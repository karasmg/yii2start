<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ContactForm extends Formlabel
{
	public $name;
	public $phone;
	public $email;
	public $text;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('name, phone, email, text, verifyCode', 'required',
                  'message'=>$this->errorStyle(Yii::t('main', 'Enter value for {attribute}')) ),
			array('email', 'email'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements() ),			
		);
	}

	public function attributeLabels()
	{
		return array(
			'name'			=> (Yii::t('main', 'User Name')),
			'phone'			=> (Yii::t('main', 'phone number')),
			'email'			=> (Yii::t('site', 'Your Email')),
			'text'			=> (Yii::t('site', 'Text')),
			'verifyCode'	=> (Yii::t('main', 'verify code')),
		);
	}
	
	public function generate($controller) {
		if(isset($_POST['ContactForm']))
		{
			$this->attributes=$_POST['ContactForm'];
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
