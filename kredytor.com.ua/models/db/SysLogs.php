<?php

/**
 * This is the model class for table "sys_logs".
 *
 * The followings are the available columns in table 'sys_logs':
 * @property string $sl_id
 * @property string $sl_user_id
 * @property string $sl_table_name
 * @property string $sl_table_id_row
 * @property string $sl_action
 * @property string $sl_date
 */
class SysLogs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sl_user_id, sl_table_name, sl_table_id_row, sl_date', 'required'),
			array('sl_user_id', 'length', 'max'=>5),
			array('sl_table_name', 'length', 'max'=>30),
			array('sl_table_id_row', 'length', 'max'=>11),
			array('sl_action', 'length', 'max'=>6),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sl_id, sl_user_id, sl_table_name, sl_table_id_row, sl_action, sl_date', 'safe', 'on'=>'search'),
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
			'user_data'=>array(self::BELONGS_TO, 'Users', 'sl_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sl_id' => 'id записи',
			'sl_user_id' => 'связь с таблицей users u_id',
			'sl_table_name' => 'таблица бд',
			'sl_table_id_row' => 'строка таблицы бд',
			'sl_action' => 'тип действия пользователя',
			'sl_date' => 'Дата действия пользователя',
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

		$criteria->compare('sl_id',$this->sl_id,true);
		$criteria->compare('sl_user_id',$this->sl_user_id,true);
		$criteria->compare('sl_table_name',$this->sl_table_name,true);
		$criteria->compare('sl_table_id_row',$this->sl_table_id_row,true);
		$criteria->compare('sl_action',$this->sl_action,true);
		$criteria->compare('sl_date',$this->sl_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SysLogs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function behaviors(){
		return array('ESaveRelatedBehavior' => array(
			'class' => 'application.components.ESaveRelatedBehavior')
		);
	}
}
