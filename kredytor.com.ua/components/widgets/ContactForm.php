<?php
class ContactForm extends CWidget
{
	public $_fields;
	public $_nameSpace = 'ContactForm';
	public $_method = 'post';
	public $_errors = array();
	public $_globalCheck = array();
	public $_title;
	public $_mail_subject;
	public $_manager_email = 'info@kredytor.com.ua';
	public $_observers = ''; 
	
	public function __construct() {
		parent::__construct();
		$this->_title = Yii::t('site', 'Contact form');
		$this->_mail_subject = Yii::t('main', 'Contact form');
		$this->_fields = array(
			'name' => array(
				'type'		=> 'input',
				'label'		=> Yii::t('site', 'Your Name'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
			),		
			'email' => array(
				'type'		=> 'input',
				'label'		=> Yii::t('site', 'Your Email'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
				'validators'=> 'emailValidator',
			),
			'text' => array(
				'type'		=> 'textarea',
				'label'		=> Yii::t('site', 'Text'),
				'required'	=> true,
				'val'		=> '',
				'error'		=> array(),
			),
		);
		if ( $this->_observers )
			$this->_manager_email.= ','.$this->_observers;
	}
	
    public function run()
    {		
		if ( !empty($_REQUEST[$this->_nameSpace]) ) {
			if ( $this->checkFormVals($this->_nameSpace) ) {
				$result = $this->sendForm();
				if ( $result ) {
					Yii :: app()->request->redirect('/'.Yii::app()->language.'/kontakty/?result=1');
					Yii :: app()->end;
				}
			}
		}		
		
		$this->render('ContactForm/ContactForm', array(
			'form_title'	=> Yii::t('site', 'Contact form'), 
			'fields'		=> $this->_fields,
		));
    }
	
	public function checkFormVals($nameSpace) {
		$valid = true;		
		foreach ( $this->_fields as $name=>$fld ) {
			$val = $this->_fields[$name]['val'];
			if ( isset($_REQUEST[$nameSpace][$name]) ) {
				if (  is_array($_REQUEST[$nameSpace][$name]) ) 
					$val = $_REQUEST[$nameSpace][$name];
				else
					$val = trim($_REQUEST[$nameSpace][$name]);
			}
			$this->_fields[$name]['val'] = $val;
			//Дополнительные валидаторы поля
			if ( !empty($this->_fields[$name]['validators']) ) {
				if ( !$this->additionalValidation($name) )
					$valid = false;
			}
			if ( $fld['required'] && !$this->_fields[$name]['val'] ) {
				$valid = false;
				$this->_fields[$name]['error'][] = Yii::t('site', 'Can`t be empty');
			}
		}
		foreach ( $this->_globalCheck as $check ) {
			eval($check['condition']);
			if ( $test ) {
				$valid = false;
				$this->_errors[] = Yii::t('site', $check['msg']);
			}
		}
		return $valid;
	}
	
	public function buildField($name, $fld) {
		return $this->render('ContactForm/fields/'.$fld['type'], array(
			'name'	=> $name,
			'fld'	=> $fld,
		), true);
	}
	
	public function showErrors($error_list) {
		$return = '';
		if ( !empty($error_list) )
			$return = '<ul class="error-text"><li>- '.implode('</li><li>- ', $error_list).'</li></ul>';
		return $return;
	}
	
	public function sendForm() {
		$email = Yii::app()->params['adminEmail'];
		if ( $this->_manager_email )
			$email=$this->_manager_email.','.$email;
		if ( $this->_observers )
			$email = str_replace ( (','.$this->_observers), '', $email );
		unset($_SESSION['security_number']);
		$sender = explode(',', $email);
		
		$subject = $this->_title.' '.Yii::app()->name;
		if ( $this->_mail_subject )
			$subject = $this->_mail_subject.' '.Yii::app()->name;
		
		$message = ServiceHelper::render('contact_forms/contact_form', array('fields' => $this->_fields, 'subject' => $subject), true);
		ServiceHelper::sendEmail($sender, $subject, $message);
		
		$result = $this->saveResult();		
		return $result;
	}
	
	protected function saveResult() {
		
		$result = new FormsSendedData();
		$fields_types = $fields_vals = $fields_names = array();
		foreach ( $this->_fields as $fld_name=>$fld_params ) {
			$fields_types[$fld_name] = $fld_params['type'];
			$fields_vals[$fld_name]  = $fld_params['val'];
			$fields_names[$fld_name]  = $fld_params['label'];
		}
		$result->f_form_name = $this->_title;
		$result->f_form_state = 'New';
		$result->f_field_types = serialize($fields_types);
		$result->f_field_vals = serialize($fields_vals);
		$result->f_field_names = serialize($fields_names);
		$result->f_admins = $this->_manager_email;
		$result->f_type = get_class($this);
		$result->f_user_ip = $_SERVER['REMOTE_ADDR'];
		return $result->save();
	}
	
	private function additionalValidation($name) {
		$valid = true;
		$val = $this->_fields[$name]['val'];
		if ( is_array($this->_fields[$name]['validators']) ) {
			
		} else {
			$operation = $this->_fields[$name]['validators'];
			if ( method_exists($this, $operation) ) {
				if ( $this->{$operation}($name, $val) === false )
					$valid = false;
			}				
		}
		return $valid;
	}
	
	public function emailValidator($name, $val) {
		if ( !trim($val) ) return true;
		$result = filter_var($val, FILTER_VALIDATE_EMAIL);
		if ( !$result ) {
			$this->_fields[$name]['error'][] = Yii::t('site', 'Should be valid email');
		}
		return $result;
	}
	public function capchaValidator($name, $val) {
		$result = ( isset($_SESSION['security_number']) && $_SESSION['security_number'] == $val );
		if ( !$result ) {
			$this->_fields[$name]['error'][] = Yii::t('site', 'Wrong control text');
		}
		return $result;
	}
	public function capchaHidenValidator($name, $val) {
		$result = ( !empty($_SESSION['security_number']) );
		if ( !$result ) {
			$this->_fields[$name]['error'][] = Yii::t('site', 'Wrong control text');
		}
		return $result;
	}
	public function double_selectValidator($name, $val) {
		$val_1 = $val_2 = 0;
		$nameSpace = $this->_nameSpace;
		if ( isset($_REQUEST[$nameSpace][$this->_fields[$name]['selectname'][0]]) ) {
			$val_1 = trim($_REQUEST[$nameSpace][$this->_fields[$name]['selectname'][0]]);
		}
		if ( isset($_REQUEST[$nameSpace][$this->_fields[$name]['selectname'][1]]) ) {
			$val_2 = trim($_REQUEST[$nameSpace][$this->_fields[$name]['selectname'][1]]);
		}
		$this->_fields[$name]['val'] = $val_2;
		$this->_fields[$name]['selectvals'] = array($val_1, $val_2);
		$result = true;
		if ( !$result ) {
			$this->_fields[$name]['error'][] = Yii::t('site', 'Wrong control text');
		}
		return $result;
	}
	public function imageValidator($name, $val) {
		$nameSpace = $this->_nameSpace;	
		for ( $i=0; $i<$this->_fields[$name]['count']; $i++ ) {
			$file = $this->workWithPics($name, $i);
			if (!$file) continue;
			$this->_fields[$name]['val'].='<a href="https://'.$_SERVER['HTTP_HOST'].$file.'">'.$_SERVER['HTTP_HOST'].$file.'</a><br/>';
			$this->_fields[$name]['vals'][$i] = $file;
		}
		
		return $this->_fields[$name]['val'];
	}
	public function checkboxValidator($name, $val) {
		$this->_fields[$name]['vals'] = array();
		foreach ( $val as $one_val)
			if ( trim($one_val) )
				$this->_fields[$name]['vals'][] = trim($one_val);
		$this->_fields[$name]['val'] = implode(', ',  $this->_fields[$name]['vals']);
		return true;
	}
	public function phoneValidator($name, $val) {
		if ( !trim($val) ) return true;
		$result = preg_match("/^((8|\+3|\+38)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $val);
		if ( !$result ) {
			$this->_fields[$name]['error'][] = Yii::t('site', 'Should be valid phone');
		}
		return $result;
	}
	public function birthdateValidator($name, $val) {
		$nameSpace = $this->_nameSpace;
		$vals = array('-','-','-');
		for ( $i=0; $i<3; $i++ ) {
			if ( isset($_REQUEST[$nameSpace][$name][$i]) )
				$vals[$i] = $_REQUEST[$nameSpace][$name][$i];
		}
		$this->_fields[$name]['vals'] = $vals;
		$val = implode('.', $vals);
		if ( !$val ) {
			$this->_fields[$name]['val'] = '';
			return;
		}
		if ( strtotime($val) )
			$this->_fields[$name]['val'] = $val;
		elseif ( $val != '-.-.-')
			$this->_fields[$name]['error'][] = Yii::t('site', 'Wrong date');
				
		return $this->_fields[$name]['val'];
	}
	public function mobilephoneValidator($name, $val) {
		if ( !trim($val) ) return true;
		$result = preg_match("/^(39|50|63|66|67|68|91|92|93|94|95|96|97|98|99)[0-9]{7,7}$/", $val);
		if ( !$result ) {
			$this->_fields[$name]['error'][] = Yii::t('site', 'Should be valid phone');
			return false;
		}
		return true;
	}
	public function mobilephoneValidatorFullnumb($name, $val) {
		if ( !trim($val) ) return true;
		$phone = preg_replace('/\D/', '', $val);
		if ( !preg_match('/\d{9,12}/', $phone) ) {
			$this->_fields[$name]['error'][] = Yii::t('site', 'Should be valid phone');
			return false;
		}
		$phone = str_pad($phone, 13,  '+380', STR_PAD_LEFT);
		if ( !preg_match('/\+380\d{9}/', $phone) ) {
			$this->_fields[$name]['error'][] = Yii::t('site', 'Should be valid phone');
			return false;
		}
		return true;
	}
	
	
	
	
	public function workWithPics( $name, $index=0, $path = 'upload/images', $dimantions = array(800, 600), $quality = 95 ) {
		
		$path = $path.'/';
		$pic_data = false;		
		
		if ( !empty($_FILES[$this->_nameSpace]['tmp_name'][$name][$index]) ) {
			$pic_data = new PsevdoCUploadedFile();
			$pic_data->tempName = $_FILES[$this->_nameSpace]['tmp_name'][$name][$index];
			$pic_data->name		= $_FILES[$this->_nameSpace]['name'][$name][$index];
		}
		if ( !$pic_data ) {
			if ( isset($_REQUEST[$this->_nameSpace][$name.'_hidden'][$index]) )
				return $_REQUEST[$this->_nameSpace][$name.'_hidden'][$index];
			return 
				false;
		}
		$image_info = getimagesize($pic_data->tempName);
		if ( !$image_info ) return false;
		
		$size		= array($image_info[0], $image_info[1]);
		$image_type = $image_info['mime'];
		$img_width	= $dimantions[0];
		$img_height = $dimantions[1];
		
		switch ($image_type) {
			case 'image/jpeg' :$src = imagecreatefromjpeg($pic_data->tempName); break;
			case 'image/png' :$src = imagecreatefrompng($pic_data->tempName); break;
			case 'image/gif' :$src = imagecreatefromgif($pic_data->tempName); break;
			default:
				$src = imagecreatefromjpeg($pic_data->tempName);
		};
		
		$dop_name = 0;
		$up_file = $path.$dop_name.'_'.$pic_data->name;
		while ( file_exists($up_file) ) {
			$dop_name++;
			$up_file = $path.$dop_name.'_'.$pic_data->name;
		}
		
		if ( $img_width  > $size[0] ) $img_width  = $size[0];
		if ( $img_height > $size[1] ) $img_height = $size[1];

		if ($size[0] > $size[1]) { //если ихсод картинка широкая - вписываем по ширине.
			$img_height   = $size[1]/($size[0]/$img_width);
		}else {
			$img_width    = $size[0]/($size[1]/$img_height);
		}
		
		$dest_conteiner = imagecreatetruecolor($img_width, $img_height);
		imagefill($dest_conteiner, 0, 0, 0xFFFFFF);
		imagecopyresampled($dest_conteiner, $src, 0, 0, 0, 0, $img_width, $img_height, $size[0], $size[1]);
		
		switch ($image_type) {
			case 'image/jpeg':
				imagejpeg($dest_conteiner, $up_file, $quality);
			break;
			case 'image/png':
				imagepng($dest_conteiner, $up_file, ($quality/10)); 		   
			break;
			case 'image/gif':
				imagegif($dest_conteiner, $up_file, $quality);
			break;
			default:
				imagejpeg($dest_conteiner, $up_file, $quality); 
		};
		
		return '/'.$up_file; 
	}
	
	public function outputErrors() {
		$result = array();
		foreach ( $this->_fields as $keyname=>$fld ) {
			$result[$keyname] = $fld['error'];
		}
		$result = array_merge($result, $this->_errors);
		return $result;
	}
}
?>