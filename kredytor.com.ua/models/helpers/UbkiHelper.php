<?php

class UbkiHelper {
	
	private $isTest = 0;
	
	private $login = '612241';
	private $pass = '07082016';

	private $login_test = 'testcred21';
	private $pass_test = '9153test%';

	private $auth_url = 'https://secure.ubki.ua/b2_api_xml/ubki/auth';
	private $auth_url_test = 'https://secure.ubki.ua:4040/b2_api_xml/ubki/auth';
	private $get_url = 'https://secure.ubki.ua/b2_api_xml/ubki/xml';
	private $get_url_test = 'https://secure.ubki.ua:4040/b2_api_xml/ubki/xml';

	function getSessionId() {
		$auth = Auth::model()->find('expire > NOW()');
		if ( is_null($auth) )
			$sessionid = $this->renewSessionId();
		else
			$sessionid = $auth->sessionid;
		if ( !$sessionid )
			throw new Exception('Ошибка получения сессионного ключа');
		return $sessionid;
	}

	function renewSessionId() {
		$xml =  '<?xml version="1.0" encoding="utf-8" ?>'.PHP_EOL.
				'<doc>'.PHP_EOL.
				'	<auth login="' . ($this->isTest ? $this->login_test : $this->login) . '" pass="' . ($this->isTest ? $this->pass_test : $this->pass) . '"/>'.PHP_EOL.
				'</doc>';
		$req_id = $this->_reqn();
		$req_data = date('Y-m-d H:i:s');
		$result = $this->_request(($this->isTest ? $this->auth_url_test : $this->auth_url), base64_encode($xml));
		if ( !$result ) return false;
				
		libxml_use_internal_errors(true);
		$xml = new SimpleXMLElement($result, LIBXML_NOERROR|LIBXML_ERR_NONE|LIBXML_ERR_FATAL);
		if ( libxml_get_errors() ) return false;
		if ( (string)$xml->auth->attributes()->errcode ) return false;
		
		$sessionid = (string)$xml->auth->attributes()->sessid;
		$expire = date('Y-m-d H:i:s', strtotime((string)$xml->auth->attributes()->dateed));
		Auth::model()->deleteAll();
		$auth = new Auth();
		$auth->sessionid	= $sessionid;
		$auth->expire		= $expire;
		$auth->save();
		return $sessionid;
	}
	
	function getInfo($data) {
		if ( empty($data) ) return false;	
		$sessionid = $this->getSessionId();	
		$req_data = date('Y-m-d H:i:s');
		$req_id = $this->_reqn();
		$xml =			'				<request version="1.0" reqtype="10" reqreason="2" reqdate="'.$req_data.'" reqidout="'.$req_id.'" reqsource="1">'.PHP_EOL.
						'					<i reqlng="1">'.PHP_EOL.
						'						<ident okpo="'.$data['iid'].'" lname="'.$data['surname'].'" fname="'.$data['name'].'" mname="'.$data['lastname'].'" bdate="'.date('Y-m-d', strtotime($data['birth_date'])).'" name=""></ident>'.PHP_EOL.
						'						<mvd pser="'.$data['passport_seria'].'" pnom="'.$data['passport_number'].'" plname="'.$data['surname'].'" pfname="'.$data['name'].'" pmname="'.$data['lastname'].'" pbdate="'.date('Y-m-d', strtotime($data['birth_date'])).'"></mvd>'.PHP_EOL.
						'						<bphone decsr="Блок входящих данных для поиска в списке черных телефонов" phone="'.$data['contact_phone_mobile'].'"></bphone>'.PHP_EOL.
						'						<reqid descr="Блок входящих данных получения отчета по его идентификатору" reqid=""></reqid>'.PHP_EOL.
						'						<afs inn="'.$data['iid'].'" lname="'.$data['surname'].'" fname="'.$data['name'].'" mname="'.$data['lastname'].'" bdate="'.date('Y-m-d', strtotime($data['birth_date'])).'">'.PHP_EOL.
						($data['contact_phone_mobile'] ? '							<phone number="'.$data['contact_phone_mobile'].'" kind="1" ctype="3"></phone>' : '').
//						($data['contact_phone_home'] ? '							<phone number="'.$data['contact_phone_home'].'" kind="2" ctype="1"></phone>' : '').
						'						</afs>'.PHP_EOL.
						'						<spd inn="'.$data['iid'].'"></spd>'.PHP_EOL.
						'					</i>'.PHP_EOL.
						'				</request>';
		$result = $this->_request(($this->isTest ? $this->get_url_test : $this->get_url), '<?xml version="1.0" encoding="utf-8" ?><doc><ubki sessid="' . $sessionid . '"><req_envelope><req_xml>'.base64_encode($xml).'</req_xml></req_envelope></ubki></doc>');
		if ( !$result ) return false;
		return $result;
	}

	function _request($url, $req) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, '');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		$res = curl_exec($ch);
		/*
		if (curl_errno($ch) != 0 || !in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), array(200, 401))) {
			throw new Exception('Ошибка отправки запроса');
		};
		 * 
		 */
		curl_close($ch);
		return $res;
	}

	function _reqn() {
		list($usec, $sec) = explode(' ', substr(microtime(), 2));
		return substr($sec.$usec, 0, 15);
	}
}
?>