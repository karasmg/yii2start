<?php

class SmsHelper {
	private static $_login		= 'Skarbnica';
	private static $_pass		= 'yN6rt5A$';
	private static $_alfaname	= 'CreditorXXI';
	private static $_smsurl		= 'http://fastsms.telsystems.com.ua/websend/';
	
	public static function sendSms($phone, $text) {
		$xml =  
			'<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL.
			'<request method="send-sms" login="'.static::$_login.'" passw="'.static::$_pass.'">'.PHP_EOL.
			'	<msg id="1" phone="'.$phone.'" sn="'.static::$_alfaname.'" encoding="cyrillic">'.$text.'</msg>'.PHP_EOL.
			'</request>';
		
		$file = dirname(__FILE__).'/../../../sms.txt';
        $row = date('d.m.Y H:i'). '   ' .$phone . ':   ' . $text . "\n";
        file_put_contents($file, $row, FILE_APPEND);
          
		return static::request($xml, static::$_smsurl);
	}
	
	private static function request($xml, $url) {
		if( !Yii::app()->params['sms_send'] ) {
			return true;
		}
		$ch = curl_init();
		$url_base = $url;
		$url_query = $xml;
		curl_setopt($ch, CURLOPT_URL, $url_base);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $url_query);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		if (curl_errno($ch) != 0 || !in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), array(200, 401))) {
			$result = false;
			//$result = "";
			//$result .= "<errno>".curl_errno($ch)."</errno>\n";
			//$result .= "<error>".curl_error($ch)."</error>\n";
		}
		curl_close($ch);
		return $result;
	}
}

?>
