<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $u_id
 * @property string $u_email
 * @property string $u_pass
 * @property integer $u_active
 * @property integer $u_subscribe
 * @property integer $sms_code
 * @property integer $verify_attempts
 * @property string $u_access_level
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	
	public $u_pass_confirm = '';
	public $u_pass_current = '';
	public $verifyCode;
	public $smsVerifyCode = '';
	
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('u_email, u_login, u_pass, u_pass_confirm', 'required', 'on'=>'insert'),
			array('u_phone, u_fio, u_email, verifyCode', 'required', 'on'=>'SiteRegistration'),
			array('u_active, u_subscribe, u_boss_id', 'numerical', 'integerOnly'=>true),
			array('u_login', 'length', 'max'=>30),
			array('u_session', 'length', 'max'=>50),			
			array('u_fio', 'length', 'max'=>400),
			array('u_email', 'unique'),
			array('u_phone', 'length', 'max'=>20),
			array('u_email, u_pass, u_pass_confirm, u_pass_current, u_token', 'length', 'max'=>50),
			array('u_email', 'email', 'on'=>'SiteRegistration, insert'),
			array('u_access_level, u_subscribe, u_passneedchange, verify_attempts', 'length', 'max'=>1),
			array('sms_code, smsVerifyCode, verify_attempts', 'numerical', 'integerOnly'=>true, 'on'=>'check_phone'),
			array('smsVerifyCode', 'smsVerifyCode', 'on'=>'check_phone'),
			array('u_id, u_email, u_pass, u_active, u_access_level, u_subscribe, u_login, u_passneedchange, u_fio, u_boss_id, u_phone, u_session, u_token', 'safe', 'on'=>'search'),
			array('last_change_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'insert'),
			array('last_change_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'update'),
			array('u_login', 'default', 'value'=>new CDbExpression('NULL'), 'setOnEmpty'=>true,'on'=>'insert'),
			array('u_login', 'default', 'value'=>new CDbExpression('NULL'), 'setOnEmpty'=>true,'on'=>'update'),
			array('u_pass', 'compare', 'compareAttribute'=>'u_pass_confirm', 'on'=>'insert'),
			array('u_pass', 'check_pass', 'on'=>'update, sitePersonalpage'),
			array('u_pass_current', 'check_old_pass', 'on'=>'update, sitePersonalpage'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements() || ( !Yii::app()->user->isGuest && Yii::app()->user->access_level>3), 'on'=>'SiteRegistration'),
			array('verify_attempts', 'default', 'value'=>5, 'setOnEmpty'=>true,'on'=>'insert'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}
	
	public function selectValues() {
		return array(
			'u_boss_id'			=> array(self::BELONGS_TO, 'Users', 'u_id', 'Users', 'u_fio', false, array('u_access_level'=>array(0,1,2,3,5,6,7,8,9))),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'u_id'				=> (Yii::t('main', 'Row Id')),
			'u_boss_id'			=> (Yii::t('main', 'Boss')),
			'u_email'			=> (Yii::t('main', 'User`s email (login)')),
			'u_login'			=> (Yii::t('main', 'Login name (email)')),
			'u_fio'				=> (Yii::t('main', 'User Name')),
			'u_pass'			=> (Yii::t('main', 'Password')),
			'u_pass_confirm'	=> (Yii::t('main', 'Confirm password')),
			'u_pass_current'	=> (Yii::t('main', 'Current password')),
			'u_active'			=> (Yii::t('main', 'Is active')),
			'u_access_level'	=> (Yii::t('main', 'Access level')),
			'u_subscribe'		=> (Yii::t('main', 'Mail subscribe')),
			'u_passneedchange'	=> (Yii::t('main', 'Passwoed needs to be changed')),
			'u_phone'			=> (Yii::t('main', 'phone number')),
			'u_token'			=> (Yii::t('main', 'token')),
			'verify_attempts'	=> (Yii::t('main', 'verify attempts')),
			'smsVerifyCode'		=> (Yii::t('main', 'sms code')),
			'verifyCode'		=> (Yii::t('main', 'verify code')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'u_id'			=> 'HiddenField',
			'u_boss_id'		=> 'DropDownList',
			'u_email'		=> 'TextField',
			'u_login'		=> 'TextField',
			'u_fio'			=> 'TextField',			
			'u_pass'		=> 'PasswordField',
			'u_active'		=> 'CheckBox',
			'u_access_level'=> 'TextField',
			'u_pass_confirm'=> 'PasswordField',
			'u_pass_current'=> 'PasswordField',
			'smsVerifyCode'	=> 'PasswordField',
			'u_subscribe'	=> 'CheckBox',
			'u_passneedchange'=> 'CheckBox',
			'u_phone'		=> 'TextField',	
			'u_token'		=> 'HiddenField',
		);
				
		if ( Yii::app()->user->access_level < 8 ) {
			$fields['u_access_level'] = $fields['u_email'] = $fields['u_active'] = $fields['u_passneedchange'] = $fields['u_boss_id'] = 'HiddenField';
		}
		
		if ( isset($fields[$asked_field]) ) return $fields[$asked_field];
		else return 'TextField';
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('u_id',$this->u_id,true);
		$criteria->compare('u_email',$this->u_email,true);
		$criteria->compare('u_login',$this->u_login,true);
		$criteria->compare('u_fio',$this->u_fio,true);
		$criteria->compare('u_pass',$this->u_pass,true);
		$criteria->compare('u_active',$this->u_active);
		$criteria->compare('u_access_level',$this->u_access_level,true);
		$criteria->compare('u_subscribe',$this->u_subscribe,true);
		$criteria->compare('u_passneedchange',$this->u_passneedchange,true);
		$criteria->compare('u_boss_id',$this->u_boss_id,true);
		$criteria->compare('u_phone',$this->u_phone,true);
		$criteria->compare('u_session',$this->u_session,true);
		$criteria->compare('u_token',$this->u_token,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function check_pass($attribute,$params)
    {
		if ( $this->u_pass && $this->u_pass !== $this->u_pass_confirm ) {
			 $this->addError('u_pass_confirm', Yii::t('main', 'Password not matches'));
		}
    }
	
	public function check_old_pass($attribute,$params)
    {
		if ( !Yii::app()->user->isGuest && Yii::app()->user->access_level >= 8 )
			return true;
		if ( !$this->u_id )
			return true;
		$user = Users::model()->findByPk($this->u_id);
		if ( $user === NULL )
			return false;
		
		$check_pass = crypt($this->u_pass_current,$this->u_pass_current);
		
		
		if ( $check_pass !== $user->u_pass ) {
			 $this->addError('u_pass_current', Yii::t('main', 'Password not matches'));
		}
    }
	
	 public function smsVerifyCode($attribute, $params){
    	$value = $this->$attribute;
    	$sms_code = $this->sms_code;
    	if ( empty($value)) {
    		$this->addError ( $attribute, Yii::t ( 'site', 'Can`t be empty' ) );
    		return false;    		
    	}
    	elseif ( $value!==$sms_code ) {
    		$this->addError ( $attribute, Yii::t ( 'site', 'Enter wrong code' ) );
    		return false;
    	}
    	$this->sms_code = 0;
    	return true;
    }
	
	protected function beforeSave() {
		if($this->scenario == 'check_phone') return true;
		if ( $this->u_pass ) {
			$this->u_pass = crypt($this->u_pass,$this->u_pass);
			$this->u_pass_confirm = crypt($this->u_pass_confirm,$this->u_pass_confirm);
		}
		return true;
	}
}