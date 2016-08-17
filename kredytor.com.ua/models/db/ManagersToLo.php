<?php

/**
 * This is the model class for table "managers_to_lo".
 *
 * The followings are the available columns in table 'managers_to_lo':
 * @property string $user_id
 * @property string $user_login
 * @property string $user_lo
 * @property string $sync_time
 */
class ManagersToLo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'managers_to_lo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, user_login, user_lo', 'required'),
			array('user_id', 'length', 'max'=>11),
			array('user_login', 'length', 'max'=>30),
			array('user_lo', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, user_login, user_lo, sync_time', 'safe', 'on'=>'search'),
			array('sync_time', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>true,'on'=>'insert, update'),
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
			'user_id' => 'id пользователя',
			'user_login' => 'логин пользователя',
			'user_lo' => 'Текущее ЛО пользователя',
			'sync_time' => 'Дата синхронизации',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('user_login',$this->user_login,true);
		$criteria->compare('user_lo',$this->user_lo,true);
		$criteria->compare('sync_time',$this->sync_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ManagersToLo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
