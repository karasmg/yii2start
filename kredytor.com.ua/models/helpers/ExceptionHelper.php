<?php

class ExceptionHelper extends Exception {

	public static function exсeptionLog($e) {
		$msg = date('d.m.Y H:i').'-> '.$e->getMessage().'<br> ('.$e->getFile().':'.$e->getLine().")";
		Controller::mailToAdmin( Yii::t('site', 'Ошибка при оформлении заявки'), $msg );
		return true;
	}
}