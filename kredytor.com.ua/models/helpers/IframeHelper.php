<?php

class IframeHelper {
	
	/*
	* Возвращает признак нахождения пользователя в рамках IFRAME API
	*/
	public static function camedFromIframe() {
		return !empty($_SESSION['zayavka']['inn']);
	}
}
?>