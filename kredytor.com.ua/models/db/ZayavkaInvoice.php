<?php

/**
 * This is the model class for table "zayavka_invoice".
 *
 * The followings are the available columns in table 'zayavka_invoice':
 * @property string $id
 * @property string $zid
 * @property string $number
 * @property string $date_from_mag
 * @property string $date_to_zayav
 * @property string $date_annulate
 * @property string $reason_annulate
 * @property string $shop_name
 * @property string $shop_addr
 * @property string $income_ip
 */
class ZayavkaInvoice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zayavka_invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number, date_from_mag', 'required'),
			array('number', 'unique'),
			array('zid, number', 'length', 'max'=>11),
			array('reason_annulate', 'length', 'max'=>500),
			array('shop_name, shop_addr', 'length', 'max'=>300),
			array('income_ip', 'saveIncomeIp'),
			array('date_to_zayav, date_used_inmag, date_annulate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, zid, number, date_from_mag, date_to_zayav, date_used_inmag, date_annulate, reason_annulate, shop_name, shop_addr, income_ip', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'id счета-фактуры',
			'zid' => 'id заявки',
			'number' => 'Номер счет-фактуры',
			'date_from_mag' => 'Счет-фактура от',
			'date_to_zayav' => 'Дата включения в заявку',
			'date_used_inmag' => 'Дата использования в магазине',
			'date_annulate' => 'Дата анулирования',
			'reason_annulate' => 'Причина анулирования',
			'shop_name' => 'Название магазина',
			'shop_addr' => 'Адрес, url или физический',
			'income_ip' => 'ip откуда пришел инвойс',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('zid',$this->zid,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('date_from_mag',$this->date_from_mag,true);
		$criteria->compare('date_to_zayav',$this->date_to_zayav,true);
		$criteria->compare('date_used_inmag',$this->date_used_inmag,true);
		$criteria->compare('date_annulate',$this->date_annulate,true);
		$criteria->compare('reason_annulate',$this->reason_annulate,true);
		$criteria->compare('shop_name',$this->shop_name,true);
		$criteria->compare('shop_addr',$this->shop_addr,true);
		$criteria->compare('income_ip',$this->income_ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function saveIncomeIp($attribute='income_ip', $params=null)
	{
		if ( empty($this->income_ip) ) {
			$this->income_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ZayavkaInvoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
