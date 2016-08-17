<?php

/**
 * PassrecoveryForm class.
 * PassrecoveryForm is the data structure for keeping
 * user Passrecovery form data. It is used by the 'Passrecovery' action of 'SiteController'.
 */
class InnForm extends Formlabel
{
	public $inn;
	public $anketa = null;
	public $user = null;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array( 
			array('inn', 'required'),
			array('inn', 'numerical', 'integerOnly'=>true, 'min'=>1000000000),
			array('inn', 'length', 'max'=>10),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'inn'		=> (Yii::t('site', 'Inn')),
		);
	}


	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether Passrecovery is successful
	 */
	public function check_inn()
	{
			$anketa = Anketa::model()->find('iid=:iid', array(':iid'=>$this->inn));
			if ( is_null($anketa) ) {
				return true;
			}
			return false;
	}
}
