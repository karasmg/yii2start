<?php

/**
 * This is the model class for table "state_log".
 *
 * The followings are the available columns in table 'state_log':
 * @property string $s_id
 * @property string $s_zid
 * @property string $s_time
 * @property string $s_state
 * @property string $s_action
 * @property string $s_controller
 * @property string $s_comment
 */
class StateLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'state_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('s_zid, s_state, s_action, s_controller', 'required'),
			array('s_zid', 'length', 'max'=>11),
			array('s_state', 'length', 'max'=>2),
			array('s_action, s_controller', 'length', 'max'=>50),
			array('s_comment', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('s_id, s_zid, s_time, s_state, s_action, s_controller, s_comment', 'safe', 'on'=>'search'),
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
			's_id' => 'ID',
			's_zid' => 'ID Заявки',
			's_time' => 'Дата/Время',
			's_state' => 'Состояние',
			's_action' => 'Имя метода',
			's_controller' => 'Имя контроллера',
			's_comment' => 'Комментарий',
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

		$criteria->compare('s_id',$this->s_id,true);
		$criteria->compare('s_zid',$this->s_zid,true);
		$criteria->compare('s_time',$this->s_time,true);
		$criteria->compare('s_state',$this->s_state,true);
		$criteria->compare('s_action',$this->s_action,true);
		$criteria->compare('s_controller',$this->s_controller,true);
		$criteria->compare('s_comment',$this->s_comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StateLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
