<?php

/**
 * This is the model class for table "anketas_errorfields".
 *
 * The followings are the available columns in table 'anketas_errorfields':
 * @property integer $id
 * @property string $iin
 * @property string $field_name
 * @property string $curr_val
 */
class AnketasErrorfields extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'anketas_errorfields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iin, field_name', 'required'),
			array('iin', 'length', 'max'=>10),
			array('field_name', 'length', 'max'=>50),
			array('curr_val', 'length', 'max'=>300),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, iin, field_name, curr_val', 'safe', 'on'=>'search'),
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
			'id' => 'key',
			'iin' => 'id анкеты',
			'field_name' => 'имя поля с ошибкой',
			'curr_val' => 'Curr Val',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('iin',$this->iin,true);
		$criteria->compare('field_name',$this->field_name,true);
		$criteria->compare('curr_val',$this->curr_val,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AnketasErrorfields the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
