<?php

/**
 * This is the model class for table "anketa".
 *
 * The followings are the available columns in table 'anketa':
 * @property integer $id
 * @property string $iid
 * @property integer $credit_for
 * @property string $state
 * @property string $surname
 * @property string $name
 * @property string $lastname
 * @property string $prev_fio
 * @property string $birth_date
 * @property string $sex
 * @property string $cityzen
 * @property string $passport_seria
 * @property string $passport_number
 * @property string $passport_issued
 * @property string $passport_date
 * @property string $contact_region
 * @property string $contact_area
 * @property string $contact_city
 * @property string $contact_street
 * @property string $contact_house
 * @property string $contact_corp
 * @property string $contact_flat
 * @property integer $contact_livetime
 * @property string $live_region
 * @property string $live_area
 * @property string $live_city
 * @property string $live_street
 * @property string $live_house
 * @property string $live_corp
 * @property string $live_flat
 * @property integer $live_livetime
 * @property integer $live_status
 * @property string $contact_phone_home
 * @property string $new_phone
 * @property string $contact_phone_mobile
 * @property string $contact_phone_mobile2
 * @property string $contact_email
 * @property string $tdate
 * @property string $ip
 * @property integer $married
 * @property integer $children
 * @property integer $prof
 * @property integer $job_type
 * @property integer $job_shpere
 * @property string $job_orgname
 * @property string $job_position
 * @property string $job_addr
 * @property string $job_phone
 * @property string $job_bossfio
 * @property string $job_bossphone
 * @property integer $job_experiencethis
 * @property integer $job_experiencetotal
 * @property string $job_orgtype
 * @property string $job_flpname
 * @property string $job_primary_income
 * @property string $job_secondary_income
 * @property integer $check_info
 * @property integer $check_rule
 * @property string $guarant1_fio
 * @property string $guarant1_phone_mobile
 * @property string $guarant1_phone_mobile2
 * @property integer $guarant1_relationship
 * @property string $guarant2_fio
 * @property string $guarant2_phone_mobile
 * @property string $guarant2_phone_mobile2
 * @property integer $guarant2_relationship
 * @property string $highlight
 * @property string $cassier_comment
 * @property string $pred_refusal
 * @property string $expert_comment
 * @property string $user_lo
 * @property float $buffer_user
 * @property int $site_userId 
 */
