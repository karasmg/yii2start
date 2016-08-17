<?php

/**
 * This is the model class for table "updatelog".
 *
 * The followings are the available columns in table 'updatelog':
 * @property string $id
 * @property string $filePath
 * @property integer $lastRevision
 * @property integer $useInUpdates
 * @property string $fBackUp
 */
class Updatelog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'updatelog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lastRevision, useInUpdates', 'numerical', 'integerOnly'=>true),
			array('filePath', 'length', 'max'=>200),
			array('fBackUp', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, filePath, lastRevision, useInUpdates, fBackUp', 'safe', 'on'=>'search'),
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
			'id' => 'id',
			'filePath' => 'путь к файлу',
			'lastRevision' => 'дата обновления',
			'useInUpdates' => 'использовался в последнем обновлении',
			'fBackUp' => 'содержание до обновления',
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
		$criteria->compare('filePath',$this->filePath,true);
		$criteria->compare('lastRevision',$this->lastRevision);
		$criteria->compare('useInUpdates',$this->useInUpdates);
		$criteria->compare('fBackUp',$this->fBackUp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Updatelog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
