<?php
class terminalEasyPayClass extends paysystemClass {
	public $_paysys = 'easypay';
	private $_accessip = array(
		'93.183.196.26',
		'46.182.81.50',//офис Скарбниця
	);
	public $_request = null;
	private $_SERVICE_ID = 2560;
	
	public function __construct($_request) {
		$this->logRequest($_request);
		if ( !in_array($_SERVER['REMOTE_ADDR'], $this->_accessip) || empty($_request) ) {
			$this->output(-300, array('statusdetail' => 'Получен запрос с неразрешенного IP-адреса'));
		}
		$xmlArr=$this->XmlToArray($_request);
		$sign = $this->getValueByTag($xmlArr,'sign');
		if( !$this->checkSign($_request, $sign) ) {
			$this->output(-3001, array('statusdetail' => 'Ошибка при проверке подписи запроса'));
        }
		$serviceId = $this->getValueByTag($xmlArr,'SERVICEID');
		if ( $serviceId != $this->_SERVICE_ID ) {
			$this->output(-3001, array('statusdetail' => 'Ошибка SERVICEID'));
		}
		
		foreach ($xmlArr as $i) {
			switch ($i['tag']) {
				case 'CHECK': 
					$this->Check( $this->getValueByTag($xmlArr,'ACCOUNT') );
					return;
				case 'PAYMENT': 
					$this->Payment( $this->getValueByTag($xmlArr,'ACCOUNT'), $this->getValueByTag($xmlArr,'AMOUNT'), $this->getValueByTag($xmlArr,'ORDERID') );
					return;
				 case 'CONFIRM':
					 $this->Confirm( $this->getValueByTag($xmlArr,'PAYMENTID') );   
					 return;					 
				 case 'CANCEL':
					 $this->Cancel( $this->getValueByTag($xmlArr,'PAYMENTID') );   
					 return;
			}	
		}
	}
	
	protected function checkSign($_request, $sign) {
		if ( $_SERVER['REMOTE_ADDR'] == '46.182.81.50' ) {
			return true;
		}
		$fp = fopen(Yii::app()->basePath.'/data/certs/easyPay/Provider_1.cer', 'r');        
        $pkeyid = fread($fp, 8192);
        fclose($fp);
		$pub_key = openssl_get_publickey($pkeyid);
        $xml = str_replace($sign, '', $_request); //очищаем содержимое тега Sign, получая таким образом XML, который был подписан 
        $bin_sign = pack("H*", $sign); //переводим подпись в бинарный вид 
        $result =  openssl_verify($xml, $bin_sign, $pub_key);
		return ( $result );
	}
	
	public function output($error, $data = null, $exit=true) {
		header('Content-Type: text/xml');
		ob_start();
		$response = '<Response>'.PHP_EOL.
		'<StatusCode>'.$error.'</StatusCode>'.PHP_EOL.
		'<StatusDetail>'.(empty($data['statusdetail']) ? 'OK' : $data['statusdetail']).'</StatusDetail>'.PHP_EOL.
		'<DateTime>'.date('Y-m-d\TH:i:s', time()).'</DateTime>'.PHP_EOL.
		'<Sign></Sign>'.PHP_EOL;
		if (isset($data['accountinfo'])) {
			if (is_array($data['accountinfo']) && count($data['accountinfo']) > 0) {
				$response .= '<AccountInfo>'.PHP_EOL;
				if (isset($data['accountinfo']['account'])) {
					$response .= '<Account>'.$data['accountinfo']['account'].'</Account>'.PHP_EOL;
				}
				if (isset($data['accountinfo']['izdnum'])) {
					$response .= '<IzdNumber>'.$data['accountinfo']['izdnum'].'</IzdNumber>'.PHP_EOL;
				}
				$response .= '</AccountInfo>'.PHP_EOL;
				if (isset($data['accountinfo']['min_amount'])) {
					$response .= '<Amount.Min>'.$data['accountinfo']['min_amount'].'</Amount.Min>'.PHP_EOL;
				}
				if (isset($data['accountinfo']['max_amount'])) {
					$response .= '<Amount.Max>'.$data['accountinfo']['max_amount'].'</Amount.Max>'.PHP_EOL;
				}
			}
		}
		if (isset($data['receiptinfo']) && !empty($data['receiptinfo'])) {
			$response .= '<ReceiptInfo>'.$data['receiptinfo'].'</ReceiptInfo>'.PHP_EOL;
		}
		if (isset($data['bankdetails']) && !empty($data['bankdetails'])) {
			$response .= '<BankingDetails>'.$data['bankdetails'].'</BankingDetails>'.PHP_EOL;
		}
		if (isset($data['paymentid']) && !empty($data['paymentid'])) {
			$response .= '<PaymentId>'.$data['paymentid'].'</PaymentId>'.PHP_EOL;
		}
		if (isset($data['orderdate']) && !empty($data['orderdate'])) {
			$response .= '<OrderDate>'.$data['orderdate'].'</OrderDate>'.PHP_EOL;
		}
		if (isset($data['canceldate']) && !empty($data['canceldate'])) {
			$response .= '<CancelDate>'.$data['canceldate'].'</CancelDate>'.PHP_EOL;
		}
		$response .= '</Response>';
		$fp = fopen(Yii::app()->basePath.'/data/certs/easyPay/provider.ppk', 'r');
		$priv_key = fread($fp, 8192);
		fclose($fp);
		$pkeyid = openssl_get_privatekey($priv_key);
		openssl_sign($response, $b64sign, $pkeyid);
		openssl_free_key($pkeyid);
		$response = str_replace('<Sign></Sign>', '<Sign>'.strtoupper(bin2hex($b64sign)).'</Sign>', $response);
		$this->logRequest($response);
		echo $response;
		ob_flush();
		flush();
		if ($exit) {
			exit(0);
		}
	}
	
