<?php
class ServiceHelper {

    public static $test_email = 'alex21124@bk.ru';  //false в норме
    public static $test_sms = '+380678750320';  //false в норме
	public static $test_mode = false;  //false в норме


	public static function number2text($number, $texts, $textss, $text) {
		$number = abs ( $number );
		$number += 100;
		$number %= 100;
		if ( $number > 10 && $number < 15 )
			return $texts;
		$number %= 10;
		if ( $number > 1 && $number < 5 )
			return $textss;
		if ( $number == 1 )
			return $text;
		return $texts;
	}
	
	public static function notifiyAboutRegister($login, $password, $email, $mobile = false) {
		$subject = Yii::t ('site', 'SiteRegistrationSubject', array(), null, 'ua');
		$message = static::render('mailtpls/notifiyAboutRegister', array(
				'login'		=> $login, 
				'password'	=> $password,
		), true, 'ua');

		$result = static::sendEmail( $email, $subject, $message );
		if ( !$mobile ) {
			return $result;
		}
		$message = static::renderPartial('smstpls/notifiyAboutRegister', array(
				'login'		=> $login, 
				'password'	=> $password, 
		), true, 'ua');
		$result = $result && SmsHelper::sendSms($mobile, $message );
		return $result;
	}
	
	public static function notifiyAboutPayment($finish_date, $client, $summ_to_pay, $email = false, $mobile = false) {
		$result = true;
		$report = false;
		//var_dump($email, $mobile);
		if ( !$email && !$mobile ) {
			return false;
		}
        Yii::app()->language = 'ua';
        Yii::app()->request->hostInfo = Yii::app()->params['parent_host'];
        $diffDays =round( ( strtotime($finish_date) - strtotime(date('d.m.Y')) ) / (24*60*60) );
        $period = ($diffDays > 0) ? Yii::t('site', 'in x days').' '.$diffDays.' '.Yii::t('site', ServiceHelper::number2text( $diffDays, Yii::t ( 'site', 'days' ), Yii::t ( 'site', 'dayss' ), Yii::t ( 'site', 'day' ))).',':'';
        $notify_view = ($diffDays < 1) ? 'notifiyAboutDelay':'notifiyAboutPayment';
        if ( $email ) {
			$subject = Yii::t('site', 'Day to pay for loan contract');
			$message = static::render('mailtpls/'.$notify_view, array(
					'finish_date'	=> $finish_date, 
					'dog_number'	=> $client['zayavkaNumb'],
					'summ_to_pay'	=> $summ_to_pay,
                    'period'	    => $period,
			), true, 'ua' );
			$result = $result && static::sendEmail( $email, $subject, $message, false );
			if($result) {
				$report = static::renderPartial('mailtpls/reportEmail' , array(
					'client' => $client,
                    'subject'=> $subject,
					'message' => $message,
				), true, 'ua');
			}
		}

		if ( !$mobile ) {
			return $report;
		}

		$message = static::renderPartial('smstpls/'.$notify_view, array(
				'finish_date'	=> $finish_date, 
				'dog_number'	=> $client['zayavkaNumb'],
				'summ_to_pay'	=> $summ_to_pay,
                'period'	    => $period,
		), true, 'ua' );
        if(static::$test_mode) $result = $result && SmsHelper::sendSms(static::$test_sms, $message );
        else $result = $result && SmsHelper::sendSms($mobile, $message );
		if($result) {
			$report = static::renderPartial('mailtpls/reportSms', array(
                'client' => $client,
                'message' => $message,
			), true, 'ua');
		}
		return $report;
	}
	
	public static function notifiyAboutChanges($model) {
		if ( $model instanceof Anketa && $model->site_userId ) {
			$user = Users::model()->find('u_id='.(int)$model->site_userId);
			if ( is_null($user) ) {
				return false;
			}
			//Анкета отправлена экспертом на доработку клиенту
			if ( $model->state == 2 ) {
				$subject = Yii::t('site', 'Ankets data', array(), null, 'ua');
				$message = static::render('mailtpls/AnketOnEdit', array(
					'anketa'	=> $model,
					'user'		=> $user,
				), true, 'ua');
			$result = static::sendEmail($user->u_email, $subject, $message );
				$message = static::renderPartial('smstpls/AnketOnEdit', array(
					'anketa'	=> $model,
					'user'		=> $user,
				), true, 'ua');
			$result = $result && SmsHelper::sendSms($model->contact_phone_mobile, $message );
			}
		} elseif ( $model instanceof Zayavka ) {
			$user = Users::model()->find('u_id=(SELECT site_userId FROM anketa WHERE iid="'.$model->iid.'")');
			$anketa = Anketa::model()->find('iid="'.$model->iid.'"');
			if ( is_null($user) ) {
				return false;
			}

			//Заявка отредактирована экспертом иотправлена на согласование ( скорее всего уменьшили сумму займа )
			if ( $model->state == 2 ) {
				$subject = Yii::t('site', 'Reconciliation of credit', array(), null, 'ua');
				$message = static::render('mailtpls/ZayavkaOnEdit', array(
					'zayavka'	=> $model,
					'user'		=> $user,
				), true, 'ua');
				$result = static::sendEmail($user->u_email, $subject, $message );
				$message = static::renderPartial('smstpls/ZayavkaOnEdit', array(
					'zayavka'	=> $model,
					'user'		=> $user,
				), true, 'ua');
				$result = $result && SmsHelper::sendSms($anketa->contact_phone_mobile, $message );
			} 
			//Заявка утверждена пожно получать кредит				
			elseif ( $model->state == 3 ) {
                if(!empty($model->card)){
                    $subject = Yii::t('site', 'An application for a loan is approved!', array(), null, 'ua');
                    $message = static::render('mailtpls/ZayavkaActiveCard', array(
                        'zayavka'	=> $model,
                        'user'		=> $user,
                    ), true, 'ua');
                    $result = static::sendEmail($user->u_email, $subject, $message );
                    $message = static::renderPartial('smstpls/ZayavkaActiveCard', array(
                        'zayavka'	=> $model,
                        'user'		=> $user,
                    ), true, 'ua');
                    $result = $result && SmsHelper::sendSms($anketa->contact_phone_mobile, $message );
                } else {
                    $subject = Yii::t('site', 'An application for a loan is approved!', array(), null, 'ua');
                    $message = static::render('mailtpls/ZayavkaActiveLombard', array(
                        'zayavka' => $model,
                        'user' => $user,
                    ), true, 'ua');
                    $result = static::sendEmail($user->u_email, $subject, $message);
                    $message = static::renderPartial('smstpls/ZayavkaActiveLombard', array(
                        'zayavka' => $model,
                        'user' => $user,
                    ), true, 'ua');
                    $result = $result && SmsHelper::sendSms($anketa->contact_phone_mobile, $message);
                }
			}
            elseif ( $model->state == 4 ) { //Завка отклонена
                $subject = Yii::t('site', 'An application for a loan is rejected', array(), null, 'ua');
                $message = static::render('mailtpls/ZayavkaRejected', array(
                    'zayavka'	=> $model,
                    'user'		=> $user,
                ), true, 'ua');
                $result = static::sendEmail($user->u_email, $subject, $message );
                $message = static::renderPartial('smstpls/ZayavkaRejected', array(
                    'zayavka'	=> $model,
                    'user'		=> $user,
                ), true, 'ua');
                $result = $result && SmsHelper::sendSms($anketa->contact_phone_mobile, $message );
            }
		}

		
		return $result;
	}
	
