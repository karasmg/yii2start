<?php

/**
 * This is the model class for table "content_categs_lang".
 *
 * The followings are the available columns in table 'content_categs_lang':
 * @property string $ccl_id
 * @property string $ccl_cid
 * @property string $ccl_lang
 * @property string $ccl_title
 * @property string $ccl_text
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_discriptiion
 */
class ContentCategsLang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content_categs_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ccl_lang, ccl_title', 'required'),
			array('ccl_cid', 'length', 'max'=>5),
			array('ccl_lang', 'length', 'max'=>2),
			array('ccl_title', 'length', 'max'=>100),
			array('meta_title, meta_keywords', 'length', 'max'=>150),
			array('meta_discriptiion', 'length', 'max'=>400),
			array('ccl_text', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ccl_id, ccl_cid, ccl_lang, ccl_title, ccl_text, meta_title, meta_keywords, meta_discriptiion', 'safe', 'on'=>'search'),
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
			'ccl_id'			=> (Yii::t('main', 'Row Id')),
			'ccl_cid'			=> (Yii::t('main', 'Outer key')),
			'ccl_lang'			=> (Yii::t('main', 'Languege identify')),
			'ccl_title'			=> (Yii::t('main', 'Category name')),
			'ccl_text'			=> (Yii::t('main', 'Text')),
			'meta_title'		=> (Yii::t('main', 'Meta title')),
			'meta_keywords'		=> (Yii::t('main', 'Meta keywords')),
			'meta_discriptiion'	=> (Yii::t('main', 'Meta discriptiion')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'ccl_id'			=> 'HiddenField',
			'ccl_cid'			=> 'HiddenField',
			'ccl_lang'			=> 'HiddenField',
			'ccl_title'			=> 'TextField',
			'ccl_text'			=> 'TextArea',
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

		$criteria->compare('ccl_id',$this->ccl_id,true);
		$criteria->compare('ccl_cid',$this->ccl_cid,true);
		$criteria->compare('ccl_lang',$this->ccl_lang,true);
		$criteria->compare('ccl_title',$this->ccl_title,true);
		$criteria->compare('ccl_text',$this->ccl_text,true);
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
	 * @return ContentCategsLang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
