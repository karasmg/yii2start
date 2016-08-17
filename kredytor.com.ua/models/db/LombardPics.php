<?php

/**
 * This is the model class for table "lombard_pics".
 *
 * The followings are the available columns in table 'lombard_pics':
 * @property integer $lp_id
 * @property string $lp_lid
 * @property string $lp_path
 * @property integer $lp_with
 * @property integer $lp_height
 */
class LombardPics extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lombard_pics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lp_lid, lp_path', 'required'),
			array('lp_lid', 'length', 'max'=>10),
			array('lp_path', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lp_id, lp_lid, lp_path', 'safe', 'on'=>'search'),
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
			'lp_id' => 'id картинки',
			'lp_lid' => 'связь с lombard l_id',
			'lp_path' => 'относительный путь к изображению',
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

		$criteria->compare('lp_id',$this->lp_id);
		$criteria->compare('lp_lid',$this->lp_lid,true);
		$criteria->compare('lp_path',$this->lp_path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LombardPics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function afterDelete() {
		parent::afterDelete();
		unlink($_SERVER['DOCUMENT_ROOT'].$this->lp_path);
	}
}
