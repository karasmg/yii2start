<?php

/**
 * NewpassForm class.
 * NewpassForm is the data structure for keeping
 * user Newpass form data. It is used by the 'Passrecovery' action of 'SiteController'.
 */
class NewpassForm extends Formlabel
{
	public $token;
	public $password;
	public $passwordConfirm;
	public $user;


	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// password and passwordConfirm are required
			array('password, passwordConfirm', 'required'),
			array('token', 'check_token'),
			array('password', 'check_pass'),			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password'				=> (Yii::t('main', 'Password')),
			'passwordConfirm'		=> (Yii::t('main', 'Confirm password')),				
		);
	}

	public function check_pass($attribute, $params)
	{
		if ( $this->password && $this->password !== $this->passwordConfirm ) {
			$this->addError('passwordConfirm', Yii::t('main', 'Password not matches'));
			return false;
		}
		return true;
	}

	public function saveNewPass() {
		$this->user->u_pass = $this->password;
		$this->user->u_pass_confirm = $this->passwordConfirm;
		$this->user->u_token = new CDbExpression('Null');
		$this->user->setScenario('insert');
		$validate = $this->user->validate();
		if($validate) {
			$this->user->save();
			return true;
		} 
		return false;
	}
	
	
	public function check_token($attribute, $params)
	{
		$this->user = Users::model()->find('u_token=:u_token AND u_token IS NOT NULL', array(':u_token'=>$this->token) );
		if ( is_null($this->user) ) {
			$this->addError ( $attribute, Yii::t ( 'site', 'link has expired or is used already' ) );
		}
	}
}
