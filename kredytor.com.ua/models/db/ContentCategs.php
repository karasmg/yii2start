<?php

/**
 * This is the model class for table "content_categs".
 *
 * The followings are the available columns in table 'content_categs':
 * @property string $cc_id
 * @property integer $cc_active
 * @property string $cc_alias
 */
class ContentCategs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content_categs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cc_alias', 'required'),
			array('cc_active', 'numerical', 'integerOnly'=>true),
			array('cc_alias', 'length', 'max'=>100),
			array('cc_alias', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cc_id, cc_active, cc_alias', 'safe', 'on'=>'search'),
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
			'lang_data'=>array(self::HAS_MANY, 'ContentCategsLang', 'ccl_cid'),
			'items_data'=>array(self::HAS_MANY, 'ContentArticles', 'ca_cat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cc_id'		=> (Yii::t('main', 'Row Id')),
			'cc_active' => (Yii::t('main', 'Is active')),
			'cc_alias'	=> (Yii::t('main', 'Alias')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'cc_id' => 'HiddenField',
			'cc_alias' => 'TextField',
			'cc_active' => 'CheckBox',
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

		$criteria->compare('cc_id',$this->cc_id,true);
		$criteria->compare('cc_active',$this->cc_active);
		$criteria->compare('cc_alias',$this->cc_alias,true);

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
	 * @return ContentCategs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*protected function afterSave() {
		AdminHelper::primaryKeyAlias($this, 'cc_alias');
	}
	 * 
	 */
}
