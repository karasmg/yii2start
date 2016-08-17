<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property string $n_id
 * @property string $n_active
 * @property string $n_alias
 * @property string $n_active_date
 * @property string $n_anons_pic
 * @property integer $n_text_pic
 */
class News extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('n_alias', 'required'),
			array('n_active', 'length', 'max'=>1),
			array('n_alias, n_anons_pic, n_text_pic', 'length', 'max'=>100),
			array('n_cat_id', 'length', 'max'=>5),
			array('n_anons_pic, n_text_pic', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			array('n_active_date', 'safe'),
			array('n_active_date', 'checkIsNotEmptydate'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('n_id, n_active, n_alias, n_active_date, n_anons_pic, n_text_pic', 'safe', 'on'=>'search'),
			array('last_change_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'insert'),
			array('last_change_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'update'),
			array('n_active_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>true,'on'=>'insert'),
		);
	}
	
	public function checkIsNotEmptydate () {
		if ( !$this->n_active_date ) $this->n_active_date = null;
		return true;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'lang_data'=>array(self::HAS_MANY, 'NewsLang', 'nl_nid'),
			'n_cat_id'	=> array(self::BELONGS_TO, 'ContentCategs', 'cc_id'),
		);
	}
	
	public function selectValues() {
		return array(
			'n_cat_id'	=> array(self::BELONGS_TO, 'ContentCategs', 'cc_id', 'ContentCategsLang', 'ccl_title', 'ccl_lang'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'n_id'			=> (Yii::t('main', 'Row Id')),
			'n_active'		=> (Yii::t('main', 'Is active')),
			'n_alias'		=> (Yii::t('main', 'Alias')),
			'n_cat_id'		=> (Yii::t('main', 'Category name')),
			'n_active_date' => (Yii::t('main', 'Activate date')),
			'n_anons_pic'	=> (Yii::t('main', 'Annonce picture')),
			'n_text_pic'	=> (Yii::t('main', 'Text picture')),
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'n_id'			=> 'HiddenField',
			'n_active'		=> 'CheckBox',
			'n_alias'		=> 'TextField',
			'n_cat_id'		=> 'DropDownList',
			'n_active_date' => 'DateField',
			'n_anons_pic'	=> 'FileField',
			'n_text_pic'	=> 'FileField',
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

		$criteria->compare('n_id',$this->n_id,true);
		$criteria->compare('n_active',$this->n_active,true);
		$criteria->compare('n_alias',$this->n_alias,true);
		$criteria->compare('n_cat_id',$this->n_cat_id,true);
		$criteria->compare('n_active_date',$this->n_active_date,true);
		$criteria->compare('n_anons_pic',$this->n_anons_pic,true);
		$criteria->compare('n_text_pic',$this->n_text_pic);

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
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function afterSave() {
		AdminHelper::primaryKeyAlias($this, 'n_alias');
	}
	
	protected function beforeSave() {
		if ( $this->scenario == 'insert' ) {
			$this->n_active_date = new CDbExpression('NOW()');
		}
		return parent::beforeSave();
	}
}
