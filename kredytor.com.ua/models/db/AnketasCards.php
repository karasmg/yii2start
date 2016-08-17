<?php

/**
 * This is the model class for table "anketas_cards".
 *
 * The followings are the available columns in table 'anketas_cards':
 * @property integer $ac_id
 * @property integer $ac_uid
 * @property string $card_number
 * @property string $card_valid
 * @property integer $card_named
 * @property string $card_holder
 * @property integer $card_state
 * @property integer $card_attempts
 * @property double $card_block_summ
 * @property string $outer_provider
 * @property string $outer_hash
 */
class AnketasCards extends CActiveRecord {
	public $month;
	public $year;
	public $card_sum_verification;
	public $password;
	public $maskedCard = false;
	public $payCardMask = false;
	/**
	 *
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'anketas_cards';
	}
	
	/**
	 *
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array (
			array ('ac_uid, card_number, card_valid', 'required'),
			array ('card_number', 'unique'),
			array ('card_named, card_attempts, card_state, card_g, card_type, ac_uid', 'numerical', 'integerOnly' => true),
			array ('card_block_summ, month, year', 'numerical'),
			array ('month', 'length', 'max' => 2),
			array ('year', 'length', 'max' => 4),
			array ('card_type, card_attempts, card_g', 'length', 'max' => 1),
			array ('card_number', 'length', 'max' => 19, 'min' => 19),
			array ('card_valid, ac_uid', 'length', 'max' => 5),
			array ('outer_provider, outer_hash', 'length', 'max' => 255),
			array ('card_holder', 'checkCardHolder'),				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array ('ac_uid, card_number, card_attempts, card_type, card_valid, card_g, card_named, card_holder, card_state, card_block_summ, outer_provider, outer_hash', 'safe', 'on' => 'search'),
			array('card_attempts', 'default', 'value'=>3, 'setOnEmpty'=>true,'on'=>'insert, new_card'),
		);
	}
	
	/**
	 *
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array ();
	}
	public function selectValues() {
		return array (
			'card_state' => array ('DoneVals', array (
				'0' => (Yii::t ( 'site', 'Not active' )),
				'1' => (Yii::t ( 'site', 'Active' )),
				'2' => (Yii::t ( 'site', 'Checking' )),
				'-1' => (Yii::t ( 'site', 'Blocked' )),
			))
		);
	}
	
	/**
	 *
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array (
			'ac_uid' => (Yii::t ( 'main', 'User' )),
			'card_type' => (Yii::t ( 'site', 'Card type' )),
			'card_number' => (Yii::t ( 'site', 'Card number' )),
			'card_valid' => (Yii::t ( 'site', 'Card expired date' )),
			'card_g' => (Yii::t ( 'site', 'Is general' )),
			'card_named' => (Yii::t ( 'site', 'Is personalized card' )),
			'card_sum_verification'=> (Yii::t ( 'site', 'Verification summ' )),
			'card_holder' => (Yii::t ( 'site', 'Card holder name' )),
			'card_attempts' => (Yii::t ( 'site', 'Card attempts' )),
			'card_state' => (Yii::t ( 'site', 'Card state' )), // 'статус карты. 0-не активна. 1-активна. 2-проверяется',
			'card_block_summ' => (Yii::t ( 'site', 'Blocked summ' )),
			'outer_provider' => (Yii::t ( 'site', 'outer_provider' )),
			'outer_hash' => (Yii::t ( 'site', 'outer_hash' )),
		);
	}
	public function fieldtypes($asked_field) {
		$fields = array (
				'ac_uid' => 'HiddenField',
				'card_number' => 'TextField',
				'card_valid' => 'TextField',
				'card_named' => 'CheckBox',
				'card_holder' => 'TextField',
				'card_state' => 'DropDownList',
				'card_type' => 'DropDownList',
				'card_block_summ' => 'HiddenField',
				'outer_provider' => 'HiddenField',
				'outer_hash' => 'HiddenField', 
		);
		
		if (isset ( $fields [$asked_field] ))
			return $fields [$asked_field];
		else
			return 'TextField';
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
	 *         based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria = new CDbCriteria ();
		
		$criteria->compare ( 'ac_uid', $this->ac_uid, true );
		$criteria->compare ( 'card_number', $this->card_number, true );
		$criteria->compare ( 'card_type', $this->card_type, true );
		$criteria->compare ( 'card_valid', $this->card_valid, true );
		$criteria->compare ( 'card_g', $this->card_g, true );
		$criteria->compare ( 'card_attempts', $this->card_attempts, true );
		$criteria->compare ( 'card_named', $this->card_named );
		$criteria->compare ( 'card_holder', $this->card_holder, true );
		$criteria->compare ( 'card_state', $this->card_state );
		$criteria->compare ( 'card_block_summ', $this->card_block_summ );
		$criteria->compare ( 'outer_provider', $this->outer_provider );
		$criteria->compare ( 'outer_hash', $this->outer_hash );
		
		return new CActiveDataProvider ( $this, array (
				'criteria' => $criteria 
		) );
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * 
	 * @param string $className
	 *        	active record class name.
	 * @return AnketasCards the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function checkCardHolder($attribute, $params) {
		$value = $this->attributes [$attribute];
		$named = $this->attributes ['card_named'];
		if (empty ( $named ))
			return true;
		if ($named == '1' && ! empty ( $value ) && count ( $value ) < 255)
			return true;
		else {
			$this->addError ( $attribute, Yii::t ( 'site', 'Enter Cardholder name' ) );
			return false;
		}
	}
	
	protected function beforeSave() {
		$this->card_type = substr($this->card_number, 0, 1);
		$cards_count = AnketasCards::model()->count(array(
			'condition' => 'ac_uid=:ac_uid and card_state > 0',
			'params' => array (
				':ac_uid' => YII::app ()->user->id,
			)
		));
		if ( !$cards_count )
			$this->card_g = 1;
		return parent::beforeSave();
	}
	
	public function __get($name) {
		if ( method_exists($this, ('get'.$name)) ) {
			return call_user_func(array($this, ('get'.$name)));
		}
		return parent::__get($name);
	}
	
	public function getmaskedCardnumber() {
		if ( $this->maskedCard ) {
			return $this->maskedCard; 
		}
		if ( !$this->card_number ) {
			return false;
		}
		$this->maskedCard = substr($this->card_number,0,4).'-XXXX-XXXX-'.substr($this->card_number,-4);
		return $this->maskedCard;
	}
	public function getpayCardMasknumber() {
		if ( $this->payCardMask ) {
			return $this->payCardMask; 
		}
		if ( !$this->card_number ) {
			return false;
		}
		$number = str_replace(' ', '', $this->card_number);
		$this->payCardMask = substr($number,0,6).'****'.substr($number,-4);
		return $this->payCardMask;
	}
	public function getpayCardNumberInt() {
		if ( !$this->card_number ) {
			return false;
		}		
		$number = str_replace(' ', '', $this->card_number);
		return $number;
	}
}
