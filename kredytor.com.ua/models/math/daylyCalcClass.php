<?php

class daylyCalcClass extends baseCalcClass {
	
	public $_targeted_percentstage = 1;	
	public $_frontEnd_percentstage = 1.5;	
	public $_buffer = 0;
	public $_srok_limits = array (
			'_minsrok'			=> 1,
			'_maxsrok'			=> 30,
	);
	public $_creditParams = array(
		'_percentstage'		=> 2,
		'_penystage'		=> 2,
		'_panydaystart'		=> 0,
		'_firstdayminpay'	=> 0,
		'_termmodifier'		=> -1,
		'_firstdayPayed'	=> false,
	);
	
	public function __construct($model=null) {
		parent::__construct($model);
		if ( $this->_calcUnit instanceof Zayavka && $this->_calcUnit->credit_targeted ) {
			$this->_percentstage = $this->_targeted_percentstage;
		} elseif ( $this->_calcUnit instanceof Dogovor ) {
			$this->_buffer = (float)$this->_calcUnit->d_boffer;
		}
		
		if ( Yii::app()->params['siteGoingPartIsFront'] == true ) {
			$this->_percentstage = $this->_frontEnd_percentstage;
		}
	}
	
	//Подсчет срока использования кредита
	//На дату $today
	public function countDaysInUse($today=false) {		
		if ( $today === false )
			$today = time();
		else 
			$today = strtotime($today);
		if ( !$today )
			return false;
		$today = strtotime(date('Y-m-d', $today));
		
		return ceil( ($today-$this->_calcUnit->d_date_zalog)/(60*60*24)-$this->_termmodifier );
	}
	
	//Подсчет срока пени
	//На дату $today
	public function countDaysPeny($today=false) {				
		$daysUse = $this->countDaysInUse($today);
		$days_peny=$daysUse-$this->_Params['srok']-$this->_panydaystart;
		if ( $days_peny < 0 )
			$days_peny = 0;
		
		return $days_peny;
	}
	
	//Возвращает сумму по процентам за пользование кредитом 
	//суммой $cred_summ за $cred_perriod дней/месяцев
	public function countPercentSumm($cred_summ, $cred_perriod) {		
		if ( !$cred_summ )		$cred_summ = $this->_Params['summ'];
		if ( !$cred_perriod && $this->_calcUnit instanceof Zayavka ) $cred_perriod = $this->_Params['srok'];
		elseif ( !$cred_perriod && $this->_calcUnit instanceof Dogovor ) $cred_perriod = $this->countDaysInUse();
		$cred_perriod = (int)$cred_perriod;
		if ( !$cred_perriod ) return 0;
		
		$perDay = $cred_summ/100*$this->_percentstage;		
		$total = $perDay*$cred_perriod;
		if ( $perDay < $this->_firstdayminpay && !$this->_firstdayPayed )
			$total+= $this->_firstdayminpay-$perDay;
		return $total;
	}
	
	//Возвращает сумму по пене
	//суммой $cred_summ за $cred_perriod просрочки
	public function countPenySumm($cred_summ, $cred_perriod) {
		if ( !$cred_summ )		$cred_summ = $this->_Params['summ'];
		if ( !$cred_perriod && $this->_calcUnit instanceof Dogovor ) $cred_perriod = $this->countDaysPeny();
		elseif ( !$cred_perriod )	$cred_perriod = $this->_Params['penyDays'];
		
		$perDay = $cred_summ/100*$this->_penystage;
		$total = $perDay*$cred_perriod;
		return $total;
	}
	
	//Возвращает сумму полной задолженности
	//суммой $cred_summ за $days_in_use пользования и $days_peny просрочки
	public function countTotalSumm($cred_summ, $days_in_use, $days_peny) {		
		if ( !$cred_summ )	$cred_summ = $this->_Params['summ'];
		$total = $this->countPercentSumm($cred_summ, $days_in_use)+$this->countPenySumm($cred_summ, $days_peny)+$cred_summ - $this->_buffer;
		return $total;
	}
	
