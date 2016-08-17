<?php

class annuitetCalcClass extends baseCalcClass {
	public static $_targeted_percentstage = 1;
	public $_payment_day;
	public $_credit_startTimestamp;
	public $_monthly_payment = 0;
	public $_firstDayminimalDifference = 0;
	public $_buffer = 0;
	public $_srok_limits = array (
			'_minsrok'			=> 3,
			'_maxsrok'			=> 36,
			'_default'			=> 12,
	);
	public $_summ_limits = array (
			'_minsumm'			=> 500,
			'_maxsumm'			=> 10000000,
	);
	public $_creditParams = array (
			'_percentstage' => 0.274, 
			'_penystage' => 1, 
			'_panydaystart' => 1, 
			'_firstdayminpay' => 0, 
			'_termmodifier' => -1, 
			'_firstdayPayed' => false,
	);

	public function __construct($model = null) {
		parent::__construct ( $model );
		if ( $this->_calcUnit instanceof Dogovor || $this->_calcUnit instanceof stdClass ) {
			$this->_credit_startTimestamp = $this->_calcUnit->d_date_zalog;
			$this->_monthly_payment = $this->_calcUnit->firstMinPay;
			$this->_buffer = (float)$this->_calcUnit->d_boffer;
		} elseif ( $this->_calcUnit instanceof Zayavka ) {
			if ( !empty($this->_calcUnit->dateStart) ) {
				$date = explode(' ', $this->_calcUnit->dateStart);
				$this->_credit_startTimestamp = strtotime($date[0]);
			} else {
				$this->_credit_startTimestamp = time();
			}
			$this->_monthly_payment = $this->countMonthlySumm($this->_calcUnit->summ, $this->_calcUnit->srok, $this->_credit_startTimestamp);
		}
		$this->_payment_day = date('j', $this->_credit_startTimestamp);
	}

	public function __get($var) {
		if ( $var == '_current_payment_date' )
			return $this->closestPaymentDate( time(), $this->_payment_day);
		return parent::__get ( $var );
	}
	
	
	// Подсчет срока использования кредита
	// На дату $today (в днях)
	public function countDaysInUse($today = false) {
		if ( $today === false )
			$today = time();
		else
			$today = strtotime( $today );
		if ( !$today )			
			return false;
		$today = strtotime(date('Y-m-d', $today));
		$diff = floor(($today-$this->_credit_startTimestamp)/60/60/24);
		return $diff - $this->_termmodifier;
	}
	
	// Подсчет даты рекомендуемого платежа
	//
	static function getReminderDate($days_to_finish) {
		$return = array();
		$return['select'] = 'DATE_ADD(a.d_date_start, INTERVAL 1 MONTH)';
		$return['where'] = '(TO_DAYS(DATE_ADD(a.d_date_start, INTERVAL 1 MONTH))-'.$days_to_finish.')';
		return $return; 
	}	
	
	
	// Подсчет срока пени
	// На дату $today
	public function countDaysPeny($today = false) {
		if ( $today === false )
			$today = time ();
		else
			$today = strtotime($today);
		if ( ! $today )
			return false;
		
		$today = strtotime(date('Y-m-d', $today));		
		$start_peny_date = $this->start_peny_date();
		$diff = floor(($today-$start_peny_date)/60/60/24);
		if ( $diff < 0 ) {
			$diff = 0;
		}		
		return $diff;
	}
	
	// Рассчитывает сумму ежемесячного аннуитетного платежа
	public function countMonthlySumm($cred_summ, $number_months, $startPerriod=false) {
		$koef = 0.04; //Коэфициент надбавки для невелирования разницы дней в зависимости от перриода.
		if ( $number_months == 1 )
			$koef = 0.00;
		$rate = $this->_percentstage*365 / 12/ 100;
		if ( !$rate ) {
			$firstDayPay = $this->_firstdayminpay;
			if ( $this->_firstdayPayed ) $firstDayPay = 0;
			return MathHelper::mathRound(($cred_summ+$firstDayPay)/$number_months);
		}
		$monthly_summ = 0;
		if( $cred_summ != 0 && $number_months != 0 )
			$monthly_summ = $rate * pow ( (1 + $rate), $number_months ) / (pow ( (1 + $rate), $number_months ) - 1) * $cred_summ;
		return MathHelper::mathRound($monthly_summ+$monthly_summ*$koef);
	}
	
