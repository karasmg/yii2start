<?php

/**
 * This is the model class for table "content_articles_lang".
 *
 * The followings are the available columns in table 'content_articles_lang':
 * @property string $cal_id
 * @property string $cal_catid
 * @property string $cal_lang
 * @property string $cal_title
 * @property string $cal_text
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_discriptiion
 */
class ContentArticlesLang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content_articles_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cal_lang, cal_title, cal_text', 'required'),
			array('cal_catid', 'length', 'max'=>11),
			array('cal_lang', 'length', 'max'=>2),
			array('cal_title', 'length', 'max'=>100),
			array('meta_title, meta_keywords', 'length', 'max'=>150),
			array('meta_discriptiion', 'length', 'max'=>400),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cal_id, cal_catid, cal_lang, cal_title, cal_text, meta_title, meta_keywords, meta_discriptiion', 'safe', 'on'=>'search'),
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
			'cal_id'			=> (Yii::t('main', 'Row Id')),
			'cal_catid'			=> (Yii::t('main', 'Outer key')),
			'cal_lang'			=> (Yii::t('main', 'Languege identify')),
			'cal_title'			=> (Yii::t('main', 'Article title')),
			'cal_text'			=> (Yii::t('main', 'Article text')),
			'meta_title'		=> (Yii::t('main', 'Meta title')),
			'meta_keywords'		=> (Yii::t('main', 'Meta keywords')),
			'meta_discriptiion'	=> (Yii::t('main', 'Meta discriptiion')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'cal_id'			=> 'HiddenField',
			'cal_catid'			=> 'HiddenField',
			'cal_lang'			=> 'HiddenField',
			'cal_title'			=> 'TextField',
			'cal_text'			=> 'TextArea',
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

		$criteria->compare('cal_id',$this->cal_id,true);
		$criteria->compare('cal_catid',$this->cal_catid,true);
		$criteria->compare('cal_lang',$this->cal_lang,true);
		$criteria->compare('cal_title',$this->cal_title,true);
		$criteria->compare('cal_text',$this->cal_text,true);
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
	 * @return ContentArticlesLang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
