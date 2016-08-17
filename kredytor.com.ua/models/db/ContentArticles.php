<?php

/**
 * This is the model class for table "content_articles".
 *
 * The followings are the available columns in table 'content_articles':
 * @property string $ca_id
 * @property integer $ca_active
 * @property string $ca_alias
 * @property string $ca_cat_id
 */
class ContentArticles extends CActiveRecord
{	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content_articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ca_alias', 'required'),
			array('ca_active', 'numerical', 'integerOnly'=>true),
			array('ca_alias', 'length', 'max'=>100),
			array('ca_cat_id', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ca_id, ca_active, ca_alias, ca_cat_id', 'safe', 'on'=>'search'),
			array('last_change_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'insert'),
			array('last_change_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'update'),
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
			'lang_data'	=> array(self::HAS_MANY, 'ContentArticlesLang', 'cal_catid'),
			'ca_cat_id'	=> array(self::BELONGS_TO, 'ContentCategs', 'cc_id'),
		);
	}
	
	public function selectValues() {
		return array(
			'ca_cat_id'	=> array(self::BELONGS_TO, 'ContentCategs', 'cc_id', 'ContentCategsLang', 'ccl_title', 'ccl_lang'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ca_id'		=> (Yii::t('main', 'Row Id')),
			'ca_active' => (Yii::t('main', 'Is active')),
			'ca_alias'	=> (Yii::t('main', 'Alias')),
			'ca_cat_id' => (Yii::t('main', 'Category name')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'ca_id'			=> 'HiddenField',
			'ca_cat_id'		=> 'DropDownList',
			'cc_alias'		=> 'TextField',
			'ca_active'		=> 'CheckBox',
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

		$criteria->compare('ca_id',$this->ca_id,true);
		$criteria->compare('ca_active',$this->ca_active);
		$criteria->compare('ca_alias',$this->ca_alias,true);
		$criteria->compare('ca_cat_id',$this->ca_cat_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array('ESaveRelatedBehavior' => array(
			'class' => 'application.components.ESaveRelatedBehavior')
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContentArticles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
