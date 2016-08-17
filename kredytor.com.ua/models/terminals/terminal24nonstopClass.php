<?php
class terminal24nonstopClass extends paysystemClass {
	public $_paysys = 'terminal_24';
	private $_accessip = array(
		'193.104.58.50',
		'193.104.58.150',
		'46.182.81.50',//офис Скарбниця
	);
	private $_SECRET = 'ga34s456dffk456a';
	public $_request = null;
	private $_SERVICE_ID = 1;
	
	public function __construct($_request) {
		$this->logRequest($_request);
		if ( !in_array($_SERVER['REMOTE_ADDR'], $this->_accessip) || empty($_request) ) {
			$this->output(-101);
		}
		$request = new SimpleXMLElement($_request, LIBXML_NOERROR|LIBXML_ERR_NONE|LIBXML_ERR_FATAL);
		if ( libxml_get_errors() ) {
			$this->output(-101);
		}
		$this->_request = $request;
		if ( !$this->checkSign() ) {
			$this->output(-101);
		}
		$actionName = 'action'.$this->_request->act.'func';
		if ( method_exists($this, $actionName) ) {
			call_user_func( array($this, $actionName) );
		}
		$this->output(-101);
	}
	
	protected function checkSign() {
		$checkSign = strtoupper(md5($this->_request->act.'_'.$this->_request->pay_account.'_'.$this->_request->service_id.'_'.$this->_request->pay_id.'_'.$this->_SECRET));
		//var_dump($checkSign);
		return ( $checkSign == $this->_request->sign );
	}
	
	public function output($error, $data = null, $exit = true) {
		header('Content-Type: text/xml');
		ob_start();
		$response = '<?xml version="1.0" encoding="utf-8" ?>';
		$response .= '<pay-response>';
		$response .= '<status_code>'.$error.'</status_code>';
		if (isset($data['account'])) {
			$response .= '<account>'.$data['account'].'</account>';
		}
		if (isset($data['max_amount'])) {
			$response .= '<max_amount>'.$data['max_amount'].'</max_amount>';
		}
		if (isset($data['min_amount'])) {
			$response .= '<min_amount>'.$data['min_amount'].'</min_amount>';
		}
		if (isset($data['service_id'])) {
			$response .= '<service_id>'.$data['service_id'].'</service_id>';
		}
		if (isset($data['amount'])) {
			$response .= '<amount>'.$data['amount'].'</amount>';
		}
		if (isset($data['pay_id'])) {
			$response .= '<pay_id>'.$data['pay_id'].'</pay_id>';
		}
		if (isset($data['name'])) {
			$response .= '<name>'.$data['name'].'</name>';
		}
		if (isset($data['parameters'])) {
			$response .= '<parameters>'.$data['parameters'].'</parameters>';
		}
		if (isset($data['description'])) {
			$response .= '<description>'.$data['description'].'</description>';
		}
		if (!empty($data['transaction']) && is_array($data['transaction']) && count($data['transaction']) > 0) {
			$response .= '<transaction>';
			if (isset($data['transaction']['pay_id'])) {
				$response .= '<pay_id>'.$data['transaction']['pay_id'].'</pay_id>';
			}
			if (isset($data['transaction']['service_id'])) {
				$response .= '<service_id>'.$data['transaction']['service_id'].'</service_id>';
			}
			if (isset($data['transaction']['amount'])) {
				$response .= '<amount>'.$data['transaction']['amount'].'</amount>';
			}
			if (isset($data['transaction']['status'])) {
				$response .= '<status>'.$data['transaction']['status'].'</status>';
			}
			if (isset($data['transaction']['time_stamp'])) {
				$response .= '<time_stamp>'.$data['transaction']['time_stamp'].'</time_stamp>';
			}
			$response .= '</transaction>';
		}
		$response .= '<time_stamp>'.date('d.m.Y H:i:s').'</time_stamp>';
		$response .= '</pay-response>';
		$this->logRequest($response);
		echo $response;
		ob_flush();
		flush();
		if ($exit) {
			exit(0);
		}
	}
	
