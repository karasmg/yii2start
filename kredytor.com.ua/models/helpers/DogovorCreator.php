<?php

class DogovorCreator {
	public static function createDogovor($zayavka, $date_start = false, $invoiceNumb = false) {
		if ( !($zayavka instanceof Zayavka) )
			return false;
		
		$dogovor = new Dogovor;
		$dogovor->d_zid = $zayavka->id;
		$dogovor->d_aid = $zayavka->iid;
		$sync = ManagersToLo::model()->findByAttributes(array('user_id'=>$zayavka->manager)); 
		if ( !is_null($sync) && $sync->user_lo )
			$lo_num = $sync->user_lo;
		else				
			$lo_num = '0';
		$dogovor->d_lo = $lo_num;
		if ( !$date_start ) {
			$dogovor->d_date_start = new CDbExpression('NOW()');
			$dogovor->d_PaymentDay = new CDbExpression('NOW()');
		} else {
			$dogovor->d_date_start = date('Y-m-d H:i:s', $date_start);
			$dogovor->d_PaymentDay = date('Y-m-d', $date_start);
		}
		$dogovor->d_term = $zayavka->srok;
		$dogovor->d_summ = $zayavka->summ;
		
		if ( $invoiceNumb ) {
			$dogovor->d_iid = $invoiceNumb;
		}
		
		$math	= new MathHelper($zayavka);
		$dogovor->d_percentstage = $zayavka->percentstage;
		$dogovor->d_penystage = $zayavka->penystage;
		$dogovor->d_panydaystart = $math->_panydaystart;
		$dogovor->d_firstdayminpay = $math->_firstdayminpay;
		$dogovor->d_termmodifier = $math->_termmodifier;
		$dogovor->d_firstdayPayed = (int)$math->_firstdayPayed;
		$dogovor->calc_type = $zayavka->calc_type;
		$dogovor->firstMinPay = $zayavka->firstMinPay;
		$dogovor->d_PerriodPayment = 0;
		$dogovor->d_PenyDaysPayed = 0;
		$result = $dogovor->save();
		return $result;
	}
}
?>