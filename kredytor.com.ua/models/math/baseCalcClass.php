<?php

abstract class baseCalcClass {
	
	public $_creditParams;
	public $_calcUnit = null;	
	public $_Params = array(
		'summ'			=> null,
		'srok'			=> null,
		'penyDays'		=> 0,
	);
		
	public function __get($var) {
		if ( array_key_exists($var, $this->_creditParams) )
			return $this->_creditParams[$var];
	}
	
	public function __set($var, $value) {
		if ( array_key_exists($var, $this->_creditParams) )
			$this->_creditParams[$var] = $value;
	}
	
	public function __construct($model=null) {
		if ( !is_null($model) ) {
			if ( $model instanceof Zayavka ) {
				$this->_Params['summ']	= $model->summ;
				$this->_Params['srok']	= $model->srok;
			} elseif ( $model instanceof Dogovor ) {
				$this->_Params['summ'] = $model->d_summ;
				$this->_Params['srok'] = $model->d_term;
			} elseif ( is_array($model) ) {	
				if ( empty($model['d_date_start']) || empty($model['d_summ']) || empty($model['d_term']) ) {
					die('Unsupported calc unit');
				}
				foreach ( $this->_creditParams as $paramName=>$paramValue ) {
					if ( !array_key_exists(('d'.$paramName), $model) ) {
						die('Unsupported calc unit '.'d'.$paramName);
					}
					$this->{$paramName} = $model['d'.$paramName];
				}
				$model = (object)$model;
				$date = explode(' ', $model->d_date_start);
				$model->d_date_zalog = strtotime($date[0]);
				$model->d_date_vikup = $this->getVikupDate($date[0]);
				$this->_Params['summ'] = $model->d_summ;
				$this->_Params['srok'] = $model->d_term;
			} else {
				die('Unsupported calc unit');
			}
			$this->_calcUnit = $model;
		}		
	}
	
	//Подсчет срока использования кредита
	//На дату $today
	abstract public function countDaysInUse($today=false);
	
	//Подсчет срока пени
	//На дату $today
	abstract public function countDaysPeny($today=false);
	
	//Возвращает сумму по процентам за пользование кредитом 
	//суммой $cred_summ за $cred_perriod дней/месяцев
	abstract public function countPercentSumm($cred_summ, $cred_perriod);
	
	//Возвращает сумму по пене
	//суммой $cred_summ за $cred_perriod просрочки
	abstract public function countPenySumm($cred_summ, $cred_perriod);
	
	//Возвращает сумму полной задолженности
	//суммой $cred_summ за $days_in_use пользования и $days_peny просрочки
	abstract public function countTotalSumm($cred_summ, $days_in_use, $days_peny);
	
	//Возвращает расчтеный первый платеж за пользование кредитом 
	//суммой $cred_summ
	abstract public function calculatefirstMinPay($cred_summ);
	
	
	/* Расчет назначения платежа по договору
	 * $dog_summ	- Сумма по договору
	 * $days_in_use - Кол-во дней пользования договора
	 * $days_peny	- Кол-во дней пени договора
	 * $pay_summ	- Сумма к распределению
	 */
	abstract public function calculatePayment($pay_summ, $dog_summ, $days_in_use, $days_peny, $discount);	
	
	////Подсчет колва дней пошльзования
	abstract public function countsrokDays($srok);
}

?>
