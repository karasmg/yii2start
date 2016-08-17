<?php

/**
 * PassrecoveryForm class.
 * PassrecoveryForm is the data structure for keeping
 * user Passrecovery form data. It is used by the 'Passrecovery' action of 'SiteController'.
 */
class CashPaymentForm extends Formlabel
{
	public $summ;
	public $dog_num = null;
    public $dogovor = null;
	public $date = null;

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
			array('summ, date, dog_num', 'required'),
			array('dog_num', 'numerical', 'integerOnly'=>true),
			array('summ', 'numerical'),
			array('summ', 'check_summ'),
			array('date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'summ'		=> (Yii::t('site', 'Summ')),
			'date'		=> (Yii::t('site', 'Date')),
		);
	}

	public function check_summ($attribute, $params)
	{
        $pay_sum = $this->summ;
        $this->dogovor = Dogovor::model()->find('d_id = '.$this->dog_num);
        if ( empty($this->dogovor) ) {
            $this->addError($attribute, 'Не найден договор');
            return false;
        }
        $dogovor = $this->dogovor;
        if( !preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $this->date) ) {
            $this->addError($attribute, 'Ошибка в дате.');
            return false;
        }

        $dateToday = date('Y-m-d H:i:s', strtotime($this->date));
        $min_sum = $dogovor->minimalPayment();
        $max_sum = $dogovor->countTotalSumm($dateToday);
        if ( !$max_sum ) {
            $this->addError($attribute, 'По данному договору нет задолженности');
            return false;
        }

        if ( !($min_sum <= $pay_sum) ) {
            $this->addError($attribute, 'Указана сумма меньше минимально возможной');
            return false;
        }

        if ( !($pay_sum  <= $max_sum) ) {
            $this->addError($attribute, 'Указана сумма больше максимально возможной');
            return false;
        }

        return true;
    }

}