	public static function sendEmail($to, $subject, $message, $test=false) {
		if(static::$test_mode){
            $message .= var_export($to, true);
            $to = static::$test_email;
        } else {
            if($test){
                $message .= var_export($to, true);
                $to = 'karasmg@gmail.com';
            }
        }

		$email = Yii::app()->params['adminEmail'];
		$sender = explode(',', $email);
		$from = trim($sender[0]);
		
		Yii::import('application.extensions.mailer.EMailer');
		$mail = new EMailer;
		$mail->CharSet = "UTF-8";
		
		$mail->setFrom($from, 'Kredytor info');
		
		if ( !is_array($to) )
			$to = explode(',', $to);
		$to = array_unique($to);
		
		foreach ( $to as $send ) {
			if ( !$send )
				continue;
			$mail->addAddress( trim($send) );
		}
		
		$mail->addReplyTo($from, 'Kredytor info');		
		$mail->isHTML(true);		
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$result = $mail->send();
		
		return $result;
	}
	
	public static function checkIsOnSiteGoing($model) {
		$result = false;
		if ( $model instanceof Anketa ) {
			$zavavka = Zayavka::model()->find(array(
				'condition'	=> 'iid='.(int)$model->iid,
				'order'		=> 'id DESC',
			));
			if ( !is_null($zavavka) ) {
				$result = $zavavka->siteCreated;
			}
		} elseif ( $model instanceof Zayavka ) {
			$result = $model->siteCreated;
		}
		
		return $result;
	}
	
	public static function addTriggerLog($route) {
		$url			= Yii::app()->request->url;
		$urlReferrer	= Yii::app()->request->urlReferrer;
		$userAgent		= Yii::app()->request->userAgent;
		$IPaddress		= Yii::app()->request->userHostAddress;
		$post			= http_build_query($_POST);
		$user_id		= (int)Yii::app()->user->id;
		$command = Yii::app()->db->createCommand()->insert('trigger_log', array(
			't_uid'			=> $user_id,
			't_url'			=> $url,
			't_post'		=> $post,
			't_user_agent'	=> $userAgent,
			't_ip'			=> $IPaddress,
			't_action'		=> $route,
			't_referrer'	=> $urlReferrer,
		));
	}
	
	public static function clearTriggerLog() {
		$stored_days = 14;
		Yii::app ()->db->createCommand ("DELETE FROM trigger_log WHERE TO_DAYS(NOW())-TO_DAYS(t_time) > ".$stored_days)->execute();		
	}
	
	public static function clearRequestLog() {
		$stored_days = 180;
		Yii::app ()->db->createCommand ("DELETE FROM request_log WHERE TO_DAYS(NOW())-TO_DAYS(date) > ".$stored_days)->execute();		
	}
	
	public static function render($view, $data=null, $return=false, $lang=false) {
        if($lang) {
            $tempLang = Yii::app()->language;
            Yii::app()->language = $lang;
        }
		$output = static::renderPartial($view, $data, true);
		$output = static::renderPartial('mailtpls/layouts/email_notification', array('content'=>$output), true);
        if($lang) Yii::app()->language = $tempLang;
		if($return)
			return $output;
		else
			echo $output;
	}
	
	public static function renderPartial($_viewFile_, $_data_ = null, $return = false, $lang=false) {
        if($lang) {
            $tempLang = Yii::app()->language;
            Yii::app()->language = $lang;
        }
		$path = Yii::app()->getBasePath().DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'frontend'.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $_viewFile_).'.php';
		if ( is_array($_data_) )
			extract($_data_, EXTR_PREFIX_SAME, 'data');
		else
			$data = $_data_;
		ob_start ();
		ob_implicit_flush(false);
		require ($path);
        if($lang) Yii::app()->language = $tempLang;
		return ob_get_clean ();
	}
}