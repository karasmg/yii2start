<?php

/**
 * This is the model class for table "zayavka".
 *
 * The followings are the available columns in table 'zayavka':
 * @property string $id
 * @property string $iid
 * @property string $summ
 * @property string $manager
 * @property string $srok
 * @property string $card
 * @property string $calc_type
 * @property double $firstMinPay
 * @property string $zayavkaNumb
 * @property string $dogNumb
 * @property string $uidZayavki
 * @property string $uidKlienta
 * @property double $percentstage
 * @property double $penystage
 * @property double $siteCreated
 * @property double $issueDate
 * * 
 */
class Zayavka extends CActiveRecord
{
	public $_zayavka_invoice	= null;
	public $_prev_fp			= 0;
	public $_prev_summ			= 0;
	public $_money_type;
	public $_prev_state			= null;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zayavka';
	}
	
	public function afterFind() {
		parent::afterFind();
		$this->_prev_fp = $this->summ_fp;
		$this->_prev_summ = $this->summ;
		$invoice = Yii::app()->db->createCommand('SELECT id FROM `zayavka_invoice` WHERE zid='.$this->id)->queryRow();
		if ( !is_null($invoice) )
			$this->_zayavka_invoice = $invoice['id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iid, summ, srok, credit_for, calc_type', 'required'),
			array('_money_type', 'moneyType','on'=>'frontendInsert'),
			array('iid, summ', 'length', 'max'=>10),
			array('manager, dogNumb, zayavkaNumb', 'length', 'max'=>255),
			array('uidZayavki, uidKlienta', 'length', 'max'=>400),
			//array('summ', 'numerical', 'integerOnly'=>false, 'min'=>500),
			array('srok', 'checkSrok'),
			array('card', 'numerical', 'integerOnly'=>true),
			array('firstMinPay, percentstage', 'numerical', 'integerOnly'=>false),
			array('state, highlight, credit_targeted, siteCreated', 'length', 'max'=>1),
			array('highlight', 'default', 'value'=>0, 'setOnEmpty'=>false,'on'=>'insert, frontendInsert'),
			array('percentstage', 'setCheckPercentstage'),
			array('penystage', 'setPenyStage'),
			array('summPercent', 'countsummPercent'),
			array('iid', 'checkIfFirstToday'),
			array('summ_fp', 'checkIfTargetedPaymentCalculation'),
			array('firstMinPay', 'calculatefirstMinPay'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, iid, summ, manager, srok, state, highlight, dateStart, summPercent, credit_targeted, summ_fp, dogNumb, zayavkaNumb, uidZayavki, uidKlienta, calc_type, firstMinPay, percentstage, penystage, issueDate, siteCreated, _money_type', 'safe', 'on'=>'search'),
			array('dateStart', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>true,'on'=>'insert, frontendInsert'),
			array('issueDate', 'default', 'value'=>new CDbExpression('NULL'), 'setOnEmpty'=>true,'on'=>'insert, frontendInsert'),
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
		$_money_type = array('DoneVals', array('cash'=>Yii::t('site', 'Cash'), 'online'=>Yii::t('site', 'On card') ));
		/*if ( $_SERVER['REMOTE_ADDR'] == '46.182.81.50' || $_SERVER['REMOTE_ADDR'] == '83.99.145.9' ) {
		//	$_money_type = array('DoneVals', array('cash'=>Yii::t('site', 'Cash'), 'online'=>Yii::t('site', 'On card') ));
		}*/
		return array(
			'state'	=> array('DoneVals', array('0'=>'new', '1'=>'in_progress', '2'=>'edited', '3'=>'active', '4'=>'canseled', '5'=>'gived', '6'=>'closed_successs', '7'=>'troubles' )),
			//Статус заявки. 0-новая, 1-в ожидании, 2-отредактирована, 3-активна, 4-отклонена, 5-выдана, 6-закрыт договор успешно, 7-проблемы при регистрации
			'credit_for'		=> array('DoneVals', array('1'=>Yii::t('site', 'On the personal needs'), '2'=>Yii::t('site', 'For family'), '3'=>Yii::t('site', 'Other objects') )),
			'credit_targeted'	=> array('DoneVals', array('0'=>Yii::t('site', 'Not targeted'), '1'=>Yii::t('site', 'Targeted') )),
			'calc_type'			=> array('DoneVals', array('dayly'=>Yii::t('site', 'Dayly % adding'), 'annuitet'=>Yii::t('site', 'Annuity'), 'partspay'=>Yii::t('site', 'PayByParts'), 'differntial'=>Yii::t('site', 'Graded') )),
            '_money_type'       => $_money_type,
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'				=> (Yii::t('main', 'Row Id')),
			'iid'				=> (Yii::t('main', 'Outer key')),
			'credit_targeted'	=> (Yii::t('main', 'Credit type')),
			'summ_fp'			=> (Yii::t('main', 'Credit fp summ')),
			'summ'				=> (Yii::t('main', 'Credit sum')),
			'manager'			=> (Yii::t('main', 'Manager')),
            '_money_type'		=> (Yii::t('main', 'Money type')),
			'srok'				=> (Yii::t('main', 'Credit term')),
			'calc_type'			=> (Yii::t('main', 'Credit calculation type')),
			'firstMinPay'		=> 'Очередной платеж',
			'state'				=> (Yii::t('site', 'State')),
			'card'				=> (Yii::t('site', 'Card number')),
			'summPercent'		=> (Yii::t('main', 'summPercent')),
			'credit_for'		=> (Yii::t('main', 'Credit For')),
			'percentstage'		=> (Yii::t('main', 'summPercent2')),
			'penystage'			=> (Yii::t('main', 'penyStage')),
			'highlight'			=> 'Highlitgh line',
			'dateStart'			=> 'Create date',
			'dogNumb'			=> 'Номер договора в 1С',
			'zayavkaNumb'		=> 'Номер заявки в 1С',
			'uidZayavki'		=> 'id заявки в 1С',
			'uidKlienta'		=> 'id клиента в 1С',
			'siteCreated'		=> 'Заявка создана через сайт',
			'issueDate'			=> 'Дата показа отказа',
		);
	}
	
	public function fieldtypes ( $asked_field ) 
	{
		$fields =  array (
			'id'						=> 'HiddenField',
			'iid'						=> 'DisabledText',
			'credit_targeted'			=> 'DropDownList',
			'summ_fp'					=> 'TextField',
			'summ'						=> 'TextField',
			'manager'					=> 'HiddenField',
			'srok'						=> 'TextField',
			'calc_type'					=> 'DropDownList',
			'firstMinPay'				=> 'DisabledText',
			'credit_for'				=> 'DropDownList',
            '_money_type'				=> 'DropDownList',
			'percentstage'				=> 'TextField',
			'summPercent'				=> 'DisabledText',
			'state'						=> 'DisabledField',
			'highlight'					=> 'HiddenField',
			'dateStart'					=> 'DisabledText',
			'dogNumb'					=> 'DisabledText',
			'zayavkaNumb'				=> 'DisabledText',
			'uidZayavki'				=> 'HiddenField',
			'uidKlienta'				=> 'HiddenField',
			'penystage'					=> 'DisabledText',
			'card'						=> 'HiddenField',
			'siteCreated'				=> 'DisabledText',
			'issueDate'					=> 'HiddenField',
		);
		
		if ( isset($fields[$asked_field]) ) return $fields[$asked_field];
		else return 'TextField';
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('iid',$this->iid,true);
		$criteria->compare('credit_targeted',$this->credit_targeted,true);
		$criteria->compare('summ_fp',$this->summ_fp,true);
		$criteria->compare('summ',$this->summ,true);
		$criteria->compare('manager',$this->manager,true);
		$criteria->compare('srok',$this->srok,true);
		$criteria->compare('highlight',$this->highlight,true);	
		$criteria->compare('dateStart',$this->dateStart,true);	
		$criteria->compare('summPercent',$this->summPercent,true);	
		$criteria->compare('credit_for',$this->credit_for,true);	
		$criteria->compare('dogNumb',$this->dogNumb,true);	
		$criteria->compare('zayavkaNumb',$this->zayavkaNumb,true);
		$criteria->compare('uidZayavki',$this->uidZayavki,true);
		$criteria->compare('uidKlienta',$this->uidKlienta,true);
		$criteria->compare('card',$this->card,true);	
		$criteria->compare('calc_type',$this->calc_type,true);	
		$criteria->compare('firstMinPay',$this->firstMinPay,true);	
		$criteria->compare('percentstage',$this->percentstage,true);	
		$criteria->compare('penystage',$this->penystage,true);	
		$criteria->compare('siteCreated',$this->siteCreated,true);	
		$criteria->compare('issueDate',$this->issueDate,true);	
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function setPenyStage($attribute='penystage', $params=null)
	{
		if ( $this->penystage === 0 || $this->penystage === '0' || $this->penystage ) {
			return true;
		}
		$math = new MathHelper($this);
		$this->penystage = $math->_penystage;
		
		if ( $this->penystage ) {
			return true;
		}
		
		$this->addError('percentstage', Yii::t('main', 'Peny stage is incorrect') );
		return false;
	}


	public function checkSrok($attribute='srok', $params=null)
	{
		$math = new MathHelper($this);
		if( $this->srok < $math->_calc->_srok_limits['_minsrok'] ) {
			$this->addError('srok', Yii::t('main', 'srok less then min').' ('.$math->_calc->_srok_limits['_minsrok'].')' );
			return false;
		}
		if( $this->srok > $math->_calc->_srok_limits['_maxsrok'] ) {
			$this->addError('srok', Yii::t('main', 'srok more then max').' ('.$math->_calc->_srok_limits['_maxsrok'].')' );
			return false;
		}
		return true;
	}


	public function setCheckPercentstage($attribute='percentstage', $params=null)
	{
		if ( $this->percentstage === 0 || $this->percentstage === '0' || $this->percentstage ) {
			return true;
		}
		$math = new MathHelper($this);
		$this->percentstage = $math->_percentstage;
		
		if ( $this->percentstage ) {
			return true;
		}
		
		$this->addError('percentstage', Yii::t('main', 'Percent stage is incorrect') );
		return false;
	}
	
	public function countsummPercent($attribute='summPercent', $params=null)
	{
		$math = new MathHelper($this);
		if ( empty($this->percentstage) )
			$this->setCheckPercentstage();
		$math->_percentstage = $this->percentstage; 
		$this->summPercent = $math->countPercentSumm();		
		return $this->summPercent;		
	}
	
	public function calculatefirstMinPay($attribute='firstMinPay', $params=null)
	{
		$math = new MathHelper($this);
		$this->firstMinPay = $math->calculatefirstMinPay();
		return $this->firstMinPay;		
	}
	
	public function checkIfFirstToday($attribute, $params)
	{
		if ( !Yii::app()->params['OnlyOneZayavkaPerDay'] )
			return true;

		$todays = Zayavka::model()->find('iid = '.(int)$this->iid.' AND DATE(dateStart) = "'.date('Y-m-d').'" AND id !='.(int)$this->id);
		if ( is_null($todays)  )
			return true;
		
		$this->addError('iid', Yii::t('main', 'Only one Zayavka per day is allowed') );
		return false;
	}
	
	public function checkIfTargetedPaymentCalculation($attribute, $params)
	{		
		if ( $this->state == 4 ) {
			return true;
		}
		
		if ( $this->credit_targeted == 0 ) {
			if ( $this->summ > 9000 ) {
				$this->addError('summ', Yii::t('main', 'Max value is 9000') );
				return false;
			}
			if ( $this->calc_type != 'dayly' ) {
				$this->addError('calc_type', Yii::t('main', 'Not alloowed for non targeted credit') );
				return false;
			}
			return true;			
		}
		
		if ( !empty($_POST['zayavka_invoice']) )
			$this->_zayavka_invoice = (int)$_POST['zayavka_invoice'];
		
		if ( empty($this->summ_fp) ) {
			$this->addError('summ_fp', Yii::t('main', 'You need to enter value') );
			return false;
		}
		if ( empty($this->_zayavka_invoice) ) {
			$this->addError('credit_targeted', Yii::t('main', 'You need to enter invoice number') );
			return false;
		}
		$invoice = ZayavkaInvoice::model()->find('id = '.$this->_zayavka_invoice.' AND date_annulate IS NULL AND ( zid IS NULL OR zid="'.$this->id.'")');
		if ( is_null($invoice) ) {
			$this->addError('credit_targeted', Yii::t('main', 'Invoice couldn`t be used') );
			return false;
		}
		if ( $this->_prev_fp != $this->summ_fp || $this->_prev_summ != $this->summ ) {
			if ( $this->summ < $this->_prev_summ && $this->_prev_fp == $this->summ_fp )
				$this->summ_fp += $this->_prev_summ-$this->summ;
			$min_fp = $this->get_fp_params();
			if ( $this->summ_fp < $min_fp['prod_firstpay_min'] ) {
				$this->addError('summ_fp', Yii::t('main', 'Minimum value is').' '.$min_fp['prod_firstpay_min']);
				return false;
			}
			$cred_summ = $this->recal_prods_fp($min_fp);
			if ( Yii::app()->params['applicationWorkType'] == 'console' ) {
				$this->summ = $cred_summ;
			} elseif ( ($cred_summ - $this->summ)  ) {
				$this->addError('summ', Yii::t('main', 'Cred summ should be').' '.$cred_summ);
				return false;
			}
		}		
		return true;		
	}
	
	public function beforeSave() {
		$previousData = Zayavka::model()->findByPk($this->id);
		if(!empty($previousData))
			$this->_prev_state = $previousData->state;
		if ( Yii::app()->params['applicationWorkType'] != 'console' ) {
			if ( in_array($this->state, array(1,2,3,4,5,6)) ) {
				$changed = false;
				foreach ( $this->attributes as $key=>$val ) {
					if ( in_array($key, array('highlight', 'dogNumb', 'zayavkaNumb', 'firstMinPay')) )
						continue;
					if ( $this->attributes[$key] != $previousData->attributes[$key] ) {
						if( $key == 'state' && $val == '4' && in_array($previousData->state, array('2', '3')) )
							continue;
						$this->{$key} = $previousData->attributes[$key];
						$changed = true;
						//echo $key.' => '.$this->attributes[$key].'<br>';
					}
				}
				if ( $changed ) {
					$text = 'On current state not possible to edit';
					if ( $this->state == 2 )
						$text = 'You should accept or cansel on this state';
					$this->addError('state', Yii::t('main', $text) );
					return false;
				}
			}

			$inWorkZayavka = Zayavka::model()->count('iid = '.$this->iid.' AND id != '.(int)$this->id.' AND state IN (0, 1, 2, 3, 5)');
			if ( $inWorkZayavka > 0 ) {
				$this->addError('iin', Yii::t('main', 'Curr user have active zayavki now') );
				return false;
			}
		}
		
		$anketa = Anketa::model()->find('iid='.$this->iid);
		if ( !is_null($anketa) ) {
			$anketa->highlight = 0;
			$temp = Yii::app()->params['applicationWorkType'];
			Yii::app()->params['applicationWorkType'] = 'console';
			$anketa->save();
			Yii::app()->params['applicationWorkType'] = $temp;
		}
		return parent::beforeSave();
	}
	
	public function afterSave() {
		if( $this->state !== $this->_prev_state ){
			$state_log = new StateLog();
			$state_log->s_zid = $this->id;
			$state_log->s_state = $this->state;
			$state_log->s_controller = Yii::app()->getController()->id;
			$state_log->s_action = Yii::app()->getController()->getAction()->id;
			$state_log->save();
		}
		$anketa = Anketa::model()->find('iid=:iid', array('iid'=>$this->iid));
		if ( is_null($anketa) )
			return false;
		$anketa->credit_for = $this->credit_for;
		$anketa->srok = $this->srok;
		$anketa->highlight = 0;
		$temp = Yii::app()->params['applicationWorkType'];
		Yii::app()->params['applicationWorkType'] = 'console';
		$anketa->save();
		Yii::app()->params['applicationWorkType'] = $temp;
		if ( is_null($this->_zayavka_invoice) )
			return parent::afterSave();
		$invoice = ZayavkaInvoice::model()->find('id = '.$this->_zayavka_invoice.' AND date_used_inmag IS NULL AND date_annulate IS NULL AND zid IS NULL');
		if ( !is_null($invoice) ) {
			$invoice->zid			= $this->id;
			$invoice->date_to_zayav = new CDbExpression('NOW()');
			$invoice->save();
		}

		static::MakeFreeInvoice( array(array(
				'id'=>$this->id,
				'state'=>$this->state,
				'credit_targeted'=>$this->credit_targeted,
			))
		);
		return parent::afterSave();
	}

	public function afterDelete(){
		static::MakeFreeInvoice( array(array(
				'id'=>$this->id,
				'state'=>$this->state,
				'credit_targeted'=>$this->credit_targeted,
			))
		);
		return parent::afterDelete();
	}
	
	public function getRealdata() {
		$selectVals = $this->selectValues();
		$response = $this->attributes;
		foreach ( $response as $key=>$val ) {
			if ( empty($selectVals[$key]) || in_array($key, array('state', 'percentstage', 'penystage')) ) continue;
			if ( is_array($selectVals[$key]) && $selectVals[$key][0] == 'DoneVals' && !empty($selectVals[$key][1][$val]) ) {
				$response[$key] = $selectVals[$key][1][$val];
			}
		}
		if ( is_null($this->_zayavka_invoice) )
			return $response;
		$invoice = ZayavkaInvoice::model()->find('id = '.$this->_zayavka_invoice.' AND date_annulate IS NULL AND zid="'.$this->id.'"');
		$prods = Yii::app()->db->createCommand('SELECT prod_article, prod_name, prod_count, prod_price, prod_firstpay_rec, prod_firstpay_real, prod_cred_summ, prod_type FROM `zayavka_invoice_prods` WHERE prod_iid='.$this->_zayavka_invoice.' ORDER BY prod_firstpay_rec DESC')->queryAll();
		if ( is_null($invoice) || empty($prods) )
			return $response;
		
		$invoice_params = array(
			'number'	=> $invoice->number,
			'prods'		=> $prods,
		);
		$response = array_merge($response, array('invoice'=>$invoice_params));		
		return $response;
	}
	
	public function get_fp_params() {
		return TempZayavkaHelper::get_fp_params($this->_zayavka_invoice);
	}	
	public function recal_prods_fp($min_fp = false) {
		if ( empty($this->_zayavka_invoice) ) 
			return false;
		if ( !$min_fp )
			$min_fp = $this->get_fp_params();
		$first_pay = $this->summ_fp;
		$missmatch = $this->summ_fp/$min_fp['prod_firstpay_min'];
		$cred_summ = 0;
		$prods = Yii::app()->db->createCommand('SELECT prod_id, prod_count, prod_price, prod_firstpay_rec  FROM `zayavka_invoice_prods` WHERE prod_iid='.$this->_zayavka_invoice.' ORDER BY prod_firstpay_rec DESC')->queryAll();
		for ( $i=0, $total=count($prods); $i<$total; $i++ ) {
			$prod = $prods[$i];  
			$real = round($prod['prod_firstpay_rec']*$missmatch, 2);
			$first_pay -= $real;
			if ( ($i+1) == $total && $first_pay ) {
				$real += round($first_pay, 2);
			}
			$cred_summ_this = $prod['prod_price']-$real;
			//echo $real.' : '.$cred_summ_this.'<br>';
			$cred_summ += $cred_summ_this;
			Yii::app()->db->createCommand('UPDATE`zayavka_invoice_prods` SET prod_firstpay_real = "'.$real.'", prod_cred_summ = "'.$cred_summ_this.'" WHERE prod_id='.$prod['prod_id'])->execute();
		}		
		return round($cred_summ, 2);
	}
	
	public function moneyType($attribute, $params){
        switch ($this->_money_type) {
            case '0':
                $this->addError($attribute, Yii::t('site', 'Fill the field') . ' ' . Yii::t('main', 'Money type'));
                return false;
            case 'cash':
                if (empty($this->manager)) {
                    $this->addError('manager', Yii::t('site', 'Fill the field') . ' ' . Yii::t('main', 'Manager'));
                    return false;
                } else {
                    $this->card = 0;
                    return true;
                }
            case 'online':
                if (empty($this->card)) {
                    $this->addError('card', Yii::t('site', 'Fill the field') . ' ' . Yii::t('site', 'Card number'));
                    return false;
                } else {
                    $card = AnketasCards::model ()->findByPk ( (int)$this->card );
                    if(empty($card)) {
                        $this->addError('card', Yii::t('site', 'Card is NULL') . ' ' . Yii::t('site', 'Card number'));
                        return false;
                    }
                    if($card->card_state==='0') {
                        Yii::app()->controller->redirect('/'.Yii::app()->language.'/personalpage/cards/verification/'.(int)$this->card.'/');
                        return false;
                    }
                    $this->manager = '0000';
                    return true;
                }
        }
        $this->addError($attribute, Yii::t('main', 'Money type'));
        return false;
    }
	
	public function getVikupDate($fromday = false) {
		$math = new MathHelper($this);		
		return $math->getVikupDate($fromday);
	}

	//Освобождаем счета-фактуры перед отменой заявок
	public static function MakeFreeInvoice($invoices) {
	$idInvoices = array();
	if ( empty($invoices) || !is_array($invoices) )
		return false;
	foreach($invoices as $invoice){
		if ( !is_array($invoice) || empty($invoice['state']) || empty($invoice['credit_targeted']) ) {
			continue;
		}
		if( $invoice['state'] == 4 && $invoice['credit_targeted'] == '1' ){
			$idInvoices[] = $invoice['id'];
		}
	}
	$idInvoices = implode(',', $idInvoices);
	if (empty($idInvoices))
		return false;
	$connection = Yii::app()->db;
	$cond = 'UPDATE zayavka_invoice
			 SET zid = NULL, date_to_zayav = NULL
			 WHERE zid IN (' . $idInvoices . ')';
	$command = $connection->createCommand($cond);
	$command->execute();
	return true;
	}

}
