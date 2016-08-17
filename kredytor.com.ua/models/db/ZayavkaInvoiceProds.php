<?php

/**
 * This is the model class for table "zayavka_invoice_prods".
 *
 * The followings are the available columns in table 'zayavka_invoice_prods':
 * @property string $prod_id
 * @property string $prod_iid
 * @property string $prod_article
 * @property string $prod_name
 * @property double $prod_price
 * @property double $prod_firstpay_rec
 * @property double $prod_firstpay_real
 * @property double $prod_cred_summ
 * @property string $prod_type
 */
class ZayavkaInvoiceProds extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zayavka_invoice_prods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prod_iid, prod_article, prod_name, prod_price, prod_firstpay_rec', 'required'),
			array('prod_price, prod_firstpay_rec, prod_firstpay_real, prod_cred_summ, prod_count', 'numerical'),
			array('prod_iid', 'length', 'max'=>11),
			array('prod_article, prod_name', 'length', 'max'=>200),
			array('prod_type', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prod_id, prod_iid, prod_article, prod_name, prod_price, prod_firstpay_rec, prod_firstpay_real, prod_cred_summ, prod_count, prod_type', 'safe', 'on'=>'search'),
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
			'prod_type'	=> array( 'DoneVals', array('1'=>'техника', '2'=>'ювелирка',) ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prod_id' => 'id товара',
			'prod_iid' => 'id счет-фактуры',
			'prod_article' => 'артикул товара',
			'prod_name' => 'название товара',
			'prod_count'=> 'Кол-во товара',
			'prod_price' => 'цена товара',
			'prod_firstpay_rec' => 'Рекомендованный п.в.',
			'prod_firstpay_real' => 'Первый знос',
			'prod_cred_summ' => 'Сумма кредита по товару',
			'prod_type'		=> 'Тип товара',
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

		$criteria->compare('prod_id',$this->prod_id,true);
		$criteria->compare('prod_iid',$this->prod_iid,true);
		$criteria->compare('prod_article',$this->prod_article,true);
		$criteria->compare('prod_name',$this->prod_name,true);
		$criteria->compare('prod_count',$this->prod_count);
		$criteria->compare('prod_price',$this->prod_price);
		$criteria->compare('prod_firstpay_rec',$this->prod_firstpay_rec);
		$criteria->compare('prod_firstpay_real',$this->prod_firstpay_real);
		$criteria->compare('prod_cred_summ',$this->prod_cred_summ);
		$criteria->compare('prod_type',$this->prod_type);
		
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ZayavkaInvoiceProds the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