	private function XmlToArray($inXmlset){ 
		$resource    =    xml_parser_create(); 
		xml_parse_into_struct($resource, $inXmlset, $outArray);
		xml_parser_free($resource);
        return $outArray;
	}
	private function getValueByTag($xmlArray,$needle){
		foreach ($xmlArray as $i){
			if($i['tag']==strtoupper($needle)) {
				return $i['value'];  
			}
		}
	}
	private function datetime($time=false){
		if ( !$time )
			$time = time();
		return date('Y-m-d\TH:i:s', time());
	}
	
	private function bankdetails() {
		$bankdetails =  '
			<Payee>'.PHP_EOL.
			'	<Id>'.$payment['edrpou'].'</Id>'.PHP_EOL.
			'	<Name>'.$payment['firm_uk'].'</Name>'.PHP_EOL.
			'	<Bank>'.PHP_EOL.
			'		<Name>'.$payment['bank_uk'].'</Name>'.PHP_EOL.
			'		<Mfo>'.$payment['mfo'].'</Mfo>'.PHP_EOL.
			'		<Account>'.$payment['account'].'</Account>'.PHP_EOL.
			'	</Bank>'.PHP_EOL.
			'</Payee>'.PHP_EOL.
			'<Narrative>'.PHP_EOL.
			'	<Name>Перерахування прийнятих платежів зг. дог. № '.$narrative[(int)$org_key][0].' від '.$narrative[(int)$org_key][1].' p. та реєстру за [work_date]р. Без ПДВ</Name>'.PHP_EOL.
			'	<Vat>0</Vat>'.PHP_EOL.
			'</Narrative>';
	}
	
	private function Check($account) {
		preg_match('/^(\d+),(\d+),(\d*)$/', $account, $matches);
		if ( !is_array($matches) ) {
			$this->output(-3001, array('statusdetail' => 'Ошибка аккаунта'));
		}
		if ( empty($matches[1]) || empty($matches[2]) || empty($matches[3]) ) {
			$this->output(-3001, array('statusdetail' => 'Ошибка аккаунта'));
		}
		
		$id = $matches[1];
		$cs = $matches[2];
		$phone = $matches[3];
		
		if ( !$this->checkIdCs($id, $cs) ) {
			$this->output(-3002, array('statusdetail' => 'Ошибка аккаунта'));
		}
		$dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
			$this->output(-3003, array('statusdetail' => 'Ошибка аккаунта'));
		}
		
