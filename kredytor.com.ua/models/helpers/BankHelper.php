<?php

class BankHelper {
	
	public $lastError			= false;
	public $_card				= null;
	public $_paysys				= null;	
	public $_apprcode			= null;	
	public $_commision_rate		= '0';
	public  $_commision_stat	= '0';
	
	private $_errors = array();
	
	public function __construct($card) {
		$this->_card = $card;
		$paysysClassName = $card->outer_provider.'Paysys';
		$this->_paysys = new $paysysClassName($card);
	}

	public function logRequest($data) {
		$file ='paytoyou.txt';
		$path = realpath(getcwd().'/../reglogs').'/'.$file;
		return file_put_contents($path, '{'.date('Y-m-d H:i:s').'} '.print_r($data, true)."\n", FILE_APPEND);
	}


	public function _request($params, $url, $header=false, $sslFile=false) {		
		$ch = curl_init( $url );
		# Setup request to send json via POST.
		if ( !empty($params) ) {
			if ( is_array($params) ) {
				$params = http_build_query($params);
			}
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, '');
		# Return response instead of printing.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 25);
		
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		
		if ( !empty($sslFile) ) {
			curl_setopt($ch,CURLOPT_SSLCERT, $sslFile['crt']);
			curl_setopt($ch,CURLOPT_SSLKEY, $sslFile['pem']);
		}
		
		# Send request.
		$result = curl_exec($ch);
		//$headerSent = curl_getinfo($ch, CURLINFO_HEADER_OUT );
		//curl_close($ch);
		//return $headerSent;
		$data = array(
			'curl_errno'			=> curl_errno($ch),
			'CURLINFO_HTTP_CODE'	=> curl_getinfo($ch, CURLINFO_HTTP_CODE),
			'curl_error'			=> curl_error($ch),
		);
		
		//return $data;
		if (curl_errno($ch) != 0 || !in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), array(200, 401,  415)) || empty($res) ) {
			//throw new Exception( 'Ошибка отправки запроса '.curl_error($ch).' '.curl_getinfo($ch, CURLINFO_HTTP_CODE).' '.curl_errno($ch) );
		};

		curl_close($ch);

		return $result;
	}
	
	public function sendMoneytoCard($ammount, $order_id) {
		return $this->_paysys->sendMoneytoCard($ammount, $order_id);
	}
	
	public function verifyCardBlock() {
		return $this->_paysys->verifyCardBlock();
	}
	
	public function verifyCardResult($data) {
		return $this->_paysys->verifyCardResult($data);
	}
	
	public function checkresponse($callbackParams) {
		return 'ERROR:тестирование ошибки';
	}
	
	public function createPaymentFrame($invoiceNumb, $sum, $dognumb){
		return $this->_paysys->createPaymentFrame($invoiceNumb, $sum, $dognumb);
	}
	
	protected function setError($text) {
		$this->_errors[] = $text;
	}
	
	public function getErrors() {
		return $this->_errors;
	}
	
	public function sendInvoiceTo1C($pay_sum, $pay_params, $dogovor, $dognumb, $payDate, $state='prepaid', $lo_from = false) {
		$method = 'Invoice';
		$pay_ident=array(
			'dogovor'	=> $dognumb,
			'number'	=> $dogovor->d_zid,
			'invoice'	=> $pay_params['invoice'],
			'paysum'	=> $pay_sum,
			'payday'	=> C1Helper::formatDate($payDate),
			'method'	=> $this->_paysys,
			'apprcode'	=> $this->_apprcode,
			'status'	=> $state,
		);
		if ( $lo_from ) {
			$pay_ident['lo_from'] = $lo_from;
		}
			
		$data = array('Request'=>array(
			'ident'		=> $pay_ident,
			'paycalc'	=> $pay_params,
		));
		$xml = XmlHelper::buildXml($data);
		C1Helper::saveRequestTo1C($xml, $method, $dogovor->d_zid, $dogovor->d_lo);
	}
	
	public function commisionSum($sum) {
		return $this->_paysys->commisionSum($sum);
	}	
}
?>