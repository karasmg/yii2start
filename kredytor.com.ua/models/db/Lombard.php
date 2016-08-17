<?php

/**
 * This is the model class for table "lombard".
 *
 * The followings are the available columns in table 'lombard':
 * @property string $l_id
 * @property string $l_cid
 * @property integer $l_active
 * @property string $l_alias
 * @property integer $l_allday
 * @property double $l_coord_lati
 * @property double $l_coord_long
 * @property string $l_map_link
 */
class Lombard extends CActiveRecord
{
	public $temp_pics_key = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lombard';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('l_cid, l_alias', 'required'),
			array('l_active, l_allday', 'numerical', 'integerOnly'=>true),
			array('l_coord_lati, l_coord_long', 'numerical'),
			array('l_cid', 'length', 'max'=>5),
			array('l_alias, temp_pics_key', 'length', 'max'=>100),
			array('l_map_link', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('l_id, l_cid, l_active, l_alias, l_allday, l_coord_lati, l_coord_long, l_map_link', 'safe', 'on'=>'search'),
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
			'lang_data'=>array(self::HAS_MANY, 'LombardLang', 'll_lid'),
			'pics_data'=>array(self::HAS_MANY, 'LombardPics', 'lp_lid'),
		);
	}
	
	public function selectValues() {
		return array(
			'l_cid'	=> array(self::BELONGS_TO, 'Cities', 'c_id', 'CitiesLang', 'cl_title', 'cl_lang'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'l_id'			=> (Yii::t('main', 'Row Id')),
			'l_cid'			=> (Yii::t('main', 'City title')),
			'l_active'		=> (Yii::t('main', 'Is active')),
			'l_alias'		=> (Yii::t('main', 'Alias')),
			'l_allday'		=> (Yii::t('main', 'Convenience Store')),
			'l_coord_lati'	=> (Yii::t('main', 'Coordinates latitude')),
			'l_coord_long'	=> (Yii::t('main', 'Coordinates longitude')),
			'coords_create'	=> 'LombardLang_1_ll_adress,Lombard_l_coord_lati,Lombard_l_coord_long',
			'coords_check'	=> 'Lombard_l_coord_lati,Lombard_l_coord_long',
			'l_map_link'	=> (Yii::t('main', 'Map link')),
			'temp_pics_key' => (Yii::t('main', 'Outer key')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'l_id'			=> 'HiddenField',
			'l_cid'			=> 'DropDownList',
			'l_active'		=> 'CheckBox',
			'l_alias'		=> 'TextField',
			'l_allday'		=> 'CheckBox',
			'l_coord_lati'	=> 'TextField',
			'l_coord_long'	=> 'TextField',
			'l_map_link'	=> 'TextField',
			'temp_pics_key' => 'HiddenField',
			'coords_create' => 'YaMapCoords',
			'coords_check'  => 'YaMapCheck',
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

		$criteria->compare('l_id',$this->l_id,true);
		$criteria->compare('l_cid',$this->l_cid,true);
		$criteria->compare('l_active',$this->l_active);
		$criteria->compare('l_alias',$this->l_alias,true);
		$criteria->compare('l_allday',$this->l_allday);
		$criteria->compare('l_coord_lati',$this->l_coord_lati);
		$criteria->compare('l_coord_long',$this->l_coord_long);
		$criteria->compare('l_map_link',$this->l_map_link,true);

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
	 * @return Lombard the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function afterSave() {
		AdminHelper::primaryKeyAlias($this, 'l_alias');
	}
}
