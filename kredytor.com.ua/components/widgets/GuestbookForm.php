<?php
class GuestbookForm extends ContactForm
{
	public $_fields;
	public $_nameSpace = 'GuestbookForm';
	public $_manager_email = 'guestbook@skarb.com.ua,A.Titarenko@skarb.com.ua,bwe@skarb.com.ua,AllUr@skarb.com.ua,Valentina.Petruk@skarb.com.ua,Setevik1@skarb.com.ua,tech5@skarb.com.ua';
	public $_observers = 'shop@skarb.com.ua,E.Ovsuk@skarb.com.ua,Svetlana.Bahmutova@skarb.com.ua,Tatyana.Vasileva@skarb.com.ua,Setevik8@skarb.com.ua,a.prihodko@skarb.com.ua,Ludmila.Filipova@skarb.com.ua,setevik10@skarb.com.ua,a.yakovenko@skarb.com.ua,Setevik9@skarb.com.ua,Zaitseva@skarb.com.ua,Development@skarb.com.ua,Lisya.Samulyak@skarb.com.ua,D.Morozov@skarb.com.ua,call3@skarb.com.ua'; 

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
			'phone' => array(
				'type'		=> 'input',
				'label'		=> Yii::t('site', 'Your Phone'),
				'mail_label'=> Yii::t('main', 'Phones'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
				'validators'=> 'phoneValidator',
			),
			'email' => array(
				'type'		=> 'input',
				'label'		=> Yii::t('site', 'Your Email'),
				'required'	=> false,
				'val'		=> '',
				'error'		=> array(),
				'validators'=> 'emailValidator',
			),
			'lo_adress' => array(
				'type'		=> 'double_select',
				'label'		=> Yii::t('site', 'Lombard adress'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
				'validators'=> 'double_selectValidator',
				'selectname'=> array('City', 'Lombard'),
				'selectvals'=> array('', ''),
				'options'	=> array('', ''),
			),
			'text' => array(
				'type'		=> 'textarea',
				'label'		=> Yii::t('site', 'Your message'),
				'mail_label'=> Yii::t('site', 'Message'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
			),
			'capcha' => array(
				'type'		=> 'capcha',
				'label'		=> Yii::t('site', 'Control text'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
				'validators'=> 'capchaValidator',
			),
		);
	}
	
    public function run()
    {		
		if ( !empty($_REQUEST[$this->_nameSpace]) ) {
			if ( $this->checkFormVals($this->_nameSpace) ) {
				$result = $this->sendForm();
				return $this->render('ContactForm/SendedGuestbook', array('result'=>$result), true);
			}
		}
		return $this->render('ContactForm/GuestbookForm', array(
			'form_title'	=> Yii::t('site', 'Contact form'), 
			'fields'		=> $this->_fields,
		), true);
    }
	
	public function sendForm() {
		$email = Yii::app()->params['adminEmail'];
		if ( $this->_manager_email )
			$email.=','.$this->_manager_email;
		if ( $this->_observers )
			$email = str_replace ( (','.$this->_observers), '', $email );
		
		unset($_SESSION['security_number']);
		
		$sender = explode(',', $email);
		
		$guestbook = new Guestbook();
		$guestbook->g_lang = Yii::app()->language;
		$guestbook->g_uid = 0;
		$guestbook->g_lid = $this->_fields['lo_adress']['selectvals'][1];
		$guestbook->g_active = 0;
		$guestbook->g_date	= new CDbExpression('NOW()');
		$guestbook->g_name=$this->_fields['name']['val'];
		$guestbook->g_email=$this->_fields['email']['val'];
		$guestbook->g_phone=$this->_fields['phone']['val'];
		$guestbook->g_text=$this->_fields['text']['val'];
		$guestbook->g_user_ip=$_SERVER['REMOTE_ADDR'];	
		$guestbook->validate();
		$guestbook->save();	
		
		$this->_fields['lo_adress']['val'] = 
			$this->_fields['lo_adress']['options'][0][$this->_fields['lo_adress']['selectvals'][0]].', '.
			$this->_fields['lo_adress']['options'][1][$this->_fields['lo_adress']['selectvals'][1]][0];
		
		$subject = Yii::t('site', 'Guestbook form').' №'.$guestbook->g_id.' от '.date('H:i:s d.m.Y');
		
		$result = true;
		$message = ServiceHelper::render('contact_forms/contact_form', array('fields' => $this->_fields, 'subject' => $subject), true);
		$result = $result && ServiceHelper::sendEmail($sender, $subject, $message);

		if ( $this->_fields['lo_adress']['selectvals'][1] ) {
			$curators = Users::model()->findAll('u_id IN (SELECT user_id FROM admins_to_lo WHERE lo_id = '.(int)$this->_fields['lo_adress']['selectvals'][1].')');
			if ( $curators ) {
				foreach ( $curators as $curator ) {
					$send = $curator->u_email;
					$result = $result && ServiceHelper::sendEmail($sender, $subject, $message);
				}
			}
		}
		return $result;
	}
}
?>