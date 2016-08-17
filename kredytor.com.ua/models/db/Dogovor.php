<?php

/**
 * This is the model class for table "dogovor".
 *
 * The followings are the available columns in table 'dogovor':
 * @property string $d_id
 * @property string $d_zid
 * @property string $d_aid
 * @property string $d_version
 * @property string $d_lo
 * @property string $d_date_start
 * @property string $d_term
 * @property double $d_summ
 * @property double $d_percentstage
 * @property double $d_penystage
 * @property integer $d_panydaystart
 * @property double $d_firstdayminpay
 * @property integer $d_termmodifier
 * @property integer $d_firstdayPayed
 * @property string $d_iid
 * @property double $d_boffer
 * @property string $calc_type
 * @property double $firstMinPay
 * @property double $d_PerriodPayment
 * @property string $d_PaymentDay
 * @property integer $d_PenyDaysPayed
 */
class Dogovor extends CActiveRecord
{
	public $_math			= null;
	public $d_date_zalog	= null;
	public $d_date_vikup	= null;

	public function __construct($scenario = 'insert') {
		parent::__construct($scenario);
		$this->_math = new MathHelper($this);
		if ( $scenario == 'insert' )
			$this->setMathParams();
	}

	public function afterFind() {
		parent::afterFind();
		if ( $this->d_date_start && $this->d_term ) {
			$date = explode(' ', $this->d_date_start);
			$this->d_date_zalog = strtotime($date[0]);
			$this->d_date_vikup = $this->_math->getVikupDate($date[0]);
			//$this->d_date_zalog + ($this->d_term*60*60*24) + ($this->d_termmodifier*60*60*24);
		}
		$this->_math = new MathHelper($this);
		$this->setMathParams();
	}

