<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property string $m_id
 * @property string $m_par_id
 * @property string $m_active
 * @property string $m_type
 * @property string $m_resource_val
 */
class Menu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('m_par_id', 'length', 'max'=>11),
			array('m_active', 'length', 'max'=>1),
			array('m_type', 'length', 'max'=>2),
			array('m_resource_val', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('m_id, m_par_id, m_active, m_type, m_resource_val', 'safe', 'on'=>'search'),
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
			'lang_data'=>array(self::HAS_MANY, 'MenuLang', 'ml_mid'),
		);
	}
	
	public function selectValues() {
		return array(
			'm_par_id'			=> array(self::BELONGS_TO, 'Menu', 'm_id', 'MenuLang', 'ml_title', 'ml_lang', array('m_id'=>null)),
			'm_resource_val'	=> array(self::BELONGS_TO, array(
				array('1', 'ContentArticles', 'ca_id', 'ContentArticlesLang', 'cal_title', 'cal_lang', array('ca_cat_id'=>'1'), false),
				array('2', 'ContentCategs', 'cc_id', 'ContentCategsLang', 'ccl_title', 'ccl_lang', array(), false),
			)),
			'm_type'			=> array('DoneVals', array('1'=>(Yii::t('main', 'Articles')), '2'=>(Yii::t('main', 'Categories')), '3'=>(Yii::t('main', 'News')), '4'=>(Yii::t('main', 'Guestbook')), '5'=>(Yii::t('main', 'Main')), '6'=>(Yii::t('main', 'Catalog')), '7'=>(Yii::t('main', 'Lombards')) )),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'm_id'				=> (Yii::t('main', 'Row Id')),
			'm_par_id'			=> (Yii::t('main', 'Parrent Id')),
			'm_active'			=> (Yii::t('main', 'Is active')),
			'm_type'			=> (Yii::t('main', 'Menu type')),
			'm_resource_val'	=> (Yii::t('main', 'Menu element')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'm_id'			=> 'HiddenField',
			'm_par_id'		=> 'DropDownList',
			'm_active'		=> 'CheckBox',
			'm_type'		=> 'DropDownList',
			'm_resource_val'=> 'DropDownList',
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

		$criteria->compare('m_id',$this->m_id,true);
		$criteria->compare('m_par_id',$this->m_par_id,true);
		$criteria->compare('m_active',$this->m_active,true);
		$criteria->compare('m_type',$this->m_type,true);
		$criteria->compare('m_resource_val',$this->m_resource_val,true);

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
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}