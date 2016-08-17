<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class OrderFrom extends Formlabel
{
	public $userId;
	public $orderList;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('userId, orderList, verifyCode', 'required',
                  'message'=>$this->errorStyle(Yii::t('main', 'Enter value for {attribute}')) ),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements() ),			
		);
	}

	public function attributeLabels()
	{
		return array(
			'userId'			=> '',
			'orderList'			=> '',
			'verifyCode'	=> (Yii::t('main', 'verify code')),
		);
	}
	
	public function generate($controller) {
		if(isset($_POST['OrderFrom']))
		{
			$this->attributes	= $_POST['OrderFrom'];
			$this->userId		= Yii::app()->user->id;
			$this->orderList	= Yii::app()->user->_order;
			if ( $this->validate() ) {
				$message = $controller->renderPartial('contactform_success', array('name'=>$this->name, 'phone'=>$this->phone, 'email'=>$this->email, 'text'=>$this->text), true);
				$controller->sendEmail(Yii::app()->params['adminEmail'], Yii::t('site', 'Contact form from site'), $message);
				echo $message = $controller->renderPartial('contactform_success', array(), true);
				return;
			}
		}
		echo $controller->renderPartial('/site/ContactForm', array('model'=>$this));
	}
}
