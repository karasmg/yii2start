<?php

/**
 * This is the model class for table "invoices".
 *
 * The followings are the available columns in table 'invoices':
 * @property string $i_id
 * @property string $i_uid
 * @property string $i_date_create
 * @property string $i_pay_day 
 * @property string $i_date_prepaid
 * @property string $i_date_paid
 * @property double $i_summ
 * @property string $i_status
 * @property string $i_paysys
 * @property string $i_apprcode
 * @property string $i_placement
 * @property double $i_percent
 * @property double $i_peny
 * @property double $i_body
 * @property double $i_buffer
 * @property integer $i_days_prol
 * @property double $i_buffer_user
 * @property double $i_discount 
 * @property integer $i_dognumb
 * @property double $i_commision
 */
class Invoices extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('i_uid, i_date_create, i_pay_day, i_summ, i_status, i_paysys, i_percent, i_peny, i_body, i_buffer, i_days_prol, i_buffer_user', 'required'),
			array('i_days_prol', 'numerical', 'integerOnly'=>true),
			array('i_summ, i_percent, i_peny, i_body, i_buffer, i_buffer_user, i_discount, i_commision', 'numerical'),
			array('i_uid, i_dognumb', 'length', 'max'=>11),
			array('i_status', 'length', 'max'=>7),
			array('i_paysys, i_placement', 'length', 'max'=>20),
			array('i_apprcode', 'length', 'max'=>40),
			array('i_date_prepaid, i_date_paid', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('i_id, i_uid, i_date_create, i_pay_day, i_date_prepaid, i_date_paid, i_summ, i_status, i_paysys, i_apprcode, i_placement, i_percent, i_peny, i_body, i_buffer, i_days_prol, i_buffer_user, i_discount, i_dognumb, i_commision', 'safe', 'on'=>'search'),
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

	public function selectValues()
	{
		return array(
			'i_status' => array('DoneVals', array('prepaid'=>(Yii::t('site', 'prepaid')), 'paid'=>(Yii::t('site', 'paid')), 'cancel'=>(Yii::t('site', 'cancel')), 'new'=>(Yii::t('site', 'new')),)),
			'i_paysys' => array('DoneVals', array(
				'terminal_city24'	=> (Yii::t('site', 'terminal_city24')), 
				'Platon'			=> (Yii::t('site', 'Platon')), 
				'terminal_i'		=> (Yii::t('site', 'terminal_i')), 
				'Pay2You'			=> (Yii::t('site', 'Pay2You')), 
				'easypay'			=> (Yii::t('site', 'easypay')), 
				'lo'				=> (Yii::t('site', 'lo')), 
				'test'				=> (Yii::t('site', 'test')), 
				'bank'				=> (Yii::t('site', 'bank')), 
			)),

		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'i_id'			=> 'id заказа',
			'i_uid'			=> 'id пользователя',
			'i_date_create' => 'дата создания инвойса',
			'i_pay_day'		=> 'Дата оплаты клиентом',
			'i_date_prepaid'=> 'Дата предоплаты',
			'i_date_paid'	=> 'Дата оплаты',
			'i_summ'		=> 'Сумма инвойса',
			'i_status'		=> 'Статус инвойса',
			'i_paysys'		=> 'Способ оплаты',
			'i_apprcode'	=> 'Код платежной системы',
			'i_placement'	=> 'Место оплаты',
			'i_percent'		=> 'оплата на проценты',
			'i_peny'		=> 'Плата на пеню',
			'i_body'		=> 'Плата на тело',
			'i_buffer'		=> 'Ушло в буффер',
			'i_days_prol'	=> 'Пролонгация дней',
			'i_buffer_user' => 'Ушло в буффер пользователя',
			'i_discount'	=> 'Скидка по инвойсу',
			'i_dognumb'		=> 'Номер договора',
			'i_commision'	=> 'Комиссия платежной системы',
		);
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

		$criteria->compare('i_id',$this->i_id,true);
		$criteria->compare('i_uid',$this->i_uid,true);
		$criteria->compare('i_date_create',$this->i_date_create,true);
		$criteria->compare('i_pay_day',$this->i_date_create,true);
		$criteria->compare('i_date_prepaid',$this->i_date_prepaid,true);
		$criteria->compare('i_date_paid',$this->i_date_paid,true);
		$criteria->compare('i_summ',$this->i_summ);
		$criteria->compare('i_status',$this->i_status,true);
		$criteria->compare('i_paysys',$this->i_paysys,true);
		$criteria->compare('i_apprcode',$this->i_apprcode,true);
		$criteria->compare('i_placement',$this->i_placement,true);
		$criteria->compare('i_percent',$this->i_percent);
		$criteria->compare('i_peny',$this->i_peny);
		$criteria->compare('i_body',$this->i_body);
		$criteria->compare('i_buffer',$this->i_buffer);
		$criteria->compare('i_days_prol',$this->i_days_prol);
		$criteria->compare('i_buffer_user',$this->i_buffer_user);
		$criteria->compare('i_discount',$this->i_discount);
		$criteria->compare('i_dognumb',$this->i_dognumb);	
		$criteria->compare('i_commision',$this->i_commision);		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Invoices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