class Anketa extends CActiveRecord
{
	public $temp_pics_key	= 0;
	public $credit_summ		= 0;
	public $new_phone;
	
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
			array('iid, surname, name, lastname, birth_date, sex, passport_seria, passport_number, passport_issued, passport_date, contact_region, contact_city, contact_street, contact_house, live_region, live_city, live_street, live_house, live_status, contact_phone_mobile, contact_email, married, children, prof, job_primary_income, guarant1_fio, guarant1_phone_mobile, guarant1_relationship, guarant2_fio, guarant2_phone_mobile, guarant2_relationship', 'required'),
			array('credit_for, srok, contact_livetime, live_livetime, live_status, married, children, prof, job_type, job_shpere, job_experiencethis, job_experiencetotal, check_info, check_rule, guarant1_relationship, guarant2_relationship', 'numerical', 'integerOnly'=>true),
			array('iid', 'unique'),
			array('iid', 'numerical', 'integerOnly'=>true, 'min'=>1000000000),
			array('buffer_user', 'numerical'),
			array('iid', 'unsafe', 'on'=>'edit'),
			array('iid, contact_corp, live_corp, job_primary_income, job_secondary_income', 'length', 'max'=>10),
			array('state, sex, passport_seria', 'length', 'max'=>2),
			array('user_lo', 'length', 'max'=>4),			
			array('surname, name, lastname, passport_issued, contact_region, contact_city, contact_street, live_region, live_city, live_street', 'length', 'max'=>128),
			array('prev_fio, job_orgtype', 'length', 'max'=>200),
			array('cityzen, job_orgname, job_position, job_flpname', 'length', 'max'=>100),
			array('passport_number', 'length', 'max'=>6),
			array('passport_number', 'length', 'min'=>6),
			array('passport_number, site_userId', 'numerical', 'integerOnly'=>true),
			array('contact_area, live_area', 'length', 'max'=>50),
			array('contact_house, contact_flat, live_house, live_flat, ip', 'length', 'max'=>16),
			array('contact_phone_home, contact_phone_mobile, contact_phone_mobile2, job_phone, job_bossphone', 'length', 'max'=>32),
			array('contact_email', 'length', 'max'=>64),
			array('job_addr, job_bossfio', 'length', 'max'=>250),
			array('guarant1_fio, guarant1_phone_mobile, guarant1_phone_mobile2, guarant2_fio, guarant2_phone_mobile, guarant2_phone_mobile2', 'length', 'max'=>255),
			array('highlight, pred_refusal', 'length', 'max'=>1),
			array('cassier_comment, expert_comment', 'safe'),
			array('birth_date', 'checkUserAge'),
			array('passport_date', 'checkPaasoprtAgeGived'),
			array('check_rule', 'checkUserAgreeterms'),
			array('prof', 'checkProfDependents'),
			array('state', 'nochangesWhileInProgress'),
			array('surname, name, lastname, prev_fio, passport_issued, contact_region, contact_city, contact_street, live_region, live_city, live_street, guarant1_fio, guarant2_fio', 'checkCyrylicTextInput'),
			array('contact_phone_mobile, contact_phone_mobile2, guarant1_phone_mobile, guarant1_phone_mobile2, guarant2_phone_mobile, guarant2_phone_mobile2, job_bossphone', 'checkMobilePhone'),
			array('contact_phone_home, job_phone', 'checkCityPhone'),
			array('tdate', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'insert, update'),
			array('ip', 'default', 'value'=>$_SERVER['REMOTE_ADDR'], 'setOnEmpty'=>false,'on'=>'insert, update'),
			array('highlight', 'default', 'value'=>0, 'setOnEmpty'=>false,'on'=>'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, iid, credit_for, state, surname, name, lastname, prev_fio, birth_date, sex, cityzen, passport_seria, passport_number, passport_issued, passport_date, contact_region, contact_area, contact_city, contact_street, contact_house, contact_corp, contact_flat, contact_livetime, live_region, live_area, live_city, live_street, live_house, live_corp, live_flat, live_livetime, live_status, contact_phone_home, contact_phone_mobile, contact_phone_mobile2, contact_email, tdate, ip, married, children, prof, job_type, job_shpere, job_orgname, job_position, job_addr, job_phone, job_bossfio, job_bossphone, job_experiencethis, job_experiencetotal, job_orgtype, job_flpname, job_primary_income, job_secondary_income, check_info, check_rule, guarant1_fio, guarant1_phone_mobile, guarant1_phone_mobile2, guarant1_relationship, guarant2_fio, guarant2_phone_mobile, guarant2_phone_mobile2, guarant2_relationship, highlight, cassier_comment, pred_refusal, expert_comment, srok, user_lo, buffer_user, site_userId', 'safe', 'on'=>'search'),
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
			'married'		=> array('DoneVals', array(
				'1'=>(Yii::t('site', 'Not married')), 
				'2'=>(Yii::t('site', 'Official marriage')), 
				'3'=>(Yii::t('site', 'Divorced')), 
				'4'=>(Yii::t('site', 'Widower / widow')), 
				'5'=>(Yii::t('site', 'Civil marriage')), )),
			'children'		=> array('DoneVals', array('0'=>(Yii::t('site', 'None')), '1'=>'1', '2'=>'2', '3'=>(Yii::t('site', '3 and more')), )),
			'prof'			=> array('DoneVals', array('1'=>(Yii::t('site', 'Workless')), '2'=>(Yii::t('site', 'Allowance')), '3'=>(Yii::t('site', 'Pensioner')), '4'=>(Yii::t('site', 'Student')), '5'=>(Yii::t('site', 'Working')), )),
			'job_type'		=> array('DoneVals', array('0'	=> ' - ', '1'=>(Yii::t('site', 'Employee')), '2'=>(Yii::t('site', 'FLP')), )),
			'job_shpere'	=> array('DoneVals', array(
				'0'	=> ' - ',
				'1'=>(Yii::t('site', 'IT, Computers, Internet')), 
				'2'=>(Yii::t('site', 'The administration, middle management')), 
				'3'=>(Yii::t('site', 'Accounting, Audit')),  
				'4'=>(Yii::t('site', 'Hotel - Restaurant business, tourism')),  
				'5'=>(Yii::t('site', 'Culture')),  
				'6'=>(Yii::t('site', 'Logistics')),  
				'7'=>(Yii::t('site', 'Medicine')),  
				'8'=>(Yii::t('site', 'Marketing, Advertising')),  
				'9'=>(Yii::t('site', 'Real Estate')),  
				'10'=>(Yii::t('site', 'Education, science')),  
				'11'=>(Yii::t('site', 'Protection, Security')),  
				'12'=>(Yii::t('site', 'Commerce')),  
				'13'=>(Yii::t('site', 'Agriculture, agribusiness')),  
				'14'=>(Yii::t('site', 'Mass media, publishing, printing')),  
				'15'=>(Yii::t('site', 'Insurance')), 
				'16'=>(Yii::t('site', 'Building, architecture')), 
				'17'=>(Yii::t('site', 'Service Industry')), 
				'18'=>(Yii::t('site', 'Finance and Banking')), 
				'19'=>(Yii::t('site', 'Jurisprudence')), 
				'20'=>(Yii::t('site', 'Other areas of activity')), 
			)),
			'job_experiencethis'	=> array('DoneVals', array('0'	=> ' - ', '1'=>Yii::t('site', 'Up to 1 year'), '2'=>Yii::t('site', 'From 1 to 3 years'), '3'=>Yii::t('site', 'Over 3 years') )),
			//'job_experiencetotal'	=> array('DoneVals', array('1'=>Yii::t('site', 'Up to 1 year'), '1'=>Yii::t('site', 'From 1 to 3 years'), '2'=>Yii::t('site', 'Over 3 years') )),		
			'guarant1_relationship'	=> array('DoneVals', array(
				'1'=>(Yii::t('site', 'Husband / Wife')),
				'2'=>(Yii::t('site', 'Civil husband / civil wife')),
				'3'=>(Yii::t('site', 'Son / Daughter')),
				'4'=>(Yii::t('site', 'Father / Mother')),
				'5'=>(Yii::t('site', 'Brother / Sister')),
				'6'=>(Yii::t('site', 'Grandson / Granddaughter')),
				'7'=>(Yii::t('site', 'Kinsman')),				
				'8'=>(Yii::t('site', 'Friend')), 
			)),
			'guarant2_relationship'	=> array('DoneVals', array(
				'1'=>(Yii::t('site', 'Husband / Wife')),
				'2'=>(Yii::t('site', 'Civil husband / civil wife')),
				'3'=>(Yii::t('site', 'Son / Daughter')),
				'4'=>(Yii::t('site', 'Father / Mother')),
				'5'=>(Yii::t('site', 'Brother / Sister')),
				'6'=>(Yii::t('site', 'Grandson / Granddaughter')),
				'7'=>(Yii::t('site', 'Kinsman')),				
				'8'=>(Yii::t('site', 'Friend')), 
			)),
			'state'	=> array('DoneVals', array('0'=>'new', '1'=>'in_progress', '2'=>'edited', '3'=>'active' )),
			//Статус анкеты. 0-новая, 1-в ожидании, 2-отредактирована, 3-активна
			//'credit_for'		=> array('DoneVals', array('1'=>Yii::t('site', 'On the personal needs'), '2'=>Yii::t('site', 'For family'), '3'=>Yii::t('site', 'Other objects') )),
			'contact_livetime'	=> array('DoneVals', array('1'=>Yii::t('site', 'Up to 1 year'), '2'=>Yii::t('site', 'From 1 to 3 years'), '3'=>Yii::t('site', 'Over 3 years') )),
			'live_livetime'		=> array('DoneVals', array('1'=>Yii::t('site', 'Up to 1 year'), '2'=>Yii::t('site', 'From 1 to 3 years'), '3'=>Yii::t('site', 'Over 3 years') )),
			'live_status'		=> array('DoneVals', array('1'=>Yii::t('site', 'Arrend'), '2'=>Yii::t('site', 'The private property'), '3'=>Yii::t('site', 'Hostel'), '4'=>Yii::t('site', 'Living with relatives') )),
			'job_orgtype'		=> array('DoneVals', array(
				'0'	=> ' - ',
				'1' => 'Сельское хозяйство.  Охота. Лесное хозяйство.',
				'2' => 'Рыбоводство.',
				'3' => 'Добывающая промышленность.',
				'4' => 'Перерабатывающая промышленность.',
				'5' => 'Добыча и распределение электричества, газа и воды.',
				'6' => 'Строительство.',
				'7' => 'Торговля, ремонт автомобилей, бытовых приборов и предметов личного использования.',
				'8' => 'Деятельность отелей и ресторанов.',
				'9' => 'Деятельность транспорта и связи.',
				'10' => 'Финансовая деятельность.',
				'11' => 'Операции с недвижимым имуществом, аренда, инжиниринг и предоставление услуг предпринимателям.',
				'12' => 'Государственное управление.',
				'13' => 'Образование.',
				'14' => 'Здравоохранение и предоставление социальной помощи.',
				'15' => 'Предоставление коммунальных и индивидуальных услуг; деятельность в сфере культуры и спорта.',
				'16' => 'Деятельность домашних хозяйств.',
				'17' => 'Деятельность экстерриториальных организаций.',
			)),
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'						=> 'id',
			'iid'						=> (Yii::t('site', 'Iin')),
			'credit_for'				=> (Yii::t('main', 'Credit For')),
			'srok'						=> 'Срок кредита',
			'state'						=> (Yii::t('site', 'State')),
			'surname'					=> (Yii::t('site', 'Surename')),
			'name'						=> (Yii::t('site', 'Name')),
			'lastname'					=> (Yii::t('site', 'Lastname')),
			'prev_fio'					=> 'Прежнее фио <small>(В случае изменения)</small>',
			'birth_date'				=> (Yii::t('site', 'Birth Date')),
			'sex'						=> (Yii::t('site', 'Sex')),
			'cityzen'					=> 'Гражданство',
			'passport_seria'			=> (Yii::t('site', 'Passport Seria')),
			'passport_number'			=> (Yii::t('site', 'Passport Number')),
			'passport_issued'			=> (Yii::t('site', 'Passport Issued')),
			'passport_date'				=> (Yii::t('site', 'Passport Date')),
			'contact_region'			=> (Yii::t('site', 'Post region')),
			'contact_area'				=> 'Район',
			'contact_city'				=> (Yii::t('site', 'city')),
			'contact_street'			=> (Yii::t('site', 'Street')),
			'contact_house'				=> (Yii::t('site', 'House')),
			'contact_corp'				=> 'Корпус',
			'contact_flat'				=> (Yii::t('site', 'Flat')),
			'contact_livetime'			=> 'Длительность регистрации',
			'live_region'				=> (Yii::t('site', 'Post region')),
			'live_area'					=> 'Район',
			'live_city'					=> 'Город',
			'live_street'				=> 'Улица',
			'live_house'				=> 'Дом',
			'live_corp'					=> 'Корпус',
			'live_flat'					=> 'Квартира',
			'live_livetime'				=> 'Длительность проживания',
			'live_status'				=> 'Статус жилья',
			'contact_phone_home'		=> (Yii::t('site', 'Home phone')),
			'contact_phone_mobile'		=> (Yii::t('site', 'Mobile phone')),
			'contact_phone_mobile2'		=> (Yii::t('site', 'Additional mob phone')),			
			'new_phone'					=> (Yii::t('site', 'new phone')),
			'contact_email'				=> (Yii::t('site', 'E-mail')),
			'tdate'						=> 'Last change date',
			'ip'						=> 'Last change ip',
			'married'					=> (Yii::t('site', 'Family status')),
			'children'					=> (Yii::t('site', 'Childrens')),
			'prof'						=> (Yii::t('site', 'Scope of activity')),
			'job_type'					=> 'Тип работника',
			'job_shpere'				=> 'Сфера деятельности',
			'job_orgname'				=> 'Название организации',
			'job_position'				=> 'Должность',
			'job_addr'					=> 'Адрес',
			'job_phone'					=> 'Рабочий стационарный телефон',
			'job_bossfio'				=> 'ФИО руководителя',
			'job_bossphone'				=> 'Моб. тел руководителя',
			'job_experiencethis'		=> 'Стаж на последнем месте работы',
			'job_experiencetotal'		=> 'Стаж общий',
			'job_orgtype'				=> 'Вид деятельности организации',
			'job_flpname'				=> 'Наименование ФЛП',
			'job_primary_income'		=> 'Основной доход',
			'job_secondary_income'		=> 'Дополнительный доход',
			'check_info'				=> (Yii::t('site', 'Company news subscribe')),
			'check_rule'				=> (Yii::t('site', 'Agree with terms of use')),
			'guarant1_fio'				=> (Yii::t('site', 'Garant 1 fio')),
			'guarant1_phone_mobile'		=> (Yii::t('site', 'Garant 1 mobile number')),
			'guarant1_phone_mobile2'	=> (Yii::t('site', 'Garant 1 mobile number')).' 2',
			'guarant1_relationship'		=> (Yii::t('site', 'Garant 1 relationship')),
			'guarant2_fio'				=> (Yii::t('site', 'Garant 2 fio')),
			'guarant2_phone_mobile'		=> (Yii::t('site', 'Garant 2 mobile number')),
			'guarant2_phone_mobile2'	=> (Yii::t('site', 'Garant 2 mobile number')).' 2',
			'guarant2_relationship'		=> (Yii::t('site', 'Garant 2 relationship')),
			'highlight'					=> 'Подсветить в списке',
			'cassier_comment'			=> 'Комментарий кассира',
			'pred_refusal'				=> 'Предварительный отказ',
			'expert_comment'			=> 'Комментарий экспрета',
			'temp_pics_key'				=> (Yii::t('main', 'Outer key')),
			'user_lo'					=> 'Ло пользователя',
			'buffer_user'				=> 'Остаток на счету',
			'site_userId'				=> 'Id пользователя сайта',
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'id'						=> 'HiddenField',
			'iid'						=> 'TextField',
			'credit_for'				=> 'HiddenField',
			'srok'						=> 'HiddenField',
			'state'						=> 'DisabledField',
			'surname'					=> 'TextField',
			'name'						=> 'TextField',
			'lastname'					=> 'TextField',
			'prev_fio'					=> 'TextField',
			'birth_date'				=> 'DateYearsField',
			'sex'						=> 'DropDownList',
			'cityzen'					=> 'TextField',
			'passport_seria'			=> 'TextField',
			'passport_number'			=> 'TextField',
			'passport_issued'			=> 'TextField',
			'passport_date'				=> 'DateYearsField',
			'contact_region'			=> 'TextField',
			'contact_area'				=> 'TextField',
			'contact_city'				=> 'TextField',
			'contact_street'			=> 'TextField',
			'contact_house'				=> 'TextField',
			'contact_corp'				=> 'TextField',
			'contact_flat'				=> 'TextField',
			'contact_livetime'			=> 'DropDownList',
			'live_region'				=> 'TextField',
			'live_area'					=> 'TextField',
			'live_city'					=> 'TextField',
			'live_street'				=> 'TextField',
			'live_house'				=> 'TextField',
			'live_corp'					=> 'TextField',
			'live_flat'					=> 'TextField',
			'live_livetime'				=> 'DropDownList',
			'live_status'				=> 'DropDownList',
			'new_phone'					=> 'TextField',
			'contact_phone_home'		=> 'TextField',
			'contact_phone_mobile'		=> 'TextField',
			'contact_phone_mobile2'		=> 'TextField',
			'contact_email'				=> 'TextField',
			'tdate'						=> 'HiddenField',
			'ip'						=> 'HiddenField',
			'married'					=> 'DropDownList',
			'children'					=> 'DropDownList',
			'prof'						=> 'DropDownList',
			'job_type'					=> 'DropDownList',
			'job_shpere'				=> 'DropDownList',
			'job_orgname'				=> 'TextField',
			'job_position'				=> 'TextField',
			'job_addr'					=> 'TextField',
			'job_phone'					=> 'TextField',
			'job_bossfio'				=> 'TextField',
			'job_bossphone'				=> 'TextField',
			'job_experiencethis'		=> 'DropDownList',
			'job_experiencetotal'		=> 'TextField',
			'job_orgtype'				=> 'DropDownList',
			'job_flpname'				=> 'TextField',
			'job_primary_income'		=> 'TextField',
			'job_secondary_income'		=> 'TextField',
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
			'highlight'					=> 'HiddenField',
			'cassier_comment'			=> 'TextAreaSimpleEditor',
			'pred_refusal'				=> 'CheckBox',
			'expert_comment'			=> 'HiddenField',
			'temp_pics_key'				=> 'HiddenField',
			'user_lo'					=> 'HiddenField',
			'buffer_user'				=> 'HiddenField',
			'site_userId'				=> 'HiddenField',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('iid',$this->iid,true);
		$criteria->compare('credit_for',$this->credit_for);
		$criteria->compare('srok',$this->srok);		
		$criteria->compare('state',$this->state,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('prev_fio',$this->prev_fio,true);
		$criteria->compare('birth_date',$this->birth_date,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('cityzen',$this->cityzen,true);
		$criteria->compare('passport_seria',$this->passport_seria,true);
		$criteria->compare('passport_number',$this->passport_number,true);
		$criteria->compare('passport_issued',$this->passport_issued,true);
		$criteria->compare('passport_date',$this->passport_date,true);
		$criteria->compare('contact_region',$this->contact_region,true);
		$criteria->compare('contact_area',$this->contact_area,true);
		$criteria->compare('contact_city',$this->contact_city,true);
		$criteria->compare('contact_street',$this->contact_street,true);
		$criteria->compare('contact_house',$this->contact_house,true);
		$criteria->compare('contact_corp',$this->contact_corp,true);
		$criteria->compare('contact_flat',$this->contact_flat,true);
		$criteria->compare('contact_livetime',$this->contact_livetime);
		$criteria->compare('live_region',$this->live_region,true);
		$criteria->compare('live_area',$this->live_area,true);
		$criteria->compare('live_city',$this->live_city,true);
		$criteria->compare('live_street',$this->live_street,true);
		$criteria->compare('live_house',$this->live_house,true);
		$criteria->compare('live_corp',$this->live_corp,true);
		$criteria->compare('live_flat',$this->live_flat,true);
		$criteria->compare('live_livetime',$this->live_livetime);
		$criteria->compare('live_status',$this->live_status);
		$criteria->compare('contact_phone_home',$this->contact_phone_home,true);
		$criteria->compare('contact_phone_mobile',$this->contact_phone_mobile,true);
		$criteria->compare('contact_phone_mobile2',$this->contact_phone_mobile2,true);
		$criteria->compare('contact_email',$this->contact_email,true);
		$criteria->compare('tdate',$this->tdate,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('married',$this->married);
		$criteria->compare('children',$this->children);
		$criteria->compare('prof',$this->prof);
		$criteria->compare('job_type',$this->job_type);
		$criteria->compare('job_shpere',$this->job_shpere);
		$criteria->compare('job_orgname',$this->job_orgname,true);
		$criteria->compare('job_position',$this->job_position,true);
		$criteria->compare('job_addr',$this->job_addr,true);
		$criteria->compare('job_phone',$this->job_phone,true);
		$criteria->compare('job_bossfio',$this->job_bossfio,true);
		$criteria->compare('job_bossphone',$this->job_bossphone,true);
		$criteria->compare('job_experiencethis',$this->job_experiencethis);
		$criteria->compare('job_experiencetotal',$this->job_experiencetotal);
		$criteria->compare('job_orgtype',$this->job_orgtype,true);
		$criteria->compare('job_flpname',$this->job_flpname,true);
		$criteria->compare('job_primary_income',$this->job_primary_income,true);
		$criteria->compare('job_secondary_income',$this->job_secondary_income,true);
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
		$criteria->compare('cassier_comment',$this->cassier_comment,true);
		$criteria->compare('pred_refusal',$this->pred_refusal,true);
		$criteria->compare('expert_comment',$this->expert_comment,true);
		$criteria->compare('user_lo',$this->user_lo,true);	
		$criteria->compare('buffer_user',$this->buffer_user,true);	
		$criteria->compare('site_userId',$this->site_userId,true);	
		
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
		$this->setCorrectedErrors();
	}
	
	public function setCorrectedErrors() {
		$errors = false;
		if ( Yii::app()->params['applicationWorkType'] != 'console' ) {
			if ( $this->state != 2 ) return true;
			$previous_erros = AnketasErrorfields::model()->findAll('iin=:iin', array('iin'=>(int)$this->iid));
			$labels = $this->attributeLabels();
			foreach ( $previous_erros as $previous_error ) {
				$errors = true;
				$this->addError($previous_error->field_name, '"'.$labels[$previous_error->field_name].'" '.Yii::t('main', 'Field should be corrected'));
			}
		}
		return $errors;
	}
	
	public function getRealdata() {
		$selectVals = $this->selectValues();
		$response = $this->attributes;
		foreach ( $response as $key=>$val ) {
			if ( empty($selectVals[$key]) || in_array($key, array('state')) ) continue;
			if ( is_array($selectVals[$key]) && $selectVals[$key][0] == 'DoneVals' && !empty($selectVals[$key][1][$val]) ) {
				$response[$key] = $selectVals[$key][1][$val];
			}
		}
		return $response;
	}
	
	protected function beforeSave() {
		if ( $this->state == 3 && Yii::app()->params['applicationWorkType'] != 'console' ) {
			$previousData = Anketa::model()->findByPk($this->id);
			foreach ( $this->attributes as $key=>$val ) {
				if ( in_array($key, array('highlight', 'state', 'tdate')) )
					continue;
				if ( $this->attributes[$key] != $previousData->attributes[$key] ) {
					$this->state = 2;
					break;
				}
			}
		}
		$this->birth_date = date('Y-m-d', strtotime($this->birth_date));
		$this->passport_date = date('Y-m-d', strtotime($this->passport_date));
		return parent::beforeSave();
	}
	
	public function checkUserAge($attribute, $params)
	{
		$now = time();
		$birth = strtotime($this->birth_date);
		$diff = ($now - $birth)/60/60/24/365;
		if ( $diff >= 18 && $diff <= 200 ) {
			
			return true;
		}
		$this->addError('birth_date', Yii::t('main', 'Should be older then 18 and yanger then 200') );
		return false;
	}
	
	public function nochangesWhileInProgress($attribute, $params) {
		if ( Yii::app()->params['applicationWorkType'] == 'console' )
			return true;
		if ( $this->state == 1 ) {
			$this->addError('state', Yii::t('main', 'Not allowed any changes while anket in progress') );
			return false;
		}
		
		$zayavState = Yii::app()->db->createCommand('SELECT state FROM zayavka WHERE iid='.$this->iid.' ORDER BY id DESC')->queryRow();
        if( !empty($zayavState) && $zayavState['state'] == 5 ) {
            $this->addError('state', Yii::t('main', 'Not allowed any changes while credit in progress') );
			return false;
        }
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
	
	public function checkProfDependents($attribute, $params)
	{
		if ( $this->prof != 5 ) {
			$this->job_type		= 0;
			$this->job_shpere	= 0;
			$this->job_orgname	= '';
			$this->job_position	= '';
			$this->job_addr		= '';
			$this->job_phone	= '';
			$this->job_bossfio	= '';
			$this->job_bossphone= '';
			$this->job_experiencethis = 0;
			$this->job_experiencetotal= '';
			
			$this->job_orgtype	= 0;
			$this->job_flpname	= '';
			return true;
		}
		if ( !$this->job_type ) {
			$this->addError('job_type', Yii::t('main', 'You need to choose the type') );
			return false;
		}
		if ( $this->job_type == 1 ) {
			$fail = true;
			$this->job_orgtype	= 0;
			$this->job_flpname	= '';
			if ( !$this->job_shpere ) {
				$this->addError('job_shpere', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_orgname ) {
				$this->addError('job_orgname', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_position ) {
				$this->addError('job_position', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_addr ) {
				$this->addError('job_addr', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_phone ) {
				$this->addError('job_phone', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_bossfio ) {
				$this->addError('job_bossfio', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_bossphone ) {
				$this->addError('job_bossphone', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_experiencethis ) {
				$this->addError('job_experiencethis', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_experiencetotal ) {
				$this->addError('job_experiencetotal', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			return $fail;
		} elseif ( $this->job_type == 2 ) {
			$fail = true;
			$this->job_position = '';
			$this->job_shpere	= 0;
			$this->job_bossfio	= '';
			$this->job_bossphone= '';
			if ( !$this->job_orgtype ) {
				$this->addError('job_orgtype', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_flpname ) {
				$this->addError('job_flpname', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_addr ) {
				$this->addError('job_addr', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_phone ) {
				$this->addError('job_phone', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_experiencethis ) {
				$this->addError('job_experiencethis', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			if ( !$this->job_experiencetotal ) {
				$this->addError('job_experiencetotal', Yii::t('main', 'You need to enter value') );
				$fail = false;
			}
			return $fail;
		}
	}	
	
	public function checkCyrylicTextInput($attribute, $params) {
		$value = $this->attributes[$attribute];
		if ( !$value )
			return true;		
		$nonCerillic = array_filter( array_map( array($this, 'passOnlyNonCyrylic'), array_map('ord', $this->mb_str_split($value)) ) );
		if ( empty($nonCerillic) )
			return true;
		$this->addError($attribute, Yii::t('main', 'Only cyrillic symbols allowed') );
		return false;		
	}
	
	private function mb_str_split($string,$string_length=1,$charset='utf-8') {
		if(mb_strlen($string,$charset)>$string_length || !$string_length) {
			do {
				$c = mb_strlen($string,$charset);
				$parts[] = mb_substr($string,0,$string_length,$charset);
				$string = mb_substr($string,$string_length,$c-$string_length,$charset);
			} while( !empty($string) );
		} else {
			$parts = array($string);
		}
		return $parts;
	}
	
	private function passOnlyNonCyrylic($val) {
		if ( 
			($val < 192 || $val > 255) &&	//Символы кириллицы
			($val < 48 || $val > 57) &&		//Символы цифры
			$val != 175 &&					//Ї
			$val != 191 &&					//ї
			$val != 186 &&					//є
			$val != 170 &&					//Є
			$val != 165 &&					//Г
			$val != 184 &&					//ё
			$val != 168 &&					//Ё
			($val < 178 || $val > 180) &&	//І і г
			$val != 145 &&					//'
			$val != 146 &&					//'
			$val != 32 &&					//Пробел
			$val != 39 &&					//Одинарная кавычка
			$val != 96 &&					//Апостроф на латинской раскалдке
			($val < 44 || $val > 46)		//Запятая, точка, тире
			) {
				return chr($val);
			}
			
		return false;
	}
	
	public function checkMobilePhone($attribute, $params) {
		$value = $this->attributes[$attribute];
		if ( !trim($value) ) return true;
		$phone = preg_replace('/\D/', '', $value);
		if ( !preg_match('/\d{9,12}/', $phone) ) {
			$this->addError($attribute, Yii::t('main', 'Should be valid phone') );
			return false;
		}
		$phone = str_pad($phone, 13,  '+380', STR_PAD_LEFT);
		if ( !preg_match('/\+380\d{9}/', $phone) ) {
			$this->addError($attribute, Yii::t('main', 'Should be valid phone') );
			return false;
		}
		$this->{$attribute} = $phone;
		return true;
	}
	
	public function checkCityPhone($attribute, $params) {
		$value = $this->attributes[$attribute];
		if ( !trim($value) ) return true;
		$phone = preg_replace('/\D/', '', $value);
		if ( !preg_match('/\d{7,10}/', $phone) ) {
			$this->addError($attribute, Yii::t('main', 'Should be valid phone') );
			return false;
		}
		$phone = str_pad($phone, 10,  '0', STR_PAD_LEFT);
		if ( !preg_match('/0\d{9}/', $phone) ) {
			$this->addError($attribute, Yii::t('main', 'Should be valid phone') );
			return false;
		}
		$this->{$attribute} = $phone;
		return true;
	}
}
