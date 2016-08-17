<?php

/**
 * This is the model class for table "reminder_log".
 *
 * The followings are the available columns in table 'reminder_log':
 * @property integer $id
 * @property integer $user_id
 * @property integer $msg_type
 * @property string  $send_date
 * @property integer $dog_id
 */
class ReminderLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reminder_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, msg_type, dog_id', 'required'),
			array('user_id, msg_type, dog_id', 'numerical', 'integerOnly'=>true),
			array('dog_id', 'length', 'max'=>11),
			array('user_id', 'length', 'max'=>5),
			array('msg_type', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('$id, $user_id, $msg_type, $dog_id, $send_date', 'safe', 'on'=>'search'),
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
				'msg_type'		=> array('DoneVals', array(
						'1'=>(Yii::t('site', 'SMS')),
						'2'=>(Yii::t('site', 'email')),
	)));
	
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'id',
			'user_id' => 'id пользователя',
			'msg_type' => 'Тип сообщения',
			'send_date' => 'Дата отправки сообщения',
			'dog_id' => 'id договора',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('msg_type',$this->msg_type, true);
		$criteria->compare('send_date',$this->send_date,true);
		$criteria->compare('dog_id',$this->dog_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return reminderLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