		$dateToday = date('d.m.Y');
		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);
		
		if ( !$max_sum ) {
			$this->output(-3004, array('statusdetail' => 'Задолженности нет'));
		}
		
		$this->output(0, array(
			'accountinfo' => array(
				'account'		=> $this->dogNumbFromId($id), 
				'max_amount'	=> $max_sum, 
				'min_amount'	=> $min_sum, 
				'izdnum'		=> substr($id, 0, 3).'-'.substr($id, 3),
			), 
		));
	}	
	
	private function Payment($account, $summ, $apprcode) {
		preg_match('/^(\d+),(\d+),(\d*)$/', $account, $matches);
		if ( !is_array($matches) ) {
			$this->output(-3001, array('statusdetail' => 'Ошибка аккаунта'));
		}
		if ( empty($matches[1]) || empty($matches[2]) || empty($matches[3]) ) {
			$this->output(-3001, array('statusdetail' => 'Ошибка аккаунта'));
		}
		
		$id = $matches[1];
		$cs = $matches[2];
		$phone = $matches[3];
		if ( !$this->checkIdCs($id, $cs) ) {
			$this->output(-3002, array('statusdetail' => 'Ошибка аккаунта'));
		}
		$this->_apprcode = $apprcode;
		if ( $this->findInvoiceByAppcode($this->_apprcode) ) {
			$this->output(-3004, array('statusdetail' => 'Такой код операции уже зарегистрирован'));
		}
		$dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
			$this->output(-3003, array('statusdetail' => 'Ошибка аккаунта'));
		}
		$dateToday = date('d.m.Y H:i:s');
		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);
		$pay_sum = number_format($summ, 2, '.', '');
		
		if ( !($min_sum <= $pay_sum) || !($pay_sum  <= $max_sum) ) {
			$this->output(-3011, array('statusdetail' => 'Сумма оплаты меньше минимальной или больше максимальной суммы'));
		}
		
		$invoiceNumb = $dogovor->prepareInvoice($pay_sum, $dateToday, $this->_paysys, '', 'new', $this->_apprcode);
		
		if ( !$invoiceNumb ) {
			$this->output(-3011, array('statusdetail' => 'Ошибка создания заказа'));
		}
		$this->output(0, array(
			'paymentid' => $invoiceNumb,
		));
	}
	
	private function Confirm($order_id) {
		$order_id = (int)$order_id;
		$invoice = $this->findInvoiceById($order_id);
		if ( empty($invoice) ) {
			$this->output(-3021, array('statusdetail' => 'Ошибка PaymentId'));
		}	
		if ( $invoice->i_status == 'cancel' ) {
			$this->output(-3025, array('statusdetail' => 'Попытка полаты отмененного инвойса'));
		} elseif ( $invoice->i_status == 'new' ) {
			$dateToday = date('d.m.Y H:i:s', strtotime($invoice->i_date_create));
			$state = 'prepaid';
			$this->_apprcode = $invoice->i_apprcode;
			$dogovor = Dogovor::model()->find('d_id = '.(int)$invoice->i_dognumb);
			if ( empty($dogovor) ) {
				$this->output(-3023, array('statusdetail' => 'Ошибка Подтверждения платежа'));
			}
			$pay_params = $dogovor->makePayment($invoice->i_summ, $dateToday, $this->_paysys, '', $state, $this->_apprcode, 0, $order_id);
			if ( !$pay_params ) {
				$this->output(-3024, array('statusdetail' => 'Ошибка Подтверждения платежа'));
			}
			$zayavka	= Zayavka::model()->find('id = :id', array('id'=>$dogovor->d_zid));
			$date_timeToday = date('d.m.Y H:i:s', strtotime($invoice->i_date_create));
			$this->sendInvoiceTo1C($invoice->i_summ, $pay_params, $dogovor, $zayavka->zayavkaNumb, $date_timeToday, $state);
		}
		$this->output(0, array(
			'paymentid'		=> $order_id,
			'orderdate'		=> $this->datetime(),
		));
	}	
	
	private function Cancel($order_id) {
		$order_id = (int)$order_id;
		$invoice = $this->findInvoiceById($order_id);
		if ( empty($invoice) ) {
			$this->output(-3021, array('statusdetail' => 'Ошибка PaymentId'));
		}
		if ( $invoice->i_status != 'new' && $invoice->i_status != 'cancel' ) {
			$this->mailToAdmin('Попытка удаления проведенного инвойса', 'Попытка удаления проведенного инвойса '.$order_id);
			$this->output(0, array(
				'paymentid'		=> $order_id,
				'canceldate'	=> $this->datetime(),
			));
		}
		$invoice->i_status = 'cancel';
		$invoice->save();
		Dogovor::model()->deleteAll('d_iid='.$order_id);
		$this->output(0, array(
			'paymentid'		=> $order_id,
			'canceldate'	=> $this->datetime(),
		));
	}	
}
?>
