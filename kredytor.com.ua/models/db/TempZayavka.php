<?php

/**
 * This is the model class for table "temp_zayavka".
 *
 * The followings are the available columns in table 'temp_zayavka':
 * @property string $t_id
 * @property string $t_uid
 * @property string $t_iid
 * @property string $t_summ
 * @property string $t_manager
 * @property string $t_srok
 * @property string $t_card
 * @property string $t_calc_type
 * @property double $t_firstMinPay
 * @property double $t_siteCreated
 * @property string $t_zayavka_inv_numb
 * @property string $session
 * *
 */
class TempZayavka extends CActiveRecord
{
	public $_zayavka_invoice	= null;
	public $_prev_fp			= 0;
	public $_prev_summ			= 0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'temp_zayavka';
	}
	


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('t_summ', 'numerical', 'integerOnly'=>false),
            array('t_srok', 'numerical', 'integerOnly'=>true, 'min'=>1),
            array('t_id, t_iid, t_summ, t_manager, t_srok, dateStart, t_credit_targeted, t_summ_fp,  t_calc_type, t_firstMinPay, t_siteCreated, t__money_type, session', 'safe', 'on'=>'search'),
            array('dateStart', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>true,'on'=>'insert'),
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
			't_credit_for'		=> array('DoneVals', array('1'=>Yii::t('site', 'On the personal needs'), '2'=>Yii::t('site', 'For family'), '3'=>Yii::t('site', 'Other objects') )),
			't_credit_targeted'	=> array('DoneVals', array('0'=>Yii::t('site', 'Not targeted'), '1'=>Yii::t('site', 'Targeted') )),
			't_calc_type'			=> array('DoneVals', array('dayly'=>Yii::t('site', 'Dayly % adding'), 'annuitet'=>Yii::t('site', 'Annuity'), 'partspay'=>Yii::t('site', 'PayByParts'), 'differntial'=>Yii::t('site', 'Graded') )),
            't__money_type'       => array('DoneVals', array('cash'=>Yii::t('site', 'Cash'), 'online'=>Yii::t('site', 'On card') ))
		);
	}

	public function attributeLabels()
	{
		return array(
			't_id'				=> (Yii::t('main', 'Row Id')),
			't_uid'				=> (Yii::t('main', 'Outer key')),
            't_iid'				=> (Yii::t('main', 'Iin')),
			't_credit_targeted'	=> (Yii::t('main', 'Credit type')),
			't_summ_fp'			=> (Yii::t('main', 'Credit fp summ')),
			't_summ'			=> (Yii::t('main', 'Credit sum')),
			't_manager'			=> (Yii::t('main', 'Manager')),
            't__money_type'		=> (Yii::t('main', 'Money type')),
			't_srok'			=> (Yii::t('main', 'Credit term')),
			't_calc_type'		=> (Yii::t('main', 'Credit calculation type')),
			't_firstMinPay'		=> (Yii::t('main', 'Calculated payment')),
			't_card'			=> (Yii::t('site', 'Card number')),
			't_credit_for'		=> (Yii::t('main', 'Credit For')),
			'dateStart'		    => 'Create date',
			't_siteCreated'		=> 'Заявка создана через сайт',
			't_issueDate'		=> 'Дата показа отказа',
            't_zayavka_inv_numb'=> 'Номер счета-фактуры',
            'external_css'      => 'Внешний CSS',
            'session'         => 'session id',
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			't_id'						=> 'HiddenField',
			't_uid'						=> 'DisabledText',
            't_iid'						=> 'TextField',
			't_credit_targeted'			=> 'DropDownList',
			't_summ_fp'					=> 'TextField',
			't_summ'					=> 'TextField',
            't_manager'					=> 'HiddenField',
			't_srok'					=> 'TextField',
			't_calc_type'				=> 'DropDownList',
			't_firstMinPay'				=> 'DisabledText',
			't_credit_for'				=> 'DropDownList',
            't__money_type'				=> 'DropDownList',
			'dateStart'				    => 'DisabledText',
			't_card'					=> 'HiddenField',
			't_siteCreated'				=> 'DisabledText',
            't_zayavka_inv_numb'        => 'TextField',
            'external_css'              => 'TextField',
            'session'                 => 'TextField',
		);
		
		if ( isset($fields[$asked_field]) ) return $fields[$asked_field];
		else return 'TextField';
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t_id',$this->t_id,true);
		$criteria->compare('t_uid',$this->t_uid,true);
        $criteria->compare('t_iid',$this->t_iid,true);
		$criteria->compare('t_credit_targeted',$this->t_credit_targeted,true);
		$criteria->compare('t_summ_fp',$this->t_summ_fp,true);
		$criteria->compare('t_summ',$this->t_summ,true);
		$criteria->compare('t_manager',$this->t_manager,true);
		$criteria->compare('t_srok',$this->t_srok,true);
		$criteria->compare('dateStart',$this->dateStart,true);
		$criteria->compare('t_credit_for',$this->t_credit_for,true);
		$criteria->compare('t_card',$this->t_card,true);
		$criteria->compare('t_calc_type',$this->t_calc_type,true);
		$criteria->compare('t_firstMinPay',$this->t_firstMinPay,true);
		$criteria->compare('t_siteCreated',$this->t_siteCreated,true);
        $criteria->compare('t_zayavka_inv_numb',$this->t_zayavka_inv_numb,true);
        $criteria->compare('external_css',$this->external_css,true);
        $criteria->compare('session',$this->session,true);



		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	


	public function beforeSave() {

		return parent::beforeSave();
	}
	
	public function afterSave() {

		return parent::afterSave();
	}
	
}
