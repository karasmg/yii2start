<?php

class MathHelper
{	
	public $_calc = null;	
	public function __get($var) {
		return $this->_calc->{$var};
	}
	
	public function __set($var, $value) {
		$this->_calc->{$var} = $value;
	}
	
	public function __construct($model=null) {
		$calcClassName = 'daylyCalcClass';
		if ( !is_null($model) && !empty($model->calc_type) ) {
			$calcClassName = $model->calc_type.'CalcClass';
		} elseif ( is_array($model) && !empty($model['calc_type']) ) {
			$calcClassName = $model['calc_type'].'CalcClass';
		}
		$this->_calc = new $calcClassName($model);
	}
	
	
	//Округление денежных сум в рамках проэкта!!!
	public static function mathRound($numb) {
		$numb = floatval($numb);
		return round($numb, 2);
	}
	
	//Подсчет срока использования кредита
	//На дату $today
	public function countDaysInUse($today=false) {
		$result = $this->_calc->countDaysInUse($today);		
		return $result;
	}
	
	//Подсчет срока пени
	//На дату $today
	public function countDaysPeny($today=false) {
		$result = $this->_calc->countDaysPeny($today);		
		return $result;
	}

	//Возвращает сумму по процентам за пользование кредитом 
	//суммой $cred_summ за $cred_perriod дней/месяцев
	public function countPercentSumm($cred_summ=0, $cred_perriod=0) {
		$result = $this->_calc->countPercentSumm($cred_summ, $cred_perriod);
		return $this->mathRound($result);		
	}
	
	//Возвращает сумму по пене
	//суммой $cred_summ за $cred_perriod просрочки
	public function countPenySumm($cred_summ=0, $cred_perriod=0) {
		$result = $this->_calc->countPenySumm($cred_summ, $cred_perriod);
		return $this->mathRound($result);
	}
	
	//Возвращает сумму полной задолженности
	//суммой $cred_summ за $days_in_use пользования и $days_peny просрочки
	public function countTotalSumm($cred_summ=0, $days_in_use=0, $days_peny=0) {
		$result = $this->_calc->countTotalSumm($cred_summ, $days_in_use, $days_peny);
		return $this->mathRound($result);
	}
	
	//Возвращает расчтеный первый платеж за пользование кредитом 
	//суммой $cred_summ
	public function calculatefirstMinPay($cred_summ=0) {
		$result = $this->_calc->calculatefirstMinPay($cred_summ);
		return $this->mathRound($result);		
	}
	
	/* Расчет назначения платежа по договору
	 * $dog_summ	- Сумма по договору
	 * $days_in_use - Кол-во дней пользования договора
	 * $days_peny	- Кол-во дней пени договора
	 * $pay_summ	- Сумма к распределению
	 */
	public function calculatePayment($pay_summ, $dog_summ=0, $days_in_use=0, $days_peny=0, $discount=0) {
		$return = $this->_calc->calculatePayment($pay_summ, $dog_summ, $days_in_use, $days_peny, $discount);
		if ( !$return )
			return false;
		
		foreach ( $return as $key=>$val ) {
			if ( is_numeric($val) )
				$return[$key] = $this->mathRound($val);
		}
		
		return $return;
	}
	
	//Подсчет минимального платежа
	public function minimalPayment($today=false) {
		$result = $this->_calc->minimalPayment($today);
		return $this->mathRound($result);
	}
	
	//Дата возврата кредита
	public function getVikupDate($fromday=false) {
		$result = $this->_calc->getVikupDate($fromday);
		return $result;
	}
	
	//Подсчет колва дней пошльзования
	public function countsrokDays($srok) {
		$result = $this->_calc->countsrokDays($srok);
		return $result;
	}
}