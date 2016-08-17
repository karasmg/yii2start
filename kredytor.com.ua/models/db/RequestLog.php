<?php

/**
 * This is the model class for table "request_log".
 *
 * The followings are the available columns in table 'request_log':
 * @property string $date
 * @property string $request_type
 * @property integer $request_id
 * @property string $request_params
 * @property string $request_user
 * @property string $request_ip
 * @property string $response_time
 */
class RequestLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'request_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('request_params', 'required'),
			array('request_id', 'numerical', 'integerOnly'=>true),
			array('request_type', 'length', 'max'=>50),
			array('request_user', 'length', 'max'=>100),
			array('request_ip', 'saveIncomeIp'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('date, request_type, request_id, request_params, response, request_user, request_ip, response_time', 'safe', 'on'=>'search'),
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
			'date' => 'Дата логирования',
			'request_type' => 'Тип запроса',
			'request_id' => 'id запроса из requests_1c',
			'request_params' => 'Сообщение',
			'response' => 'Ответ сайта',
			'request_user' => 'Логин от кого пришел запрос',
			'request_ip' => 'ip от кого пришел запрос',
			'response_time' => 'дата/время ответа на запрос',
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

		$criteria->compare('date',$this->date,true);
		$criteria->compare('request_type',$this->request_type,true);
		$criteria->compare('request_id',$this->request_id);
		$criteria->compare('request_params',$this->request_params,true);
		$criteria->compare('response',$this->response,true);
		$criteria->compare('request_user',$this->request_user,true);
		$criteria->compare('request_ip',$this->request_ip,true);
		$criteria->compare('response_time',$this->response_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RequestLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function saveIncomeIp($attribute='request_ip', $params=null)
	{
		if ( empty($this->request_ip) ) {
			$this->request_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
