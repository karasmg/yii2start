<?php

/**
 * This is the model class for table "anketa".
 *
 * The followings are the available columns in table 'anketa':
 * @property string $iid
 * @property string $state
 * @property string $surname
 * @property string $name
 * @property string $lastname
 * @property string $birth_date
 * @property string $sex
 * @property string $passport_seria
 * @property string $passport_number
 * @property string $passport_issued
 * @property string $passport_date
 * @property string $contact_region
 * @property string $contact_city
 * @property string $contact_street
 * @property string $contact_house
 * @property string $contact_flat
 * @property string $contact_phone_home
 * @property string $contact_phone_mobile
 * @property string $contact_email
 * @property string $tdate
 * @property string $ip
 * @property integer $married
 * @property integer $children
 * @property integer $prof
 * @property integer $check_info
 * @property integer $check_rule
 * @property string $guarant1_fio
 * @property string $guarant1_phone_mobile
 * @property integer $guarant1_relationship
 * @property string $guarant2_fio
 * @property string $guarant2_phone_mobile
 * @property integer $guarant2_relationship
 */
class Anketa extends CActiveRecord
{
	public $temp_pics_key = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'anketa';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iid, surname, name, lastname, birth_date, sex, passport_seria, passport_number, passport_issued, passport_date, contact_region, contact_city, contact_street, contact_house, contact_phone_home, contact_phone_mobile, contact_email, married, children, prof, guarant1_fio, guarant1_lastname, guarant1_phone_mobile, guarant1_relationship, guarant2_fio, guarant2_phone_mobile, guarant2_relationship, check_rule', 'required'),
			array('married, children, prof, check_info, check_rule, guarant1_relationship, guarant2_relationship, highlight', 'numerical', 'integerOnly'=>true),
			array('iid', 'length', 'max'=>10),
			array('iid', 'unique'),
			array('iid', 'numerical', 'integerOnly'=>true, 'min'=>1000000000),
			array('iid', 'unsafe', 'on'=>'edit'),
			array('surname, name, lastname, passport_issued, contact_region, contact_city, contact_street, temp_pics_key', 'length', 'max'=>128),
			array('sex, passport_seria, state', 'length', 'max'=>2),
			array('passport_number', 'length', 'max'=>6),
			array('contact_house, contact_flat, ip', 'length', 'max'=>16),
			array('contact_phone_home, contact_phone_mobile', 'length', 'max'=>32),
			array('contact_email', 'length', 'max'=>64),
			array('guarant1_fio, guarant1_phone_mobile, guarant1_phone_mobile2, guarant2_fio, guarant2_phone_mobile, guarant2_phone_mobile2', 'length', 'max'=>255),
			array('birth_date', 'checkUserAge'),
			array('passport_date', 'checkPaasoprtAgeGived'),
			array('check_rule', 'checkUserAgreeterms'),
			array('tdate', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'insert, update'),
			array('ip', 'default', 'value'=>$_SERVER['REMOTE_ADDR'], 'setOnEmpty'=>false,'on'=>'insert, update'),
			array('highlight', 'default', 'value'=>0, 'setOnEmpty'=>false,'on'=>'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iid, state, surname, name, lastname, birth_date, sex, passport_seria, passport_number, passport_issued, passport_date, contact_region, contact_city, contact_street, contact_house, contact_flat, contact_phone_home, contact_phone_mobile, contact_email, tdate, ip, married, children, prof, check_info, check_rule, guarant1_fio, guarant1_lastname, guarant1_phone_mobile, guarant1_relationship, guarant2_fio, guarant2_phone_mobile, guarant2_relationship, highlight', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'pics_data'		=> array(self::HAS_MANY, 'AnketasPics', 'ap_lid'),
			'card_data'		=> array(self::HAS_MANY, 'AnketasCards', 'a_iid'),
		);
	}
	
	public function selectValues() {
		return array(
			'sex'			=> array('DoneVals', array('м'=>(Yii::t('site', 'male')), 'ж'=>(Yii::t('site', 'female')), )),
			'married'		=> array('DoneVals', array('1'=>(Yii::t('site', 'Official marriage')), '2'=>(Yii::t('site', 'Civil marriage')), '3'=>(Yii::t('site', 'Not married')), )),
			'children'		=> array('DoneVals', array('0'=>(Yii::t('site', 'None')), '1'=>'1', '2'=>'2', '3'=>'3', '4'=>(Yii::t('site', 'More than 3')), )),
			'prof'			=> array('DoneVals', array('1'=>(Yii::t('site', 'Student')), '2'=>(Yii::t('site', 'Operating specialty')), '3'=>(Yii::t('site', 'Office staff')), '4'=>(Yii::t('site', 'Self employed')), '5'=>(Yii::t('site', 'Chief')), '6'=>(Yii::t('site', 'Government employee')), '7'=>(Yii::t('site', 'Medical practice')), '8'=>(Yii::t('site', 'Army and navy service')), '9'=>(Yii::t('site', 'Workless')), '10'=>(Yii::t('site', 'Housekeeper')), )),
			'guarant1_relationship'	=> array('DoneVals', array('1'=>(Yii::t('site', 'Father / Mother')), '2'=>(Yii::t('site', 'Brother / Sister')), '3'=>(Yii::t('site', 'Uncle / Aunt')), '4'=>(Yii::t('site', 'Friend')), '5'=>(Yii::t('site', 'Colleague')), )),
			'guarant2_relationship'	=> array('DoneVals', array('1'=>(Yii::t('site', 'Father / Mother')), '2'=>(Yii::t('site', 'Brother / Sister')), '3'=>(Yii::t('site', 'Uncle / Aunt')), '4'=>(Yii::t('site', 'Friend')), '5'=>(Yii::t('site', 'Colleague')), )),
			'state'	=> array('DoneVals', array('0'=>'new', '1'=>'in_progress', '2'=>'edited', '3'=>'active' )),
			//Статус анкеты. 0-новая, 1-в ожидании, 2-отредактирована, 3-активна
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iid'						=> (Yii::t('site', 'Iin')),
			'state'						=> (Yii::t('site', 'State')),
			'surname'					=> (Yii::t('site', 'Surename')),
			'name'						=> (Yii::t('site', 'Name')),
			'lastname'					=> (Yii::t('site', 'Lastname')),
			'birth_date'				=> (Yii::t('site', 'Birth Date')),
			'sex'						=> (Yii::t('site', 'Sex')),
			'passport_seria'			=> (Yii::t('site', 'Passport Seria')),
			'passport_number'			=> (Yii::t('site', 'Passport Number')),
			'passport_issued'			=> (Yii::t('site', 'Passport Issued')),
			'passport_date'				=> (Yii::t('site', 'Passport Date')),
			'contact_region'			=> (Yii::t('site', 'Post region')),
			'contact_city'				=> (Yii::t('site', 'city')),
			'contact_street'			=> (Yii::t('site', 'Street')),
			'contact_house'				=> (Yii::t('site', 'House')),
			'contact_flat'				=> (Yii::t('site', 'Flat')),
			'contact_phone_home'		=> (Yii::t('site', 'Home phone')),
			'contact_phone_mobile'		=> (Yii::t('site', 'Mobile phone')),
			'contact_email'				=> (Yii::t('site', 'E-mail')),
			'tdate'						=> 'Last change date',
			'ip'						=> 'Last change ip',
			'married'					=> (Yii::t('site', 'Family status')),
			'children'					=> (Yii::t('site', 'Childrens')),
			'prof'						=> (Yii::t('site', 'Scope of activity')),
			'check_info'				=> (Yii::t('site', 'Company news subscribe')),
			'check_rule'				=> (Yii::t('site', 'Agree with terms of use')),
			'guarant1_fio'			=> (Yii::t('site', 'Garant 1 fio')),
			'guarant1_phone_mobile'		=> (Yii::t('site', 'Garant 1 mobile number')),
			'guarant1_phone_mobile2'	=> (Yii::t('site', 'Garant 1 mobile number')).' 2',
			'guarant1_relationship'		=> (Yii::t('site', 'Garant 1 relationship')),
			'guarant2_fio'			=> (Yii::t('site', 'Garant 2 fio')),
			'guarant2_phone_mobile'		=> (Yii::t('site', 'Garant 2 mobile number')),
			'guarant2_phone_mobile2'	=> (Yii::t('site', 'Garant 2 mobile number')).' 2',
			'guarant2_relationship'		=> (Yii::t('site', 'Garant 2 relationship')),
			'temp_pics_key'				=> (Yii::t('main', 'Outer key')),
			'highlight'					=> 'Highlitgh line',
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'iid'						=> 'TextField',
			'state'						=> 'DisabledField',
			'surname'					=> 'TextField',
			'name'						=> 'TextField',
			'lastname'					=> 'TextField',
			'birth_date'				=> 'DateField',
			'sex'						=> 'DropDownList',
			'passport_seria'			=> 'TextField',
			'passport_number'			=> 'TextField',
			'passport_issued'			=> 'TextField',
			'passport_date'				=> 'DateField',
			'contact_region'			=> 'TextField',
			'contact_city'				=> 'TextField',
			'contact_street'			=> 'TextField',
			'contact_house'				=> 'TextField',
			'contact_flat'				=> 'TextField',
			'contact_phone_home'		=> 'TextField',
			'contact_phone_mobile'		=> 'TextField',
			'contact_email'				=> 'TextField',
			'tdate'						=> 'HiddenField',
			'ip'						=> 'HiddenField',
			'married'					=> 'DropDownList',
			'children'					=> 'DropDownList',
			'prof'						=> 'DropDownList',
			'check_info'				=> 'CheckBox',
			'check_rule'				=> 'CheckBox',
			'guarant1_fio'				=> 'TextField',
			'guarant1_phone_mobile'		=> 'TextField',
			'guarant1_phone_mobile2'	=> 'TextField',
			'guarant1_relationship'		=> 'DropDownList',
			'guarant2_fio'				=> 'TextField',
			'guarant2_phone_mobile'		=> 'TextField',
			'guarant2_phone_mobile2'	=> 'TextField',
			'guarant2_relationship'		=> 'DropDownList',
			'temp_pics_key'				=> 'HiddenField',
			'highlight'					=> 'HiddenField',
		);
				
		if ( $this->scenario != 'insert' ) {
			$fields['iid'] = 'DisabledText';
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

		$criteria->compare('iid',$this->iid,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('birth_date',$this->birth_date,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('passport_seria',$this->passport_seria,true);
		$criteria->compare('passport_number',$this->passport_number,true);
		$criteria->compare('passport_issued',$this->passport_issued,true);
		$criteria->compare('passport_date',$this->passport_date,true);
		$criteria->compare('contact_region',$this->contact_region,true);
		$criteria->compare('contact_city',$this->contact_city,true);
		$criteria->compare('contact_street',$this->contact_street,true);
		$criteria->compare('contact_house',$this->contact_house,true);
		$criteria->compare('contact_flat',$this->contact_flat,true);
		$criteria->compare('contact_phone_home',$this->contact_phone_home,true);
		$criteria->compare('contact_phone_mobile',$this->contact_phone_mobile,true);
		$criteria->compare('contact_email',$this->contact_email,true);
		$criteria->compare('tdate',$this->tdate,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('married',$this->married);
		$criteria->compare('children',$this->children);
		$criteria->compare('prof',$this->prof);
		$criteria->compare('check_info',$this->check_info);
		$criteria->compare('check_rule',$this->check_rule);
		$criteria->compare('guarant1_fio',$this->guarant1_fio,true);
		$criteria->compare('guarant1_phone_mobile',$this->guarant1_phone_mobile,true);
		$criteria->compare('guarant1_phone_mobile2',$this->guarant1_phone_mobile2,true);
		$criteria->compare('guarant1_relationship',$this->guarant1_relationship);
		$criteria->compare('guarant2_fio',$this->guarant2_fio,true);
		$criteria->compare('guarant2_phone_mobile',$this->guarant2_phone_mobile,true);
		$criteria->compare('guarant2_phone_mobile2',$this->guarant2_phone_mobile2,true);
		$criteria->compare('guarant2_relationship',$this->guarant2_relationship);
		$criteria->compare('highlight',$this->highlight,true);		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array('ESaveRelatedBehavior' => array(
			'class' => 'application.components.ESaveRelatedBehavior')
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Anketa the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function afterSave() {
		if ( Yii::app()->params['applicationWorkType'] != 'console' ) {
			$previous_erros = AnketasErrorfields::model()->findAll('iin=:iin', array('iin'=>(int)$this->iid));
			foreach ( $previous_erros as $previous_error ) {
				$fld_key = $previous_error->field_name;
				if ( !empty($this->attributes[$fld_key]) && $this->attributes[$fld_key] != $previous_error->curr_val ) {
					$previous_error->delete();
				}
			}
		}
	}
	
	protected function afterFind() {
		if ( Yii::app()->params['applicationWorkType'] != 'console' ) {
			if ( $this->state != 2 ) return true;
			$previous_erros = AnketasErrorfields::model()->findAll('iin=:iin', array('iin'=>(int)$this->iid));
			$labels = $this->attributeLabels();
			foreach ( $previous_erros as $previous_error ) {
				$this->addError($previous_error->field_name, '"'.$labels[$previous_error->field_name].'" '.Yii::t('main', 'Field should be corrected'));
			}
		}
	}
	
	public function getRealdata() {
		$selectVals = $this->selectValues();
		$response = $this->attributes;
		foreach ( $response as $key=>$val ) {
			if ( empty($selectVals[$key]) ) continue;
			if ( is_array($selectVals[$key]) && $selectVals[$key][0] == 'DoneVals' && !empty($selectVals[$key][1][$val]) ) {
				$response[$key] = $selectVals[$key][1][$val];
			}
		}
		return $response;
	}
	
	protected function beforeSave() {
		if ( $this->state == 3 && Yii::app()->params['applicationWorkType'] != 'console' ) {
			$previousData = Anketa::model()->findByPk($this->iid);
			foreach ( $this->attributes as $key=>$val ) {
				if ( in_array($key, array('highlight', 'state', 'tdate')) )
					continue;
				if ( $this->attributes[$key] != $previousData->attributes[$key] ) {
					$this->state = 2;
					break;
				}
			}
		}
		return parent::beforeSave();
	}
	
	public function checkUserAge($attribute, $params)
	{
		$now = time();
		$birth = strtotime($this->birth_date);
		$diff = ($now - $birth)/60/60/24/365;
		if ( $diff >= 20 ) {
			return true;
		}
		$this->addError('birth_date', Yii::t('main', 'Should be older then 20') );
		return false;
	}
	
	public function checkPaasoprtAgeGived($attribute, $params)
	{
		$birth = strtotime($this->birth_date);
		$gived = strtotime($this->passport_date);	
		$diff = ($gived - $birth)/60/60/24/365;
		if ( $diff >= 16 ) {
			return true;
		}
		$this->addError('passport_date', Yii::t('main', 'Passport cannot be given earlier than 16') );
		return false;
	}
	
	public function checkUserAgreeterms($attribute, $params)
	{
		if ( (int)$this->check_rule > 0 ) 
			return true;
		$this->addError('check_rule', Yii::t('main', 'You should agree with terms of use') );
		return false;
	}
}