	// Рассчитывает количество дней в году
	public function countDaysInYear($year) {
		$days_in_year = 365;
		if ( ($year - 2000) % 4 === 0 )
			$days_in_year = 366;
		return $days_in_year;
	}
	
	// Возвращает сумму по процентам за конкртеное количество дней 
	// Используется для рассчета полного погашения
	// суммой $cred_summ за $cred_perriod дней
	public function countPercentSumm($cred_summ, $cred_perriod, $startPerriod = false, $round=true) {
		if ( !$cred_summ ) {
			$cred_summ = $this->_Params['summ'];
		}
		if ( !$cred_perriod ) {
			if ( $this->_calcUnit instanceof Dogovor ) {
				$cred_perriod = $this->countDaysInUse();
			} elseif ( $this->_calcUnit instanceof Zayavka ) {
				$cred_perriod = $this->countsrokDays($this->_calcUnit->srok);
			}
		}
		if ( !$cred_perriod )
			return 0;
		if ( !$startPerriod )
			$startPerriod = $this->_credit_startTimestamp;
		
		$dayInterval	= 60*60*24;
		$annual_rate	= $this->_percentstage/100;
		$total_percent	= 0;
		for ( $i=1, $percentDate=$startPerriod; $i<=$cred_perriod; $i++, $percentDate+=$dayInterval ) {
			$thisPercent = $cred_summ * $annual_rate;
			if ( $i == 1 && !$this->_firstdayPayed && $thisPercent < $this->_firstdayminpay ) {
				$thisPercent = $this->_firstdayminpay;
			}
			$total_percent+=  $thisPercent;
			
		}
		if ( !$round )
			return $total_percent;
		
		return MathHelper::mathRound($total_percent);
	}
	
	// Возвращает сумму по пене
	// суммой $cred_summ за $cred_perriod просрочки
	public function countPenySumm($cred_summ, $peny_days, $round=true) {
		if ( !$cred_summ )
			$cred_summ = $this->_Params['summ'];
		if ( $peny_days === false)
			$peny_days = $this->countDaysPeny();
		if ( !$peny_days )
			return 0;
		
		$peny_rate = $this->_penystage / 100;
		$start_peny_date = $this->start_peny_date();
		$dayInterval = 60*60*24;
		$penyTotal = 0;
		
		for ( $i=0, $penyDate=$start_peny_date; $i<=$peny_days; $i++, $penyDate+=$dayInterval ) {
			$penyBody	= $this->penyBody(date('d.m.Y', $penyDate));
			$penyTotal	+= $penyBody * $peny_rate;
		}
		if ( !$round )
			return $penyTotal;

		return MathHelper::mathRound($penyTotal);
	}
	
	// Возвращает сумму полной задолженности на текущую дату
	// суммой $cred_summ за $days_in_use дней пользования и $days_peny дней просрочки
	public function countTotalSumm($cred_summ, $days_in_use, $days_peny, $commission = false) {
		if ( !$cred_summ )
			$cred_summ = $this->_Params['summ'];
		if ( $commission ) 
			$cred_summ = $cred_summ * (1 + $commission/100);		
		return $this->countPercentSumm($cred_summ, $days_in_use) + $this->countPenySumm ($cred_summ, $days_peny) + $cred_summ - $this->_buffer;
	}
	
	// Возвращает расчтеный первый платеж за пользование кредитом
	// суммой $cred_summ
	public function calculatefirstMinPay($cred_summ) {
		if ( !$cred_summ )
			$cred_summ = $this->_Params['summ'];
		return $this->countMonthlySumm($cred_summ, $this->_Params['srok']);
	}

