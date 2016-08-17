<?php

/**
 * This is the model class for table "trigger_log".
 *
 * The followings are the available columns in table 'trigger_log':
 * @property string $t_id
 * @property string $t_uid
 * @property string $t_time
 * @property string $t_url
 * @property string $t_post
 * @property string $t_user_agent
 * @property string $t_ip
 * @property string $t_action
 * @property string $t_referrer
 */
class TriggerLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'trigger_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('t_time', 'required'),
			array('t_uid', 'length', 'max'=>5),
			array('t_ip', 'length', 'max'=>39),
			array('t_action', 'length', 'max'=>100),
			array('t_url, t_post, t_user_agent, t_referrer', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('t_id, t_uid, t_time, t_url, t_post, t_user_agent, t_ip, t_action, t_referrer', 'safe', 'on'=>'search'),
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
			't_id' 			=> (Yii::t('main', 'Row Id')),
			't_uid' 		=> (Yii::t('main', 'User Id')),
			't_time' 		=> (Yii::t('main', 'Time Stamp')),
			't_url' 		=> (Yii::t('main', 'URL')),
			't_post' 		=> (Yii::t('main', 'POST')),
			't_user_agent' 	=> (Yii::t('main', 'User Agent')),
			't_ip' 			=> (Yii::t('main', 'IP addres')),
			't_action' 		=> (Yii::t('main', 'Controller/Action')),
			't_referrer' 	=> (Yii::t('main', 'Referrer')),
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

		$criteria->compare('t_id',$this->t_id,true);
		$criteria->compare('t_uid',$this->t_uid,true);
		$criteria->compare('t_time',$this->t_time,true);
		$criteria->compare('t_url',$this->t_url,true);
		$criteria->compare('t_post',$this->t_post,true);
		$criteria->compare('t_user_agent',$this->t_user_agent,true);
		$criteria->compare('t_ip',$this->t_ip,true);
		$criteria->compare('t_action',$this->t_action,true);
		$criteria->compare('t_referrer',$this->t_referrer,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TriggerLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