	//Возвращает расчтеный первый платеж за пользование кредитом 
	//суммой $cred_summ
	public function calculatefirstMinPay($cred_summ) {
		return $this->countPercentSumm($cred_summ, 1);
	}
	
	
	/* Расчет назначения платежа по договору
	 * $dog_summ	- Сумма по договору
	 * $days_in_use - Кол-во дней пользования договора
	 * $days_peny	- Кол-во дней пени договора
	 * $pay_summ	- Сумма к распределению
	 */
	public function calculatePayment($pay_summ, $dog_summ, $days_in_use, $days_peny, $discount) {
		if ( !$dog_summ )				$dog_summ = $this->_Params['summ'];
		if ( $days_in_use === false )	$days_in_use = $this->_Params['srok'];
		
		if ( $dog_summ <= 0 || $pay_summ <= 0 || $days_in_use < 0 )
			return false;
		
		$return = array(
			'percent'			=> 0,
			'peny'				=> 0,
			'body'				=> 0,
			'buffer'			=> 0,
			'days_prol'			=> 0,
			'buffer_user'		=> 0,
			'discount'			=> $discount,
			'srok'				=> $this->_Params['srok'],
			'PerriodPayment'	=> 0,
			'firstMinPay'		=> $this->_calcUnit->firstMinPay,
			'PaymentDay'		=> $this->_calcUnit->d_PaymentDay,
			'PenyDaysPayed'		=> $this->_calcUnit->d_PenyDaysPayed,
		);
		$pay_summ+= $discount;
		
		//Нет задолженности
		if ( !$days_in_use ) {
			if ( $pay_summ > $dog_summ ) {
				$return['body']			= $dog_summ;
				$return['buffer_user']	= MathHelper::mathRound(($pay_summ-$dog_summ));
			} else {
				$return['body']			= $pay_summ;
			}
			return $return;
		}		
		
		$firstDayPay = $this->countPercentSumm($dog_summ, 1);
		if ( !$this->_firstdayPayed ) {
			$this->_firstdayPayed = true;
			$eachDayPay = $this->countPercentSumm($dog_summ, 1);
			$this->_firstdayPayed = false;
		} else {
			$eachDayPay = $firstDayPay;
		}
		$panyDayPay = $this->countPenySumm($dog_summ, 1);
		
		for ( $du=$days_in_use, $dp=$days_peny, $sum=$pay_summ; $du>0 && $sum>0; $du--, $dp-- ) {
			//Определяем оплату за этот день
			if ( $du==$days_in_use && !$this->_firstdayPayed )
				$thisDayPay = $firstDayPay;
			else 
				$thisDayPay = $eachDayPay;
			//Добавляем пеню
			$thisDayPeny = 0;
			if ( $dp > 0 )
				$thisDayPeny=$panyDayPay;
			
			$thisDayPay_check = MathHelper::mathRound($thisDayPay);
						
			if ( $sum < ($thisDayPay_check+$thisDayPeny) ) {
				break;
			}
			$return['days_prol']++;
			$return['percent']+=$thisDayPay;
			$return['peny']+=$thisDayPeny;
			$sum-=($thisDayPay_check+$thisDayPeny);
		}
		$return['peny']		= MathHelper::mathRound($return['peny']);
		$return['percent']	= MathHelper::mathRound($return['percent']);
		$pay_left = $pay_summ - MathHelper::mathRound(($return['percent']+$return['peny']));
		if ( !$du ) {
			if ( $pay_left > $dog_summ ) {
				$return['body']			= $dog_summ;
				$return['buffer_user']	= MathHelper::mathRound(($pay_left-$dog_summ));
			} else {
				$return['body'] = $pay_left;
			}
		} else {
			$return['buffer'] = $pay_left;
		}
		
		return $return;
	}
	
	//Подсчет минимального платежа
	public function minimalPayment($today) {
		$minPercent = $this->countPercentSumm(false, 1);
		if ( $this->countDaysPeny($today) )
			$minPercent+= $this->countPenySumm(false, 1);		
		return $minPercent;
	}
	
	//Дата возврата кредита
	public function getVikupDate($fromday) {
		if ( $fromday === false )
			$fromday = time();
		else
			$fromday = strtotime($fromday);
		if ( !$fromday )
			return false;
	
		$prolongation_date = $fromday + ($this->_Params['srok'] + $this->_termmodifier)*(60*60*24);		
		return $prolongation_date;
	}
	
	// Подсчет даты рекомендуемого платежа
	static function getReminderDate($days_to_finish) {
		$return = array();
		$return['select'] = 'FROM_DAYS(TO_DAYS(a.d_date_start)+a.d_term+a.d_termmodifier)';
		$return['where'] = '(TO_DAYS(a.d_date_start)+a.d_term+a.d_termmodifier-'.$days_to_finish.')';
		return $return;
	}
	
	public function countsrokDays($srok) {
		return $srok;
	}
}

?>