	/*
	 * Расчет назначения платежа по договору
	 * $pay_summ - Сумма к распределению
	 * $dog_summ - Тело кредита
	 * $days_in_use - Остаток месяцев по договору
	 * $days_peny - Дней просрочки 
	 * $discount - Скидка к процентой ставке договора
	 * $comission - Комиссия при досрочном возврате	
	 */
	public function calculatePayment($pay_summ, $dog_summ, $days_in_use, $days_peny, $discount, $commission = false) {
		if ( !$dog_summ ) {
			$dog_summ = $this->_Params['summ'];
		}
		if ( $days_in_use === false )	{
			$days_in_use	= $this->countDaysInUse(false);
		}
		if ( $days_peny === false )	{
			$days_peny	= $this->countDaysPeny(false);
		}
		if ( $dog_summ <= 0 || $pay_summ <= 0 || $days_in_use < 0 )
			return false;

		$return = array (
			'percent'			=> 0, 
			'peny'				=> 0, 
			'body'				=> 0, 
			'buffer'			=> 0, 
			'days_prol'			=> 0, 
			'buffer_user'		=> 0, 
			'discount'			=> $discount, 
			'srok'				=> $this->_Params['srok'], //в месяцах
			'PerriodPayment'	=> $this->payPerriodSumm(false, true),
			'firstMinPay'		=> $this->_calcUnit->firstMinPay,
			'PaymentDay'		=> $this->_calcUnit->d_PaymentDay,
			'PenyDaysPayed'		=> $this->_calcUnit->d_PenyDaysPayed,
		);		
		$pay_summ += $discount;
		
		//Списание пени
		$penySumm		= $this->countPenySumm($dog_summ, $days_peny);
		if ( $pay_summ <= $penySumm ) {
			$return['buffer'] = $pay_summ;
			return $return;
		}
		$pay_summ-=$penySumm;
		$return['peny'] = $penySumm;
		$return['PenyDaysPayed']+= $days_peny;
				
		$nexPaymentMonth = 0;
		
		//Списание процентов
		$currDate=$this->_credit_startTimestamp;
		$oplataTime = $this->start_peny_date(false);
		for ( $du=$days_in_use, $sum=$pay_summ, $dayInterval = (60*60*24); $du>0 && $sum>0; $du--, $currDate+=$dayInterval ) {
			//Определяем оплату за этот день
			$currDate = strtotime(date('Y-m-d', $currDate));
			//var_dump( date('d.m.Y H:i:s', $currDate), date('d.m.Y H:i:s', $oplataTime) ); echo '<br>';
			$thisDayPay = $this->countPercentSumm($dog_summ, 1, $currDate, false);
			if ( !$this->_firstdayPayed ) {
				$this->_firstdayPayed = true;
			}
			if ( $sum < $thisDayPay ) {
				break;
			}
			$return['days_prol']++;
			$return['percent']+=$thisDayPay;
			$return['PerriodPayment']+=$thisDayPay;
			//var_dump($return['PerriodPayment']);echo'<br/>';
			$sum-=$thisDayPay;
			if ( $currDate >= $oplataTime ) {
				$nexPaymentMonth++;
				$oplataTime = strtotime("+1 month", $currDate);
				if ( $return['PerriodPayment'] > $this->_calcUnit->firstMinPay ) {
					$return['PerriodPayment'] = 0;
				}
			}
		}	
		
		//расписываем остаток платежа
		$pay_left = $pay_summ - MathHelper::mathRound(($return['percent']));
		if ( !$du ) {
			if ( $pay_left > $dog_summ ) {
				$return['body']			= $dog_summ;
				$return['buffer_user']	= MathHelper::mathRound(($pay_left-$dog_summ));
			} else {
				$return['body'] = $pay_left;
				$return['PerriodPayment']+=$pay_left;
			}
		} else {
			$return['buffer'] = $pay_left;
			$return['PerriodPayment']+=$pay_left;
			if ( $du==$days_in_use ) {
				$return['buffer']+=$return['peny'];
				$return['peny'] = 0;
				$return['PerriodPayment']=0;
			}
		}
		
		if ( $pay_summ+$this->payPerriodSumm() >= $this->_calcUnit->firstMinPay ) {	
			if ( $days_in_use && !$nexPaymentMonth ) {
				$nexPaymentMonth++;
			}
			$return['srok']-=$nexPaymentMonth;
			$return['PaymentDay'] = date('Y-m-d', strtotime("+".$nexPaymentMonth." month", strtotime($return['PaymentDay'])));
			$return['PerriodPayment'] = 0;
			$return['PenyDaysPayed'] = 0;
		}
		
		$firstNumb = number_format((float)$return['PerriodPayment'], 2, '.', '');
		$secondNumb = number_format((float)$this->_calcUnit->firstMinPay, 2, '.', '');
		
		
		if ( $firstNumb >= $secondNumb ) 
			$return['PerriodPayment']=0;
		//РАСЧЕТ ПОСЛЕДНЕГО ПЛАТЕЖА!
		if ( $return['days_prol'] ) {
			$newStartDate=$this->_credit_startTimestamp+$return['days_prol']*$dayInterval;
			$CountedNextPayment = $this->countMonthlySumm(($dog_summ-$return['body']), 1, $newStartDate);
			//var_dump($CountedNextPayment);
			//if ( $return['srok'] == 1 || $CountedNextPayment < $this->_calcUnit->firstMinPay ) {
			if ( $CountedNextPayment < $this->_calcUnit->firstMinPay ) {
				$return['firstMinPay'] = $CountedNextPayment;
			}
		}
		
		return $return;
	}
	
