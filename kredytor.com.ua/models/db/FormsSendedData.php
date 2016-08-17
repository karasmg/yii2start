<?php

/*/**
 * To change this template, choose Tools | TemplatesThis is the model class for table "forms_sended_data".
 * and open
 * The followings are the templateavailable columns in the editor.table 'forms_sended_data':
 * @property integer $f_id
 * @property string $f_date
 * @property string $f_field_types
 * @property string $f_field_vals
 * @property string $f_form_state
 */
class FormsSendedData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'forms_sended_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('f_field_types, f_field_vals, f_form_name, f_field_names', 'required'),
			array('f_field_types, f_field_names', 'length', 'max'=>800),
			array('f_form_name', 'length', 'max'=>200),
			array('f_form_state', 'length', 'max'=>10),
			array('f_form_respond', 'length', 'max'=>100000),
			array('f_form_thema', 'length', 'max'=>2),
			array('f_user_ip', 'length', 'max'=>15),
						
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('f_id, f_date, f_field_types, f_field_vals, f_form_state, f_form_name, f_field_names, f_field_names, f_admins, f_type, f_form_respond, f_form_thema, f_user_ip', 'safe', 'on'=>'search'),
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
	public function selectValues() {
		return array(
			'f_form_thema'		=> array('DoneVals', array(				
				'0'=>(Yii::t('main', 'Not selected')), 
				'1'=>(Yii::t('main', 'Brilliants')), 
				'2'=>(Yii::t('main', 'Negative ask')), 
				'3'=>(Yii::t('main', 'Zalog (techniks)')), 
				'4'=>(Yii::t('main', 'Zalog (juvelirs)')), 
				'5'=>(Yii::t('main', 'Inkasassion')), 
				'6'=>(Yii::t('main', 'Network contacts')), 
				'7'=>(Yii::t('main', 'Techniks buyment')), 
				'8'=>(Yii::t('main', 'Juvelirs buyment')), 
				'9'=>(Yii::t('main', 'Regular customer')), 
				'10'=>(Yii::t('main', 'Overdue')), 
				'11'=>(Yii::t('main', 'Another')), 
				'12'=>(Yii::t('main', 'Silver')), 
				'13'=>(Yii::t('main', 'Employee')), 
				'14'=>(Yii::t('main', 'Credit conditions')), 
				'15'=>(Yii::t('main', 'Loss of contract')),
			)),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'f_id'			=> 'F',
			'f_date'		=> Yii::t('main', 'date of form'),
			'f_field_types' => 'F Field Types',
			'f_field_vals'	=> 'F Field Vals',
			'f_form_state'	=> Yii::t('main', 'state of form'),
			'f_field_names'	=> 'F Field Names',
			'f_admins'		=> 'F Admins',
			'f_type'		=> 'F type',
			'f_form_respond'=> Yii::t('main', 'Manager answer'),
			'f_form_thema'	=> Yii::t('main', 'Form thema'),
			'f_user_ip'		=> Yii::t('main', 'Ip adress'),
		);
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

		$criteria->compare('f_id',$this->f_id);
		$criteria->compare('f_date',$this->f_date,true);
		$criteria->compare('f_field_types',$this->f_field_types,true);
		$criteria->compare('f_form_name',$this->f_form_name,true);
		$criteria->compare('f_field_vals',$this->f_field_vals,true);
		$criteria->compare('f_form_state',$this->f_form_state,true);
		$criteria->compare('f_field_names',$this->f_field_names,true);
		$criteria->compare('f_admins',$this->f_admins,true);
		$criteria->compare('f_type',$this->f_type,true);	
		$criteria->compare('f_form_respond',$this->f_form_respond,true);	
		$criteria->compare('f_form_thema',$this->f_form_thema,true);		
		$criteria->compare('f_user_ip',$this->f_user_ip,true);	

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FormsSendedData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
?>
