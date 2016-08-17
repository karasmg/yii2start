<?php

/**
 * This is the model class for table "menu_lang".
 *
 * The followings are the available columns in table 'menu_lang':
 * @property string $ml_id
 * @property string $ml_mid
 * @property string $ml_lang
 * @property string $ml_title
 * @property string $ml_image
 */
class MenuLang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ml_lang, ml_title', 'required'),
			array('ml_mid', 'length', 'max'=>11),
			array('ml_lang', 'length', 'max'=>2),
			array('ml_title, ml_image', 'length', 'max'=>100),
			//array('ml_image', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ml_id, ml_mid, ml_lang, ml_title, ml_image', 'safe', 'on'=>'search'),
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
			'ml_id'			=> (Yii::t('main', 'Row Id')),
			'ml_mid'		=> (Yii::t('main', 'Outer key')),
			'ml_lang'		=> (Yii::t('main', 'Languege identify')),
			'ml_title'		=> (Yii::t('main', 'Menu title')),
			'ml_image'		=> (Yii::t('main', 'Menu image')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'ml_id'				=> 'HiddenField',
			'ml_mid'			=> 'HiddenField',
			'ml_lang'			=> 'HiddenField',
			'ml_title'			=> 'TextField',
			'ml_image'			=> 'FileField',
		);
		
		if ( isset($fields[$asked_field]) ) return $fields[$asked_field];
		else return 'TextField';
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

		$criteria->compare('ml_id',$this->ml_id,true);
		$criteria->compare('ml_mid',$this->ml_mid,true);
		$criteria->compare('ml_lang',$this->ml_lang,true);
		$criteria->compare('ml_title',$this->ml_title,true);
		$criteria->compare('ml_image',$this->ml_image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MenuLang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
