<?php
class loToLoClass extends paysystemClass {
	public $_paysys = 'lo';
	public $_request = null;
	public $_lo = null;
	
	public function __construct($_request) {
		$this->logRequest($_request);
		$this->_request = $_request;
	}
	
	public function makepayment($_lo) {
		$this->_lo = $_lo;
		$id = $this->_request['id'];
		$cs = $this->_request['cs'];
		if ( !$this->checkIdCs($id, $cs) ) {
			return array('result'=>0, 'message'=>'Ошибка в id cs');
		}
		$dateToday = date('d.m.Y H:i:s', strtotime($this->_request['pay_day']));
		$dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
			return array('result'=>0, 'message'=>'Договор по id не найден');
		}
		
		$from_prev_buffer = $dogovor->d_boffer;
		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);
		$pay_sum = floatVal(preg_replace('/[^0-9,.]/', '', $this->_request['summ']));
		if ( !($min_sum <= $pay_sum) || !($pay_sum  <= $max_sum) ) {
			return array('result'=>0, 'message'=>'Сумма ('.$pay_sum.') не попадает в вилку ('.$min_sum.' - '.$max_sum.')');
		}
		$state = 'paid';
		$pay_params = $dogovor->makePayment($pay_sum, $dateToday, $this->_paysys, $this->_lo, $state);

		
		if ( !$pay_params ) {
			return array('result'=>0, 'message'=>'Не удалось провести оплату');
		}
		$this->sendInvoiceTo1C($pay_sum, $pay_params, $dogovor, $this->dogNumbFromId($id), $dateToday, $state, $this->_lo);
		
		$new_dogovor = $this->getDogovorById($id);
		$pay_ident = array(
			'from_prev_buffer'	=> $from_prev_buffer,
			'percentstage'		=> $new_dogovor->d_percentstage,
			'penystage'			=> $new_dogovor->d_penystage,
			'penysrok'			=> $new_dogovor->countDaysPeny($dateToday),
			'zalogdate'			=> C1Helper::formatDate( date('Y-m-d', $new_dogovor->d_date_zalog) ),
			'vikupdate'			=> C1Helper::formatDate( date('Y-m-d', $new_dogovor->d_date_vikup) ),
			'summ'				=> $new_dogovor->d_summ,
		);
		
		return array(
			'result'	=> 1, 
			'message'	=> '',
			'payment'	=> ($pay_params+$pay_ident),
		);
		return $pay_params;
	}
}
?>