	public function action1func() {
		preg_match('/^(\d+);(\d+);(\d*)$/', $this->_request->pay_account, $matches);
		if ( !is_array($matches) ) {
			$this->output(-101);
		}
		if ( empty($matches[1]) || empty($matches[2]) || empty($matches[3]) ) {
			$this->output(-101);
		}
		
		$id = $matches[1];
		$cs = $matches[2];
		$phone = $matches[3];
		if ( !$this->checkIdCs($id, $cs) ) {
			$this->output(-40);
		}
		$dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
			$this->output(-41);
		}
		$dateToday = date('d.m.Y');
		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);
		
		if ( !$max_sum ) {
			$this->output(-41);
		}
		
		$this->output(21, array(
			'account'		=> (string)$id.';'.(string)$cs.';'.(string)$phone, 
			'service_id'	=> $this->_SERVICE_ID, 
			'max_amount'	=> $max_sum, 
			'min_amount'	=> $min_sum, 
			'name'			=> '№ договору: '.$this->dogNumbFromId($id), 
			'parameters'	=> '',
		));
	}
	
	public function action4func() {
		preg_match('/^(\d+);(\d+);(\d*)$/', $this->_request->pay_account, $matches);
		if ( !is_array($matches) ) {
			$this->output(-101);
		}
		if ( empty($matches[1]) || empty($matches[2]) || empty($matches[3]) ) {
			$this->output(-101);
		}
		$id = $matches[1];
		$cs = $matches[2];
		$phone = $matches[3];
		if ( !$this->checkIdCs($id, $cs) ) {
			$this->output(-40);
		}
		$this->_apprcode = $this->_request->pay_id;
		if ( $this->findInvoiceByAppcode($this->_apprcode) ) {
			$this->output(-100);
		}
		$dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
			$this->output(-41);
		}
		$dateToday = date('d.m.Y H:i:s');
		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);
		$pay_sum = number_format((string)$this->_request->pay_amount, 2, '.', '');
		
		if ( !($min_sum <= $pay_sum) || !($pay_sum  <= $max_sum) ) {
			$this->output(-42);
		}
		$state = 'prepaid';
		$pay_params = $dogovor->makePayment($pay_sum, $dateToday, $this->_paysys, $this->_request->trade_point, $state, $this->_apprcode);
		//$pay_params = false;
		if ( !$pay_params ) {
			$this->output(-90);
		}
		$description = "Відсотки за користування: ".number_format($pay_params['percent']+$pay_params['peny'], 2, '.', '')." грн.<br/>";
		$description.= "Тіло кредиту: ".number_format(($dogovor->d_summ-$pay_params['body']), 2, '.', '')." грн.<br/>";
		$description.= "Договір подовжено до: ".date('d.m.Y', ($dogovor->d_date_vikup+($pay_params['days_prol']*60*60*24)))."<br/>";
		$description.= "Номер договору: ".$this->dogNumbFromId($id)."<br/>";
		$this->sendInvoiceTo1C($pay_sum, $pay_params, $dogovor, $this->dogNumbFromId($id), $dateToday, $state);
		
		$this->output(22, array(
			'service_id'	=> $this->_SERVICE_ID, 
			'amount'		=> $pay_sum, 
			'pay_id'		=> $this->_apprcode, 
			'description'	=> $description,
		));
	}
	
	public function action7func() {
		$this->_apprcode = $this->_request->pay_id;
		if ( !$invoice = $this->findInvoiceByAppcode($this->_apprcode) ) {
			$this->output(-10);
		}
		$this->output(11, array(
			'transaction' => array(
				'pay_id'		=> $this->_apprcode, 
				'service_id'	=> $this->_SERVICE_ID, 
				'amount'		=> $invoice->i_summ, 
				'status'		=> '111', 
				'time_stamp'	=> date('d.m.Y H:i:s', strtotime($invoice->i_date_create)),
			)
		));
	}
}
?>