	//Подсчет минимального платежа
	public function minimalPayment($today) {
		$minPercent = $this->countPercentSumm(false, 1);
		return $minPercent;
	}
	
	//Дата возврата кредита
	public function getVikupDate($fromday=false) {
		if ( $fromday === false )
			$fromday = time();
		else
			$fromday = strtotime($fromday);
		if ( !$fromday )
			return false;
		
		$fromday = strtotime(date('Y-m-d', $fromday));
		$month_in_use = $this->_Params['srok'];
		$tillday = strtotime("+".$month_in_use." month", $fromday);
		$modifier = $this->_termmodifier*(60*60*24);
		
		return $tillday+$modifier;
	}
	
	private function start_peny_date($useDogDays=true) {
		$oplataTime = strtotime($this->_calcUnit->d_PaymentDay);
		$oplataTime = strtotime("+1 month", $oplataTime);
		if ( $useDogDays ) {
			$oplataTime = strtotime("+".$this->_calcUnit->d_PenyDaysPayed." day", $oplataTime);
		}
		while ( $oplataTime < ($this->_credit_startTimestamp+($this->_termmodifier*3600*24)) ) {
			$oplataTime = strtotime("+1 day", $oplataTime);
		}		
		
		$modifierDays = $this->_termmodifier+$this->_panydaystart;
		$znak = '+';
		if ( $modifierDays < 0 ) {
			$znak = '';
		}	
		$start_peny_date = strtotime($znak.$modifierDays." day", $oplataTime);
		
		return $start_peny_date;
	}
	
	public function payPerriodSumm($today = false, $dischargeBoffer=false) {
		/*
		if ( $today === false )
			$today = time();
		else
			$today = strtotime($today);
		if ( !$today )
			return false;
		*/
		$result = $this->_calcUnit->d_PerriodPayment;
		if ( $dischargeBoffer && $result>$this->_calcUnit->d_boffer ) {
			$result-= $this->_calcUnit->d_boffer;
		}		
		return $result;
	}
	
