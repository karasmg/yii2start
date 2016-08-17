<?php

/**
 * This is the model class for table "catalog_spravs".
 *
 * The followings are the available columns in table 'catalog_spravs':
 * @property string $cs_id
 * @property string $cs_ccid
 * @property string $cs_active
 * @property string $cs_alias
 */
class CatalogSpravs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catalog_spravs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cs_alias', 'required'),
			array('cs_ccid', 'length', 'max'=>5),
			array('cs_active', 'length', 'max'=>1),
			array('cs_alias', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cs_id, cs_ccid, cs_active, cs_alias', 'safe', 'on'=>'search'),
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
			'lang_data'=>array(self::HAS_MANY, 'CatalogSpravsLang', 'csl_csid'),
			'cs_ccid'	=> array(self::BELONGS_TO, 'ContentCategs', 'cc_id'),
		);
	}
	
	public function selectValues() {
		return array(
			'cs_ccid'	=> array(self::BELONGS_TO, 'ContentCategs', 'cc_id', 'ContentCategsLang', 'ccl_title', 'ccl_lang'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cs_id'		=> (Yii::t('main', 'Row Id')),
			'cs_ccid'	=> (Yii::t('main', 'Category name')),
			'cs_active' => (Yii::t('main', 'Is active')),
			'cs_alias'	=> (Yii::t('main', 'Alias')),	
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'cs_id'			=> 'HiddenField',
			'cs_active'		=> 'CheckBox',
			'cs_alias'		=> 'TextField',
			'cs_ccid'		=> 'DropDownList',
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

		$criteria->compare('cs_id',$this->cs_id,true);
		$criteria->compare('cs_ccid',$this->cs_ccid,true);
		$criteria->compare('cs_active',$this->cs_active,true);
		$criteria->compare('cs_alias',$this->cs_alias,true);

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
	 * @return CatalogSpravs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