	public function setMathParams() {
		foreach ( $this->_math->_calc->_creditParams as $paramName=>$paramValue ) {
			if ( !$this->hasAttribute(('d'.$paramName)) )
				continue;
			if ( !is_null($this->{'d'.$paramName}) )
				$this->_math->_calc->{$paramName} = $this->{'d'.$paramName};
			else
				$this->{'d'.$paramName} = $paramValue;
		}
		if ( $this->d_date_start && $this->d_term ) {
			$date = explode(' ', $this->d_date_start);
			$this->d_date_zalog = strtotime($date[0]);
			$this->d_date_vikup = $this->_math->getVikupDate($date[0]);
		}
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dogovor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('d_zid, d_aid, d_lo, d_date_start, d_term, d_summ, d_percentstage, d_penystage, d_panydaystart, calc_type', 'required'),
			array('d_panydaystart, d_termmodifier, d_firstdayPayed, d_PenyDaysPayed', 'numerical', 'integerOnly'=>true),
			array('d_summ, d_percentstage, d_penystage, d_firstdayminpay, d_boffer, firstMinPay, d_PerriodPayment', 'numerical'),
			array('d_zid, d_aid, d_iid', 'length', 'max'=>11),
			array('d_version', 'length', 'max'=>3),
			array('d_lo', 'length', 'max'=>4),
			array('d_term', 'length', 'max'=>5),
			array('d_PaymentDay', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('d_id, d_zid, d_aid, d_version, d_lo, d_date_start, d_term, d_summ, d_percentstage, d_penystage, d_panydaystart, d_firstdayminpay, d_termmodifier, d_firstdayPayed, d_iid, d_boffer, calc_type, firstMinPay, d_PerriodPayment, d_PaymentDay', 'safe', 'on'=>'search'),
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
				'd_zid'=>array(self::BELONGS_TO, 'zayavka', array('d_zid'=>'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'd_id'				=> 'id договора',
			'd_zid'				=> 'id заявки',
			'd_aid'				=> 'id пользователя',
			'd_version'			=> 'Версия договора',
			'd_lo'				=> 'Отделение хранения договора',
			'd_date_start'		=> 'дата начала договора',
			'd_term'			=> 'срок в днях',
			'd_summ'			=> 'Сумма кредита',
			'd_percentstage'	=> 'Процентная ставка',
			'd_penystage'		=> 'Процент пени',
			'd_panydaystart'	=> 'Кол-во дней без пени',
			'd_firstdayminpay'	=> 'Минимальный платеж за первый день',
			'd_termmodifier'	=> 'модификатор срока',
			'd_firstdayPayed'	=> 'был ли оплачен первый день',
			'd_iid'				=> 'id инвойса',
			'd_boffer'			=> 'Платежный буффер',
			'd_PerriodPayment'	=> 'Оплата за текущий перриод',
			'd_PaymentDay'		=> 'дата регулярного платежа',
			'd_PenyDaysPayed'	=> 'оплачено дней пени',
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

		$criteria->compare('d_id',$this->d_id,true);
		$criteria->compare('d_zid',$this->d_zid,true);
		$criteria->compare('d_aid',$this->d_aid,true);
		$criteria->compare('d_version',$this->d_version,true);
		$criteria->compare('d_lo',$this->d_lo,true);
		$criteria->compare('d_date_start',$this->d_date_start,true);
		$criteria->compare('d_term',$this->d_term,true);
		$criteria->compare('d_summ',$this->d_summ,true);
		$criteria->compare('d_percentstage',$this->d_percentstage,true);
		$criteria->compare('d_penystage',$this->d_penystage,true);
		$criteria->compare('d_panydaystart',$this->d_panydaystart,true);
		$criteria->compare('d_firstdayminpay',$this->d_firstdayminpay,true);
		$criteria->compare('d_termmodifier',$this->d_termmodifier,true);
		$criteria->compare('d_firstdayPayed',$this->d_firstdayPayed,true);
		$criteria->compare('d_iid',$this->d_iid,true);
		$criteria->compare('d_boffer',$this->d_boffer,true);
		$criteria->compare('d_PerriodPayment',$this->d_PerriodPayment,true);
		$criteria->compare('d_PaymentDay',$this->d_PerriodPayment,true);
		$criteria->compare('d_PenyDaysPayed',$this->d_PenyDaysPayed,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dogovor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function countPercentSumm($today=false) {
		if ( $today ) {
			$days_in_use = $this->countDaysInUse($today);
		} else {
			$days_in_use = false;
		}
		return $this->_math->countPercentSumm(false, $days_in_use);
	}
	public function countPenySumm($today=false) {
		if ( $today ) {
			$days_peny = $this->countDaysPeny($today);
		} else {
			$days_peny = false;
		}
		return $this->_math->countPenySumm(false, $days_peny);
	}
	public function countTotalSumm($today=false) {
		if ( $today ) {
			$days_in_use	= $this->countDaysInUse($today);
			$days_peny		= $this->countDaysPeny($today);
		} else {
			$days_in_use	= false;
			$days_peny		= false;
		}
		return $this->_math->countTotalSumm(false, $days_in_use, $days_peny);
	}

	public function countDaysInUse($today=false) {
		$days_in_use = $this->_math->countDaysInUse($today);
		if ( $days_in_use === false ) {
			die('Pay date error');
		}
		return $days_in_use;
	}
	public function countDaysPeny($today=false) {
		$days_peny = $this->_math->countDaysPeny($today);
		if ( $days_peny === false ) {
			die('Pay date error');
		}
		return $days_peny;
	}

	public function minimalPayment() {
		$minPercent = $this->_math->minimalPayment();
		return $minPercent;
	}

	public function preparePayment($summ, $pay_day, $discount=0) {
		$days_in_use	= $this->countDaysInUse($pay_day);
		$days_peny		= $this->countDaysPeny($pay_day);
		return $this->_math->calculatePayment(($summ+$this->d_boffer), false, $days_in_use, $days_peny, $discount);
	}
	public function makePayment($summ, $pay_day, $paysys, $placement=null, $invoiceState='new', $apprcode=null, $discount=0, $presavedInvoice = false, $comission=false) {
        $zayav = Zayavka::model()->findByPk($this->d_zid);
        $message = array(
            'dog'		=> $this->d_id,
            'summ'		=> $summ,
            'pay_day'	=> $pay_day,
            'Zayavka'	=> $this->d_zid,
        );
        if ( is_null($zayav) || $zayav->state != 5 ) {
            $message['reason'] = 'Zayavka->state != 5';
            $this->notifyAboutErrorpay($message);
            return false;
        }
        $payment_data = $this->preparePayment($summ, $pay_day, $discount);
        if ( !$payment_data ) {
            $message['reason'] = 'No payment data';
            $this->notifyAboutErrorpay($message);
            return false;
        }
		$result = true;
		$lastpaymentByThisDog = Yii::app()->db->createCommand()->
			select('max(i_pay_day) as last_pay')->
			from('invoices')->
			where('i_id IN (SELECT d_iid FROM dogovor WHERE d_zid = '.(int)$this->d_zid.')')->
			queryRow();
		if ( !empty($lastpaymentByThisDog) && $lastpaymentByThisDog['last_pay'] ) {
			$last_pay = strtotime(date('Y-m-d', strtotime($lastpaymentByThisDog['last_pay'])));
			$this_pay = strtotime($pay_day);
			if ( !$this_pay )
				$this_pay = time();
			if ( $last_pay > $this_pay ) {
				$this->notifyAboutErrorpay( array_merge($message, array(
					'text'		=> 'Payments Cross payment Error',
					'this_pay'	=> date('d.m.Y H:i:s', $this_pay),
					'last_pay'	=> date('d.m.Y H:i:s', $last_pay),
				)));
				return false;
			}
		}
		$new_dog = new Dogovor;
		$new_dog->attributes =		$this->attributes;
		$new_dog->d_version++;
		$new_dog->d_summ-=			$payment_data['body'];
		$new_dog->d_boffer=			$payment_data['buffer'];
		$new_dog->d_term=			$payment_data['srok'];
		$new_dog->d_PerriodPayment=	$payment_data['PerriodPayment'];
		$new_dog->firstMinPay=		$payment_data['firstMinPay'];
		$new_dog->d_PaymentDay=		$payment_data['PaymentDay'];
		$new_dog->d_PenyDaysPayed=	$payment_data['PenyDaysPayed'];
		$new_dog->d_date_start=		date('Y-m-d H:i:s', ($this->d_date_zalog+($payment_data['days_prol']*60*60*24)));
		if ( !$this->d_firstdayPayed && $payment_data['days_prol'] > 0 )
			$new_dog->d_firstdayPayed = 1;

		$result = ($result && $new_dog->validate());

		if ( $result ) {
			if ( $presavedInvoice ) {
				$invoice = Invoices::model()->findByPk((int)$presavedInvoice);
			} else {
				$invoice = new Invoices;
				$invoice->i_date_create = new CDbExpression('NOW()');
				$invoice->i_dognumb		= $this->d_id;
			}
			$invoice->i_uid			= $new_dog->d_aid;
			$invoice->i_pay_day		= date('Y-m-d H:i:s', strtotime($pay_day));
			if ( $invoiceState == 'paid' || $invoiceState == 'prepaid' )
				$invoice->i_date_prepaid = date('Y-m-d H:i:s', strtotime($pay_day));
			if ( $invoiceState == 'paid' )
				$invoice->i_date_paid = date('Y-m-d H:i:s', strtotime($pay_day));
			if ( $comission ) {
				$invoice->i_commision = $comission;
			}
			$invoice->i_summ		= $summ;
			$invoice->i_status		= $invoiceState;
			$invoice->i_paysys		= $paysys;
			$invoice->i_apprcode	= $apprcode;
			$invoice->i_placement	= $placement;
			$invoice->i_percent		= $payment_data['percent'];
			$invoice->i_peny		= $payment_data['peny'];
			$invoice->i_body		= $payment_data['body'];
			$invoice->i_buffer		= $payment_data['buffer'];
			$invoice->i_days_prol	= $payment_data['days_prol'];
			$invoice->i_buffer_user	= $payment_data['buffer_user'];
			$result = ($result && $invoice->validate() && $invoice->save() );
			if ( $result ) {
				$new_dog->d_iid = $invoice->i_id;
				$payment_data['invoice'] = $invoice->i_id;
				$result = ($result && $new_dog->save() );
			}

			if ( $new_dog->d_summ == 0 ) {
				$zayavka = Zayavka::model()->findByPk($new_dog->d_zid);
				Yii::app()->params['applicationWorkType'] = 'console';
				if ( !is_null($zayavka) ) {
					$zayavka->state		= 6;
					$zayavka->highlight	= 0;
					$zayavka->save();

					if ( $payment_data['buffer_user'] ) {
						$anketa = Anketa::model()->find('iid='.$zayavka->iid);
						$anketa->buffer_user+=$payment_data['buffer_user'];
						$anketa->save();
					}
				}
			}
		}
		if ( $result )
			return $payment_data;

		$this->notifyAboutErrorpay($message);
		return $result;
	}

	public function notifyAboutErrorpay($data, $log = null, $arrays=array()) {
		$message="\n";
		if ( !is_array($data) )
			$data = array(
				'dog'		=> $this->d_id,
				'message'	=> $data
			);
		foreach ( $data as $key=>$val ) {
			$message.= $key.": ".$val."\n";
		}
		if ( !empty($arrays) ) {
			$message.="\n Pay Datas:\n\n";
			foreach ( $arrays as $key=>$vals ) {
				$message.="Data $key:\n";
				foreach ( $vals as $subkey=>$subval )
					$message.="$subkey => $subval\n";
			}
		}
		Yii::log($message, "findtrace", "ErrorPayment!");
	}

	public function getPaymentsByDog() {
		if ( empty($this->d_zid) )
			return false;
		$payments = Yii::app()->db->createCommand()
			->select('i.*')
			->from('invoices i')
			->where('i.i_id IN (SELECT d_iid FROM dogovor WHERE d_zid = "'.$this->d_zid.'")')
			->order('i.i_pay_day ASC')
			->queryAll();
		return $payments;
	}

	public function createIdCS() {
		if ( empty($this->d_zid) )
			return false;
		$dogNumb = Yii::app()->db->createCommand()
			->select('zayavkaNumb')
			->from('zayavka')
			->where('id = "'.$this->d_zid.'"')
			->queryAll();
		if ( empty($dogNumb) )
			return false;

		$id = str_replace('-', '', $dogNumb[0]['zayavkaNumb']);
		$sr = substr($id, strlen($id)-1);
		$result = $id;
		for ($i = 0, $c = strlen($result); $i < $c; $i++) {
			$sr++;
			if ($sr > 9) {
				$sr = 0;
			}
			$l = $result[$i]+$sr;
			$result[$i] = $l > 9 ? substr($l, 1, 1) : $l;
		}
		$cs =  $sr.$result;

		return array(
			'id'	=> $id,
			'cs'	=> $cs,
		);
	}

	public function dognumbGet() {
		if ( empty($this->d_zid) )
			return false;
		$dogNumb = Yii::app()->db->createCommand()
			->select('zayavkaNumb')
			->from('zayavka')
			->where('id = "'.$this->d_zid.'"')
			->queryAll();
		if ( empty($dogNumb) )
			return false;
		return $dogNumb[0]['zayavkaNumb'];
	}

	public function prepareInvoice($summ, $pay_day, $paysys, $placement=null, $invoiceState='new', $apprcode=null, $discount=0, $comission=false) {
		$payment_data = $this->preparePayment($summ, $pay_day, $discount);
		$invoice = new Invoices;
		$invoice->i_date_create = new CDbExpression('NOW()');
		$invoice->i_pay_day		= date('Y-m-d H:i:s', strtotime($pay_day));
		$invoice->i_summ		= $summ;
		$invoice->i_status		= $invoiceState;
		$invoice->i_uid			= $this->d_aid;
		$invoice->i_paysys		= $paysys;
		$invoice->i_apprcode	= $apprcode;
		$invoice->i_placement	= $placement;
		$invoice->i_percent		= $payment_data['percent'];
		$invoice->i_peny		= $payment_data['peny'];
		$invoice->i_body		= $payment_data['body'];
		$invoice->i_buffer		= $payment_data['buffer'];
		$invoice->i_days_prol	= $payment_data['days_prol'];
		$invoice->i_buffer_user	= $payment_data['buffer_user'];
		$invoice->i_dognumb		= $this->d_id;
		if ( $comission ) {
			$invoice->i_commision = $comission;
		}
		$result = ( $invoice->validate() && $invoice->save() );
		if ( $result ) {
			return $invoice->i_id;
		} else {
			return false;
		}
	}
}
