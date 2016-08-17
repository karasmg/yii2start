<?php
class paysystemClass {
	public $_paysys;
	public $_request = null;
	public $_apprcode = '';
	
	public function __construct($_request=false) {
		
	}
		
	public function logRequest($data) {
		$file =$this->_paysys.'.txt';
		$path = realpath(getcwd().'/../reglogs').'/'.$file;
		return file_put_contents($path, '{'.date('Y-m-d H:i:s').' '.$_SERVER['REMOTE_ADDR'].'} | '.$this->_apprcode.' | '.print_r($data, true)."\n", FILE_APPEND);
	}
	
	public  function checkIdCs($id, $cs) {
		if ( !preg_match('/^\d{11}$/', $id) || !preg_match('/^\d{12}$/', $cs) ) {
			return false;
		}
		$cs_check = $this->buildCs($id);
		return ( $cs_check == $cs );
	}
	
	public function buildIdfromDogNumb($dog_numb) {
		return substr($dog_numb, 0, 3).substr($dog_numb, 4);
	}
	
	public static function buildCs($id) {
		$sr = substr($id, strlen($id)-1);
		$result = $id;
		for ($i = 0, $c = strlen($result); $i < $c; $i++) {
			$sr++;
			if ($sr > 9) {
				$sr = 0;
			}
			$l = $result[$i]+$sr;
			$result[$i] = $l > 9 ? substr($l, 1, 1) : $l;
		}
		return $sr.$result;
	}
	
	public function getDogovorById($id) {
		$dog_numb = $this->dogNumbFromId($id);
		$model = Dogovor::model()->find(array(
			'condition'	=> 'd_zid=( SELECT id FROM `zayavka` WHERE zayavkaNumb = "'.$dog_numb.'" )',
			'order'		=> 'd_version DESC',
		));
		if ( is_null($model) ) {
			return false;
		}
		return $model;
	}
	
	public function getZayavka1CData($id) {
		$dog_numb = $this->dogNumbFromId($id);
		$model	= Zayavka::model()->find('zayavkaNumb = :zayavkaNumb', array('zayavkaNumb'=>$dog_numb));
		if ( is_null($model) ) {
			return false;
		}
		return $model;
	}
	
	public function dogNumbFromId($id) {
		return substr($id, 0, 3).'-'.substr($id, 3);
	}
	
	public function findInvoiceByAppcode($apprcode, $usePaysys=true) {
		$search = 'i_apprcode = :i_apprcode';
		$params = array(':i_apprcode'=>$apprcode);
		if ( $usePaysys ) {
			$search.=' AND i_paysys = :i_paysys';
			$params[':i_paysys'] = $this->_paysys;
		}
		$model = Invoices::model()->find($search, $params); 
		return $model;
	}
	
	public function findInvoiceById($id) {
		$search = 'i_id = :i_id';
		$params = array(':i_id'=>$id);
		if ( $usePaysys ) {
			$search.=' AND i_paysys = :i_paysys';
			$params[':i_paysys'] = $this->_paysys;
		}
		$model = Invoices::model()->find($search, $params); 
		return $model;
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
	
	public function mailToAdmin($subject, $message) {
		$email = Yii::app()->params['adminEmail'];
		$sender = explode(',', $email);
		$subject.=' '.$this->_paysys;
		
		ServiceHelper::sendEmail($sender, $subject, $message);
		return true;
	}
}
?>
