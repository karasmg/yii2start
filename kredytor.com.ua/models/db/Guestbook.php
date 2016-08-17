<?php

/**
 * This is the model class for table "guestbook".
 *
 * The followings are the available columns in table 'guestbook':
 * @property string $g_id
 * @property string $g_lang
 * @property string $g_uid
 * @property string $g_lid
 * @property string $g_active
 * @property string $g_date
 * @property string $g_name
 * @property string $g_email
 * @property string $g_phone
 * @property string $g_text
 * @property string $g_answer
 * @property string $g_foto
 */
class Guestbook extends CActiveRecord
{
	public $_ipCount = '';
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'guestbook';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('g_lang, g_name, g_phone, g_text', 'required'),
			array('g_lang', 'length', 'max'=>2),
			array('g_uid, g_lid', 'length', 'max'=>11),
			array('g_active, g_plus', 'length', 'max'=>1),
			array('g_name, g_email', 'length', 'max'=>150),
			array('g_lo_perenos', 'length', 'max'=>500),
			array('g_foto', 'length', 'max'=>400),					
			array('g_user_ip', 'length', 'max'=>15),
			array('g_phone', 'length', 'max'=>25),
			array('g_answer', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('g_id, g_lang, g_uid, g_lid, g_active, g_date, g_name, g_email, g_phone, g_text, g_answer, g_user_ip, g_plus, g_lo_perenos, g_foto', 'safe', 'on'=>'search'),
			array('g_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>true,'on'=>'insert'),
			//array('g_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>true,'on'=>'update'),
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
			'g_uid'	=> array(self::BELONGS_TO, 'Users', 'u_id'),
			'g_lid'	=> array(self::BELONGS_TO, 'Lombard', 'l_id'),
		);
	}
	
	public function selectValues() {
		return array(
			'g_lid'	=> array(self::BELONGS_TO, 'Lombard', 'l_id', 'LombardLang', 'll_adress', 'll_lang'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'g_id'		=> (Yii::t('main', 'Row Id')),
			'g_lang'	=> (Yii::t('main', 'Languege identify')),
			'g_uid'		=> (Yii::t('main', 'Outer key')),
			'g_lid'		=> (Yii::t('main', 'Lombard address')),
			'g_active'	=> (Yii::t('main', 'Is active')),
			'g_date'	=> (Yii::t('main', 'Question date')),
			'g_name'	=> (Yii::t('main', 'User Name')),
			'g_email'	=> (Yii::t('main', 'User Email')),
			'g_phone'	=> (Yii::t('main', 'User Phone')),
			'g_text'	=> (Yii::t('main', 'Question')),
			'g_answer'	=> (Yii::t('main', 'Answer')),
			'g_user_ip'	=> (Yii::t('main', 'Ip adress')).$this->_ipCount,
			'g_plus'	=> (Yii::t('main', 'Plus')),
			'g_lo_perenos'	=> (Yii::t('main', 'Perenos')),	
			'g_foto'	=> 'Foto',
		);
	}
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'g_id'		=> 'HiddenField',
			'g_lang'	=> 'HiddenField',
			'g_uid'		=> 'HiddenField',
			'g_lid'		=> 'DropDownList',
			'g_active'	=> 'CheckBox',
			'g_date'	=> 'DateField',
			'g_name'	=> 'TextField',
			'g_email'	=> 'TextField',
			'g_phone'	=> 'TextField',
			'g_text'	=> 'TextArea',
			'g_answer'	=> 'TextArea',
			'g_user_ip'	=> 'TextField',
			'g_plus'	=> 'HiddenField',
			'g_lo_perenos'=> 'HiddenField',
			'g_foto'	=> 'FileField',
		);
		
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

		$criteria->compare('g_id',$this->g_id,true);
		$criteria->compare('g_lang',$this->g_lang,true);
		$criteria->compare('g_uid',$this->g_uid,true);
		$criteria->compare('g_lid',$this->g_lid,true);
		$criteria->compare('g_active',$this->g_active,true);
		$criteria->compare('g_date',$this->g_date,true);
		$criteria->compare('g_name',$this->g_name,true);
		$criteria->compare('g_email',$this->g_email,true);
		$criteria->compare('g_phone',$this->g_phone,true);
		$criteria->compare('g_text',$this->g_text,true);
		$criteria->compare('g_answer',$this->g_answer,true);
		$criteria->compare('g_user_ip',$this->g_user_ip,true);
		$criteria->compare('g_plus',$this->g_plus,true);
		$criteria->compare('g_lo_perenos',$this->g_lo_perenos,true);
		$criteria->compare('g_foto',$this->g_foto,true);
				
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Guestbook the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
