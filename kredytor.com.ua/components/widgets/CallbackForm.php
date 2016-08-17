<?php
class CallbackForm extends ContactForm
{
	public $_fields;
	public $_nameSpace = 'CallbackForm';
	public $_manager_email = 'info@kredytor.com.ua';
	public $_observers = ''; 
	
	public function __construct() {
		parent::__construct();
		$this->_title = Yii::t('site', 'Callback form');
		$this->_mail_subject = Yii::t('main', 'Callback form');
		$this->_fields = array(
			'name' => array(
				'type'		=> 'input',
				'label'		=> Yii::t('site', 'Your Name'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
			),			
			'phone' => array(
				'type'		=> 'input',
				'label'		=> Yii::t('site', 'Your Phone'),
				'required'	=> true,
				'val'		=> '+380',
				'error'		=> array(),
				'validators'=> 'phoneValidator',
				//'additionalinfo' => Yii::t('site', 'Phone desc'),
			),
		);
	}
	
    public function run()
    {		
		$result = 0;
		if ( !empty($_REQUEST[$this->_nameSpace]) ) {
			if ( isset($_REQUEST[$this->_nameSpace]['mobile_hash']) ) {
				$mobile_hash = md5( (date('d.m.Y').'mobile_app') );
				if ( $_REQUEST[$this->_nameSpace]['mobile_hash'] == $mobile_hash ) {
					$_SESSION['security_number'] = $mobile_hash;
					$this->_fields['capcha']['val'] = $mobile_hash;
				}
			}
			if ( $this->checkFormVals($this->_nameSpace) ) {
				$result = $this->sendForm();
			}
		}		
		$this->render('ContactForm/CallbackForm', array(
			'fields'		=> $this->_fields,
			'result'		=> $result,
		));
		return;
    }
}
?>