	public function penyBody($today = false) {
		$paymnent = $this->_calcUnit->firstMinPay;
		$penyMonth = 0;
		if ( $today === false )
			$today = time();
		else
			$today = strtotime($today);
		if ( !$today )
			return false;
		$today = strtotime(date('Y-m-d', $today));
		$penyStart	= $this->start_peny_date();
		$oplataTime = strtotime($this->_calcUnit->d_PaymentDay);
		$oplataTime = strtotime("+1 month", $oplataTime);
				
		$dayInterval = 60*60*24;
		$previousmonthNumber = $currentmonthNumber	= date('n', $penyStart);
		$penyDayNumber = date('j', $oplataTime);
		for ( $i=$penyStart; $i<$today; $i+=$dayInterval ) {
			if ( !$penyMonth ) $penyMonth++;
			$currentmonthNumber = date('n', $i);
			$currentDayNumber = date('j', $i);
			if ( $currentmonthNumber != $previousmonthNumber && $currentDayNumber >= $penyDayNumber ) {
				$penyMonth++;
				$previousmonthNumber = $currentmonthNumber;
			}
		}
		if ( !$penyMonth ) return 0;
		return $paymnent*$penyMonth-$this->payPerriodSumm();
	}

	public function countsrokDays($srok) {
		$start	= date_create(date('Y-m-d'));
		$finish = clone($start);
		$finish = date_add($finish , new DateInterval('P'.$srok.'M'));
		$diff = (int)date_diff($start, $finish)->format('%a');
		return $diff;
	}

	public function countsrokMonth($srokDays) {
		$srokMonth = round($srokDays/30);
		return $srokMonth;
	}

	public function countsrokDays1($srok) {
		$start	= date_create('Y-m-d');
		$finish = strtotime("+".$srok." month", $start);
		$diff = ceil(($finish-$start)/60/60/24);
		return $diff;
	}

	public function makeCalculation ( $summ, $srok, $date=false, $term_modifier=false, $firstdayminpay=false, $percentstage=false ) {

		$date = !$date ? date('Y-m-d') : $date;
		$this->_creditParams['_termmodifier'] = $term_modifier ? $term_modifier : $this->_creditParams['_termmodifier'];
		$this->_creditParams['_firstdayminpay'] = $firstdayminpay ? $firstdayminpay : $this->_creditParams['_firstdayminpay'];
		$this->_creditParams['_percentstage'] = $percentstage ? $percentstage : $this->_creditParams['_percentstage'];

		$payment = $this->countMonthlySumm($summ, $srok);

		$PayTimeStamp			= strtotime($date);
		$left				    = $summ;
		$paySum					= $payment;
		$totalPercent = $totalPaysum = $totalBody = $totalLeft = 0;
		$calculation = array();
		for ( $i=1; $i<=$srok; $i++ ) {
			$previousPayTimeStamp = $PayTimeStamp;
			$PayTimeStamp = strtotime("+1 month", $PayTimeStamp);
			$days_in_use = floor(($PayTimeStamp - $previousPayTimeStamp) / 60 / 60 / 24);
			if ($i == 1) {
				$days_in_use -= $this->_creditParams['_termmodifier'];
			}

			$percentUse = $this->countPercentSumm($left, $days_in_use);
			if ($i > 1) {
				$percentUse -= $this->_creditParams['_firstdayminpay'];
			}
			if ($i == $srok) {
				$paySum = $percentUse + $left;
			}
			$bodyPayment = MathHelper::mathRound($paySum - $percentUse);
			$left = round($left - $bodyPayment, 2);
			$totalPercent   += number_format($percentUse, 2, '.', '');
			$totalPaysum    += number_format($paySum, 2, '.', '');
			$totalBody      += number_format($bodyPayment, 2, '.', '');
			$calculation[] = array('i'=>$i, 'PayTimeStamp'=>date('d.m.Y', $PayTimeStamp), 'paySum'=>$paySum, 'percentUse'=>$percentUse, 'bodyPayment'=>$bodyPayment, 'left'=>$left);
		}
		$result = array('table' => $calculation,
				'total' => array(   'percent'   => $totalPercent,
									'paysum'    => $totalPaysum,
									'body'      => $totalBody,
				),
		);

		return $result;
	}

}
?>