<?php

class LongcreditcalcForm extends Formlabel
{
    public $price;
    public $firstPercent;
    public $summ;
    public $srok = null;
    public $_firstdayminpay = null;
    public $_percentstage = null;
    public $cred_type = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('summ, srok, _firstdayminpay, _percentstage, cred_type ', 'required'),
            array('srok, firstPercent', 'numerical', 'integerOnly'=>true),
            array('price, summ, _firstdayminpay, _percentstage', 'numerical'),
            array('cred_type', 'safe', 'on'=>'search'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'price'		        => (Yii::t('site', 'Price of goods')),
            'firstPercent'		=> (Yii::t('site', 'Percent of the first payment')),
            'summ'		        => (Yii::t('site', 'Summ of credit')),
            'srok'		        => (Yii::t('site', 'Srok')),
            '_firstdayminpay'   => (Yii::t('site', 'First min pay')),
            '_percentstage'     => (Yii::t('site', 'Percent stage')),
            'cred_type'         => (Yii::t('site', 'Credit type')),
        );
    }

    public function selectValues() {
        return array(
            'cred_type'			=> array('DoneVals', array('annuitet'=>(Yii::t('site', 'annuitet')), 'partspay'=>(Yii::t('site', 'partspay')), )),
            'firstPercent'		=> array('DoneVals', array('10'=>'10%', '20'=>'20%')),
        );
    }

}
