<?php

/**
 * This is the model class for table "catalog_spravs_lang".
 *
 * The followings are the available columns in table 'catalog_spravs_lang':
 * @property string $csl_id
 * @property string $csl_csid
 * @property string $csl_lang
 * @property string $csl_title
 * @property string $csl_text
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_discriptiion
 */
class CatalogSpravsLang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catalog_spravs_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('csl_lang, csl_title', 'required'),
			array('csl_csid', 'length', 'max'=>11),
			array('csl_lang', 'length', 'max'=>2),
			array('csl_title', 'length', 'max'=>100),
			array('meta_title, meta_keywords', 'length', 'max'=>150),
			array('meta_discriptiion', 'length', 'max'=>400),
			array('csl_text', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('csl_id, csl_csid, csl_lang, csl_title, csl_text, meta_title, meta_keywords, meta_discriptiion', 'safe', 'on'=>'search'),
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
			'csl_id'			=> (Yii::t('main', 'Row Id')),
			'csl_csid'			=> (Yii::t('main', 'Outer key')),
			'csl_lang'			=> (Yii::t('main', 'Languege identify')),
			'csl_title'			=> (Yii::t('main', 'Sprav title')),
			'csl_text'			=> (Yii::t('main', 'Text')),
			'meta_title'		=> (Yii::t('main', 'Meta title')),
			'meta_keywords'		=> (Yii::t('main', 'Meta keywords')),
			'meta_discriptiion' => (Yii::t('main', 'Meta discriptiion')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'csl_id'			=> 'HiddenField',
			'csl_csid'			=> 'HiddenField',
			'csl_lang'			=> 'HiddenField',
			'csl_title'			=> 'TextField',
			'csl_text'			=> 'TextArea',
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

		$criteria->compare('csl_id',$this->csl_id,true);
		$criteria->compare('csl_csid',$this->csl_csid,true);
		$criteria->compare('csl_lang',$this->csl_lang,true);
		$criteria->compare('csl_title',$this->csl_title,true);
		$criteria->compare('csl_text',$this->csl_text,true);
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
	 * @return CatalogSpravsLang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
