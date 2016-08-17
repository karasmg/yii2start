<?php

/**
 * This is the model class for table "regions_lang".
 *
 * The followings are the available columns in table 'regions_lang':
 * @property string $rl_id
 * @property string $rl_rid
 * @property string $rl_lang
 * @property string $rl_title
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_discriptiion
 */
class RegionsLang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'regions_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rl_lang, rl_title', 'required'),
			array('rl_rid', 'length', 'max'=>5),
			array('rl_lang', 'length', 'max'=>2),
			array('rl_title', 'length', 'max'=>20),
			array('meta_title, meta_keywords', 'length', 'max'=>150),
			array('meta_discriptiion', 'length', 'max'=>400),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rl_id, rl_rid, rl_lang, rl_title, meta_title, meta_keywords, meta_discriptiion', 'safe', 'on'=>'search'),
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
			'rl_id'				=> (Yii::t('main', 'Row Id')),
			'rl_rid'			=> (Yii::t('main', 'Outer key')),
			'rl_lang'			=> (Yii::t('main', 'Languege identify')),
			'rl_title'			=> (Yii::t('main', 'Region title')),
			'meta_title'		=> (Yii::t('main', 'Meta title')),
			'meta_keywords'		=> (Yii::t('main', 'Meta keywords')),
			'meta_discriptiion'	=> (Yii::t('main', 'Meta discriptiion')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'rl_id'				=> 'HiddenField',
			'rl_rid'			=> 'HiddenField',
			'rl_lang'			=> 'HiddenField',
			'rl_title'			=> 'TextField',
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

		$criteria->compare('rl_id',$this->rl_id,true);
		$criteria->compare('rl_rid',$this->rl_rid,true);
		$criteria->compare('rl_lang',$this->rl_lang,true);
		$criteria->compare('rl_title',$this->rl_title,true);
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
	 * @return RegionsLang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
