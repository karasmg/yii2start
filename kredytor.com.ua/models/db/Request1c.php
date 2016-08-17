<?php

/**
 * This is the model class for table "request_1c".
 *
 * The followings are the available columns in table 'request_1c':
 * @property string $id
 * @property string $request
 * @property string $request_date
 * @property string $response
 * @property string $response_date
 * @property integer $active
 */
class Request1c extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'request_1c';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, request, request_date, row_id, dyrectedto', 'required'),
			array('active, err_count', 'numerical', 'integerOnly'=>true),
			array('response, response_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, request, request_date, response, response_date, active, type, row_id, dyrectedto, last_showed, err_count', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'type'=>'type',
			'request' => 'Request',
			'request_date' => 'Request Date',
			'response' => 'Response',
			'response_date' => 'Response Date',
			'active' => 'Active',
			'row_id' => 'Id of db Row fo request',
			'dyrectedto' => 'dyrectedto',
			'last_showed'=>'last_showed',
			'err_count'	=> 'err_count',
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
		$criteria->compare('request',$this->request,true);
		$criteria->compare('request_date',$this->request_date,true);
		$criteria->compare('response',$this->response,true);
		$criteria->compare('response_date',$this->response_date,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('type',$this->type);
		$criteria->compare('row_id',$this->row_id);	
		$criteria->compare('dyrectedto',$this->dyrectedto);	
		$criteria->compare('last_showed',$this->last_showed);	
		$criteria->compare('err_count',$this->err_count);
				
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Request1c the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
