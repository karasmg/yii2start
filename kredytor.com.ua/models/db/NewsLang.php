<?php

/**
 * This is the model class for table "news_lang".
 *
 * The followings are the available columns in table 'news_lang':
 * @property string $nl_id
 * @property string $nl_nid
 * @property string $nl_lang
 * @property string $nl_title
 * @property string $nl_anons
 * @property string $nl_text
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_discriptiion
 * @property string $nl_pic
 */
class NewsLang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nl_lang, nl_title, nl_anons, nl_text', 'required'),
			array('nl_nid', 'length', 'max'=>5),
			array('nl_lang', 'length', 'max'=>2),
			array('nl_title, nl_pic', 'length', 'max'=>100),
			array('nl_anons, meta_discriptiion', 'length', 'max'=>400),
			array('meta_title, meta_keywords', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('nl_id, nl_nid, nl_lang, nl_title, nl_anons, nl_text, meta_title, meta_keywords, meta_discriptiion, nl_pic', 'safe', 'on'=>'search'),
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
			'nl_id'				=> (Yii::t('main', 'Row Id')),
			'nl_nid'			=> (Yii::t('main', 'Outer key')),
			'nl_lang'			=> (Yii::t('main', 'Languege identify')),
			'nl_title'			=> (Yii::t('main', 'News title')),
			'nl_anons'			=> (Yii::t('main', 'Annonce')),
			'nl_text'			=> (Yii::t('main', 'News text')),
			'nl_pic'			=> (Yii::t('main', 'Text picture')),
			'meta_title'		=> (Yii::t('main', 'Meta title')),
			'meta_keywords'		=> (Yii::t('main', 'Meta keywords')),
			'meta_discriptiion'	=> (Yii::t('main', 'Meta discriptiion')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'nl_id'				=> 'HiddenField',
			'nl_nid'			=> 'HiddenField',
			'nl_lang'			=> 'HiddenField',
			'nl_title'			=> 'TextField',
			'nl_anons'			=> 'TextArea',
			'nl_text'			=> 'TextArea',
			'nl_pic'			=> 'FileField',
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

		$criteria->compare('nl_id',$this->nl_id,true);
		$criteria->compare('nl_nid',$this->nl_nid,true);
		$criteria->compare('nl_lang',$this->nl_lang,true);
		$criteria->compare('nl_title',$this->nl_title,true);
		$criteria->compare('nl_anons',$this->nl_anons,true);
		$criteria->compare('nl_text',$this->nl_text,true);
		$criteria->compare('meta_title',$this->meta_title,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);
		$criteria->compare('meta_discriptiion',$this->meta_discriptiion,true);
		$criteria->compare('nl_pic',$this->nl_pic,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NewsLang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
