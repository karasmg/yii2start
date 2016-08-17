<?php
class FeedBackPopup extends ContactForm
{
	public $_fields;
	public $_nameSpace = 'FeedBackPopup';
	public $_manager_email = 'info@kredytor.com.ua';
	public $_observers = ''; 

	public function __construct() {
		parent::__construct();
		
		$this->_mail_subject = Yii::t('main', 'Guestbook form');
					
		$this->_fields = array(
			'name' => array(
				'type'		=> 'input',
				'label'		=> Yii::t('site', 'Your Name'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
			),
			'text' => array(
				'type'		=> 'textarea',
				'label'		=> Yii::t('site', 'Your message'),
				'mail_label'=> Yii::t('site', 'Message'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
			),
			'prodphoto' => array(
				'type'		=> 'image',
				'label'		=> Yii::t('site', 'Add a picture'),
				'required'	=> false,
				'val'		=> '',
				'error'		=> array(),
				'count'		=> 1,
				'validators'=> 'imageValidator',
			),
			'capcha' => array(
				'type'		=> 'hidden_capcha',
				'label'		=> Yii::t('site', 'Control text'),
				'required'	=> true,
				'val'		=> '111',
				'error'		=> array(),
				'validators'=> 'capchaHidenValidator',
			),
		);
	}
	
    public function run()
    {		
		$result = false;
		if ( !empty($_REQUEST[$this->_nameSpace]) ) {
			if ( $this->checkFormVals($this->_nameSpace) ) {
				$result = $this->sendForm();
			}
		}
		return $this->render('ContactForm/FeedBackPopup', array(
			'fields'		=> $this->_fields,
			'result'		=> $result,
		), false);
    }
	
	public function sendForm() {
		$email_from = Yii::app()->params['adminEmail'];
		$email = $email_from;
		if ( $this->_manager_email )
			$email.=','.$this->_manager_email;
		if ( $this->_observers )
			$email = str_replace ( (','.$this->_observers), '', $email );
		
		unset($_SESSION['security_number']);		
		$sender = explode(',', $email_from);
		
		$guestbook = new Guestbook();
		$guestbook->g_lang = Yii::app()->language;
		$guestbook->g_uid = 0;
		$guestbook->g_active = 0;
		$guestbook->g_date	= new CDbExpression('NOW()');
		$guestbook->g_name=$this->_fields['name']['val'];
		if ( !empty($this->_fields['prodphoto']['vals'][0]) )
			$guestbook->g_foto=$this->_fields['prodphoto']['vals'][0];
		$guestbook->g_phone=rand(10000000, 9999999);
		$guestbook->g_text=$this->_fields['text']['val'];
		$guestbook->g_user_ip=$_SERVER['REMOTE_ADDR'];	
		$guestbook->validate();
		$guestbook->save();	
		
		$subject = Yii::t('site', 'Guestbook form').' №'.$guestbook->g_id.' от '.date('H:i:s d.m.Y');
		$sender = explode(',', $email);

		$result = true;
		$message = ServiceHelper::render('contact_forms/contact_form', array('fields' => $this->_fields, 'subject' => $subject), true);
		$result = $result && ServiceHelper::sendEmail($sender, $subject, $message);
		return $result;
	}
}
?>