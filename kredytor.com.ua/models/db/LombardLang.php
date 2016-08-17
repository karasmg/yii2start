<?php

/**
 * This is the model class for table "lombard_lang".
 *
 * The followings are the available columns in table 'lombard_lang':
 * @property string $ll_id
 * @property string $ll_lid
 * @property string $ll_lang
 * @property string $ll_title
 * @property string $ll_text
 * @property string $ll_adress
 * @property string $ll_phones
 * @property string $ll_graffic
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_discriptiion
 */
class LombardLang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lombard_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ll_adress', 'required'),
			array('ll_lid', 'length', 'max'=>5),
			array('ll_lang', 'length', 'max'=>2),
			array('ll_title, ll_phones, ll_graffic', 'length', 'max'=>200),
			array('ll_adress', 'length', 'max'=>250),
			array('meta_title, meta_keywords', 'length', 'max'=>150),
			array('meta_discriptiion', 'length', 'max'=>400),
			array('ll_text', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ll_id, ll_lid, ll_lang, ll_title, ll_text, ll_adress, ll_phones, ll_graffic, meta_title, meta_keywords, meta_discriptiion', 'safe', 'on'=>'search'),
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
			'll_id'				=> (Yii::t('main', 'Row Id')),
			'll_lid'			=> (Yii::t('main', 'Outer key')),
			'll_lang'			=> (Yii::t('main', 'Languege identify')),
			'll_title'			=> (Yii::t('main', 'Lombard title')),
			'll_text'			=> (Yii::t('main', 'Lombard text')),
			'll_adress'			=> (Yii::t('main', 'Lombard address')),
			'll_phones'			=> (Yii::t('main', 'Lombard phones')),
			'll_graffic'		=> (Yii::t('main', 'Work graphic')),
			'meta_title'		=> (Yii::t('main', 'Meta title')),
			'meta_keywords'		=> (Yii::t('main', 'Meta keywords')),
			'meta_discriptiion'	=> (Yii::t('main', 'Meta discriptiion')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'll_id'				=> 'HiddenField',
			'll_lid'			=> 'HiddenField',
			'll_lang'			=> 'HiddenField',
			'll_title'			=> 'TextField',
			'll_text'			=> 'TextArea',
			'll_adress'			=> 'TextAreaSimpleEditor',
			'll_phones'			=> 'TextAreaSimpleEditor',
			'll_graffic'		=> 'TextAreaSimpleEditor',
			'meta_title'		=> 'TextField',
			'meta_keywords'		=> 'TextField',
			'meta_discriptiion'	=> 'TextField',
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

		$criteria->compare('ll_id',$this->ll_id,true);
		$criteria->compare('ll_lid',$this->ll_lid,true);
		$criteria->compare('ll_lang',$this->ll_lang,true);
		$criteria->compare('ll_title',$this->ll_title,true);
		$criteria->compare('ll_text',$this->ll_text,true);
		$criteria->compare('ll_adress',$this->ll_adress,true);
		$criteria->compare('ll_phones',$this->ll_phones,true);
		$criteria->compare('ll_graffic',$this->ll_graffic,true);
		$criteria->compare('meta_title',$this->meta_title,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);
		$criteria->compare('meta_discriptiion',$this->meta_discriptiion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LombardLang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
