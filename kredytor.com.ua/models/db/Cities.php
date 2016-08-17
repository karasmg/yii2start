<?php

/**
 * This is the model class for table "cities".
 *
 * The followings are the available columns in table 'cities':
 * @property string $c_id
 * @property string $c_alias
 */
class Cities extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('c_alias', 'required'),
			array('c_alias', 'length', 'max'=>100),
			array('c_alias', 'unique'),
			array('c_rid', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('c_id, c_alias last_change_date, c_rid', 'safe', 'on'=>'search'),
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
			'lang_data'=>array(self::HAS_MANY, 'CitiesLang', 'cl_cid'),
		);
	}
	
	public function selectValues() {
		return array(
			'c_rid'			=> array(self::BELONGS_TO, 'Regions', 'r_id', 'RegionsLang', 'rl_title', 'rl_lang'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'c_id'		=> (Yii::t('main', 'Row Id')),
			'c_alias'	=> (Yii::t('main', 'Alias')),
			'c_rid'		=> (Yii::t('main', 'Region title')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'c_id'		=> 'HiddenField',
			'c_alias'	=> 'TextField',
			'c_rid'		=> 'DropDownList',
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

		$criteria->compare('c_id',$this->c_id,true);
		$criteria->compare('c_alias',$this->c_alias,true);
		$criteria->compare('c_rid',$this->c_rid,true);
			

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
	 * @return Cities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function afterSave() {
		AdminHelper::primaryKeyAlias($this, 'c_alias');
	}
}
