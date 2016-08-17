<?php

class C1Helper {

	public static function logged($text, $file){
		$filename = $_SERVER['DOCUMENT_ROOT'].'/log/'.$file.'.log';
		if (!$handle = fopen($filename, 'a')) {
			//echo "Не могу открыть файл ($filename)";
			return false;
		}
		$str = "[".date("Y-m-d H:i:s")."] ".$text."\n";
		if (fwrite($handle, $str) === FALSE) {
			//echo "Не могу произвести запись в файл ($filename)";
			return false;
		}
		@chmod($filename, 0666);
		fclose($handle);
	}

	public static function sendDataTo1C($xml) {
		$response_ws = self::sendweb($xml);
		return $response_ws;
	}
	
	private static function sendweb($xml) {
		return false;
		self::logged('Start web-service', 'ws');
		try {
			$localWsdlFileName = $_SERVER['DOCUMENT_ROOT']."/ini/ws.xml";
			$remoteWsdlUrl = "http://217.76.198.86:1979/ws/ELWebServiceBK.1cws?wsdl";
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $remoteWsdlUrl,
				//CURLOPT_USERPWD => 'Exchange:Exchange',
				CURLOPT_HEADER => 0, 
				CURLOPT_RETURNTRANSFER => 1, 
				CURLOPT_TIMEOUT => 10, 
				CURLOPT_CONNECTTIMEOUT => 5
			));
			curl_exec($ch);
			if (curl_errno($ch) == 0 && in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), array(200, 401))) {
				$client = new SoapClient($localWsdlFileName, array(
					"location"		=> $remoteWsdlUrl,
					"login"			=> "Exchange", 
					"password"		=> "Exchange", 
					"exceptions"	=> 0, 
					//"soap_version"	=> SOAP_1_2,
					//"cache_wsdl"	=> WSDL_CACHE_NONE, 
					//"trace"			=> true, 
					//"connection_timeout" => 20,
				));
				//$result_ws = $client->__soapCall("get", array('xml'=>$xml) );
				//$result_ws = $client->__getFunctions();
				//$result_ws = $client->__getTypes();
				//$result_ws = $client->getResponse();
				//$result_ws = $client->__getTypes();
				$result_ws = $client->get($xml);
				 /*
				var_dump( $result_ws );
				echo "REQUEST:\n" . $client->__getLastRequest() . "\n";
				exit(0);
				
				if ( is_soap_fault($result_ws) ) {
					echo "SOAP Fault: (faultcode: {$result_ws->faultcode}, faultstring: {$result_ws->faultstring})";
				}
				exit(0);
				*/
				$text_ws = str_replace(array("'", "&", "<>"), array('"', ';amp;', ''), base64_decode($result_ws->return));
				libxml_use_internal_errors(true);
				$xml_ws = new SimpleXMLElement(iconv('windows-1251', 'utf-8', $text_ws), LIBXML_NOERROR|LIBXML_ERR_NONE|LIBXML_ERR_FATAL);
				if (!libxml_get_errors()) {
					self::logged('Get response (ok) web-service', 'ws');
					return iconv('windows-1251', 'utf-8', $text_ws);
				} else {
					self::logged('Get response (error) web-service', 'ws');
					return false;
				}
			} else {
				self::logged('Check web-service (error)', 'ws');
				return false;
			}
			curl_close($ch);
		} catch (SoapFault $e) {
			self::logged('End web-service (error:'.$e->getMessage().')', 'ws');
			return false;
		}
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
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		$res = curl_exec($ch);

		if (curl_errno($ch) != 0 || !in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), array(200, 401))) {
			throw new Exception('Ошибка отправки запроса');
		};
		curl_close($ch);
		return $res;
	}
	
	public static function saveRequestTo1C($xml, $method, $row_id, $dyrectedto) {
		if ( !$dyrectedto )
			$dyrectedto = 'expert';
		$model_old = Request1c::model()->find('type=:type AND row_id=:row_id AND dyrectedto=:dyrectedto', array(
			'type'		=> $method,
			'row_id'	=> $row_id,
			'dyrectedto'=> $dyrectedto,
		));
		if ( !is_null($model_old) ) {
			$model_old->active = 0;
			$model_old->save();
		}
			
		$model = new Request1c();
		$model->type = $method;
		$model->request = $xml;
		$model->row_id = $row_id;
		$model->request_date = new CDbExpression('NOW()');
		$model->dyrectedto = $dyrectedto;
		$model->response = new CDbExpression('NULL');
		$model->response_date = new CDbExpression('NULL');
		$model->last_showed = new CDbExpression('NULL');
		$model->active = 1;
		return ( $model->save() );
	}
	
	public static function getRequestsTo1C( $type ) {
		$requests = Request1c::model()->findAll('active=1 AND dyrectedto=:dyrectedto AND ( last_showed IS NULL OR last_showed <= date_sub(now(), interval 10 minute) )', array('dyrectedto'=>$type));
		$requests = Request1c::model()->findAll('active=1 AND dyrectedto=:dyrectedto AND ( last_showed IS NULL OR last_showed <= NOW() )', array('dyrectedto'=>$type));

		return $requests;
	}
	
	public static function formatDate($date) {
		$time = strtotime($date);
		if ( !$time ) return false;
		return date('YmdHis', $time);
	}
}
?>