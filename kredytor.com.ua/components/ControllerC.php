<?php
namespace app\components;

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */

use yii\base\Controller;
class ControllerC extends Controller
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public $_languege = '';
	
	public $pageKeywords;
	public $pageDescription;
	
	public function init() 
	{
		$this->handleLanguageBehavior();
		$this->_languege = Yii::app()->language;
		parent::init();
	}

	public function dump( $varible ) {
		echo '<pre>';
		var_dump($varible);
		echo '</pre>';
	}
	
	protected function check_lang( $_lang ) {
		return isset( Yii::app()->params['languages'][$_lang] );
	}

    protected function handleLanguageBehavior()
    {
        $app  = Yii::app();
        $user = $app->user;
				
		if ( isset($_GET['_lang']) || isset($_POST['_lang']) )
        {
			if ( isset($_POST['_lang']) ) $_lang = $_POST['_lang'];
			else $_lang = $_GET['_lang'];
						
			if ( $this->check_lang($_lang ) ) {
				$app->language = $_lang;
				/*
				$user->setState('_lang', $_lang);
				$cookie = new CHttpCookie('_lang', $_lang);
				$cookie->expire = time() + (60 * 60 * 24 * 365); // (1 year)
				$app->request->cookies['_lang'] = $cookie;
				
				* другой код, например обновление кеша некоторых компонентов, которые изменяются при смене языка
				*/
			}
        }
		/*
        elseif ($user->hasState('_lang'))
            $app->language = $user->getState('_lang');
		
        elseif (isset($app->request->cookies['_lang']))
            $app->language = $app->request->cookies['_lang']->value;
		 * 
		 */	
    }
	
	public function createMultilanguageReturnUrl($lang='en'){
		if (count($_GET)>0){
			$arr = $_GET;
			$arr['_lang']= $lang;
		}
		else 
			$arr = array('_lang'=>$lang);
		return $this->createUrl('', $arr);
	}
	
	public static function compile_items( $items, $relation_name, $column_names = array(), $select_vals = array() ) {
		$items_done = array();
		if ( !is_array($items) ) return $items_done;
		
		foreach ( $items as $key => $arr ) {
			$items_done[$key] = array();
			foreach ( $arr as $sub_key => $val ) {
				if ( isset($select_vals[$sub_key]) && isset($select_vals[$sub_key][$val]) ) $val = $select_vals[$sub_key][$val];
				if ( !in_array($sub_key, $column_names) ) continue; 
				$items_done[$key][$sub_key] = $val;
			}
						
			if ( $relation_name && isset($arr[$relation_name]) ) {
				
				if ( is_array($arr[$relation_name]) && isset($arr[$relation_name][0]) ) {
					foreach ( $arr[$relation_name][0] as $sub_key => $val ) {
						if ( isset($select_vals[$sub_key]) && isset($select_vals[$sub_key][$val]) ) $val = $select_vals[$sub_key][$val];
						if ( !in_array($sub_key, $column_names) ) continue; 
						$items_done[$key][$sub_key] = $val;
					}
				}elseif ( is_object($arr[$relation_name]) ) {
					foreach ($column_names as $sub_key => $val) {
						if ( isset($arr[$relation_name]->{$sub_key}) ) {
							$items_done[$key][$sub_key] = $arr[$relation_name]->{$sub_key};
						}
					}
				}
			}
		}			
		
		return $items_done;		
	}
	
	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	public static function mailToAdmin($subject, $message) {
		$email = Yii::app()->params['adminEmail'];
		$sender = explode(',', $email);
		
		ServiceHelper::sendEmail($sender, $subject, $message);
		return true;
	}	
	
	public function sendEmail($to, $subject, $message) {
		$email = Yii::app()->params['adminEmail'];
		$sender = explode(',', $email);
		
		$headers = "From: ".trim($sender[0])."\r\n"; 
		$headers.= "MIME-Version: 1.0\r\n"; 
		$headers.= "Content-Type: text/html; charset=utf-8\r\n"; 
		$headers.= "X-Priority: 1\r\n"; 
		
		return mail($to, $subject, $message, $headers);
	}
	
	public function getResourcesLog($memory=true, $used_tyme=true) {
		$log = new CLogger;
		$message = "Resurces used\n";
		$mem_ammount	= $mem_usage = $log->getMemoryUsage();
		$time_ammount	= $log->getExecutionTime();
		if ( $memory ) {			
			if ($mem_usage < 1024)
				$mem_usage.=" bytes";
			elseif ($mem_usage < 1048576)
				$mem_usage = round($mem_usage/1024,2)." kb";
			else
				$mem_usage = round($mem_usage/1048576,2)." mb";
			$message.= "Memory: ".$mem_usage."\n";
		}
		if ( $used_tyme )
			$message.= "Time: ".$time_ammount."\n";
		
		return array(
			'message'	=> $message,
			'memory'	=> $mem_ammount,
			'time'		=> $time_ammount,
		);
	}	
	
	public function beforeAction($action) {
		ServiceHelper::addTriggerLog( $this->getRoute() );
		return parent::beforeAction($action);
	}
	
	public function afterAction($action, $result) {
		$result = parent::afterAction($action, $result);
		$message = "\nController: ".Yii::app()->controller->id."\n";
		$message.= "Action: ".Yii::app()->controller->action->id."\n\n\n\n";
		
		$exeptions = array(
			array( 'controller'	=> 'loan',		'action'	=> 'smsverification' ),
			array( 'controller'	=> 'cards',		'action'	=> 'verification' ),
			array( 'controller'	=> 'loan',		'action'	=> 'sendmoneytocard' ),
		);
		
		foreach ( $exeptions as $exeption ) {
			if ( Yii::app()->controller->id == $exeption['controller'] && Yii::app()->controller->action->id == $exeption['action'] ) {
				return parent::afterAction($action);
			}
		}
		
		
		
		$message.= "Post: ".serialize($_POST)."\n\n\n\n";
		$message.= "Get: ".serialize($_GET)."\n\n\n\n";
		$data = $this->getResourcesLog();
		$message.=$data['message'];
		if ( ($data['memory'] > 1048576*5 || $data['time'] > 1.5) && Yii::app()->controller->id != 'adminPayment' )
			Yii::log($message, "findtrace", "actionShowrequests");
		return $result;
	}
}