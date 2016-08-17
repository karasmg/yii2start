<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Formlabel extends CFormModel
{
	public function errorStyle( $msg, $dopclass = '', $close = false ) {
		if ( $dopclass ) $dopclass = ' '.$dopclass;
		if ( $close ) $close = '<button class="close" data-dismiss="alert" type="button">×</button>';
		return '<div class="alert alert-error'.$dopclass.'">'.$close.'<strong>'.Yii::t('main', 'Error').'!</strong> '.$msg.'</div>';
	}
}
