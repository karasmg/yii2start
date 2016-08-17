<?php

/**
 * PassrecoveryForm class.
 * PassrecoveryForm is the data structure for keeping
 * user Passrecovery form data. It is used by the 'Passrecovery' action of 'SiteController'.
 */
class RecoveryForm extends Formlabel
{
	public $username;
	private $user = null;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username', 'required'),
			array('username', 'check_mail'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'		=> (Yii::t('main', 'User Email')),
		);
	}


	public function send_token () {		
		if ( is_null($this->user) )
			return false;
		$user = $this->user;
		
		//отослать токен на почту
		$email = $this->username;
		$token = md5 ( uniqid ( $email ) . $email );
		$user->u_token = $token;
		$user->save (false, array('u_token'));
		$subject = Yii::t('site', 'password recovery').' '.Yii::t('site', 'on the site').' '.Yii::app()->getRequest()->serverName;
		$message = ServiceHelper::render('site/passrecovery/letter_with_token', array('token' => $token), true, false);
		$result = ServiceHelper::sendEmail($email, $subject, $message);
		
		return $result;
	}
	
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether Passrecovery is successful
	 */
	public function check_mail($attribute, $params)
	{
			$user = Users::model()->find('u_email=:u_email', array(':u_email'=>$this->username));
			if ( is_null($user) ) {
				$this->addError ( $attribute, Yii::t ( 'site', 'We has not such mail in our base' ) );
				return false;
			}
			$this->user = $user;
			return true;
	}
}
