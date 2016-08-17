<?php
use app\components\Controller as Controller;
class SiteController extends Controller {
	/**
	 * Declares class-based actions.
	 */
	private $_helper = NULL;
	public $flash_rolik;
	public $breadcrumbs;
	public $breadcrumbs_last = null;
	public $layout_path;
	public $_showslider = true;
	public $step;
	public $isMenuDisabled;

	public function init() {
		$this->_helper = new Url_works ();
		$this->layout_path = Yii::app ()->params['layout_site_path'];
		$this->layout = '//' . $this->layout_path . '/content';
		$this->isMenuDisabled = Yii::app()->user->isMenuDisabled;
		$this->checkPassChange();
		parent::init ();
		if ( ! empty ( $_REQUEST['ajax_request'] ) ) {
			$this->layout = false;
		}
	}

	protected function beforeAction($action) {
		$this->check_mobile_redirect();
		return parent::beforeAction($action);
	}

	public function render($view, $data = null, $return = false) {
		$view = explode ( '/', $view );
		$view[(count ( $view ) - 1)] = Yii::app ()->params['mobile_sub_categ'] . $view[(count ( $view ) - 1)];
		$view = implode ( '/', $view );
		Yii::app ()->params['breadcrumbs'] = $this->breadcrumbs;
		return parent::render ( $view, $data, $return );
	}

	private function check_mobile_redirect() {
		$check_position = '/' . str_replace ( '/', '', Yii::app ()->params['mobile_sub_categ'] );
		if ( $this->getId () == 'site' && $this->getAction ()
			->getId () == 'index' && Yii::app ()->params['mobile_sub_categ'] && strpos ( $_SERVER['REQUEST_URI'], $check_position ) !== 0 ) {
			$this->redirect ( $this->createUrl ( '/' ), true, 301 );
			Yii::app ()->end ();
		}
	}

	public function actions() {
		return array (
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha' => array (
						'class' => 'CCaptchaAction', 
						'backColor' => 0xE8E8E8, 
						'foreColor' => 0x575757, 
						'height' => 50, 
						'width' => 132, 
						'fontFile' => Yii::app ()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'tahoma.ttf', 
						'minLength' => 2, 
						'maxLength' => 4 ), 
				// page action renders "static" pages stored under 'protected/views/site/pages'
				// They can be accessed via: index.php?r=site/page&view=FileName
				'page' => array ('class' => 'CViewAction' ) );
	}

	
	public function checkPassChange() {		
		if(Yii::app ()->user->isGuest) {
			return false;
		}
		if(stripos (Yii::app ()->request->url, 'changepassword/pass/needtochange')) 
			return;
		if( !empty(Yii::app()->user->passneedchange) ) {
			$this->redirect (array ($this->createURL(Yii::app()->language.'/'.'isregistered/changepassword'), 'pass' => 'needtochange'));
			return true;
		} else
			return false;
		
	}	
	
	public function actionRedirect()
	{
		$_af = '/'.Yii::app ()->language.Yii::app()->request->url;
		$this->redirect( $_af );
	}


	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex() {
		$this->layout = '//' . $this->layout_path . '/main';
		$article_data = ContentArticles::model ()->with ( array ('lang_data' => array ('joinType' => 'LEFT JOIN', 'condition' => 'cal_lang = "' . Yii::app ()->language . '"' ) ) )
			->find ( 'ca_id=:ca_id AND ca_active = 1', array (':ca_id' => 1 ) );
		$article_data = $this->compile_article ( $article_data );
		
		$total_clients = Anketa::model ()->count ();
		
		$criteria = new CDbCriteria ();
		$criteria->order = 'n_active_date DESC, n_id DESC';
		$criteria->condition = 'n_active=1 AND ( n_active_date <= NOW() OR n_active_date IS NULL ) AND n_cat_id=2';
		
		$news = News::model ()->with ( array ('lang_data' => array ('joinType' => 'LEFT JOIN', 'condition' => 'nl_lang = "' . Yii::app ()->language . '"' ) ) )
			->find ( $criteria );
		
		$about_us = ContentArticles::model ()->with ( array ('lang_data' => array ('joinType' => 'LEFT JOIN', 'condition' => 'cal_lang = "' . Yii::app ()->language . '"' ) ) )
			->find ( 'ca_id=:ca_id AND ca_active = 1', array (':ca_id' => 3 ) );
		
		$criteria = new CDbCriteria ();
		$criteria->order = 'g_date DESC';
		$criteria->condition = 'g_active = 1';
		$question = Guestbook::model ()->find ( $criteria );
		
		$this->render ( 'index', array ('article' => $article_data, 'total_clients' => $total_clients, 'news' => $news, 'question' => $question, 'about_us' => $about_us ) );
	}

	public function fixShortAnons($text, $len = 200) {
		if ( mb_strlen ( $text, 'UTF-8' ) <= $len )
			return $text;
		$pos = mb_strpos ( $text, ' ', $len, 'UTF-8' );
		return strip_tags ( mb_substr ( $text, 0, $pos, 'UTF-8' ) ) . '...';
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
		$this->render ( '404error' );
	}

	public function actionRegistration() {
		if ( !Yii::app ()->user->isGuest && !IframeHelper::camedFromIframe() ) {
			$this->redirect('/'.Yii::app()->language.'/personalpage/loan/creditstatus/');
		}
		
		$isGuest = true;
		$this->step = 1;
		$this->breadcrumbs[] = Yii::t ( 'site', 'register' );
		$model = new Users ();
		$model->u_subscribe = 1;
		
		if ( isset ( $_POST['Users'] ) ) {
			$model->attributes = $_POST['Users'];			
			$login = new LoginForm ();
			$login->username = $model->u_email;
			$login->password = $model->u_pass;
			$model->u_active = 1;
			$model->u_access_level = 1;
			if(empty($model->u_login))
				$model->u_login = $model->u_email;
			if ( $model->validate() ) {
				$model->save();
				$_SESSION['_states'] = false;
			}
			$sessionId = session_id();
			if ( $login->validate () && $login->login () ) {
				TempZayavkaHelper::updateByUserId($sessionId);
				$this->redirect ( '/' . Yii::app ()->language . '/personalpage/' );
				return;
			}
		}
		$this->render ('registraion', array(
			'model'		=> $model, 
			'isGuest'	=> $isGuest, 
			'step'		=> $this->step,
		));
	}
	/**
	 * Displays the login page
	 */
	public function actionLogin($h2Message=false) {
		if ( !Yii::app ()->user->isGuest && !$h2Message ) {
            $_SESSION['_states'] = false;
			$this->redirect('/'.Yii::app()->language.'/personalpage/loan/creditstatus/');
		}
		if(!$h2Message) $h2Message = 'login to continue';
		
		$this->breadcrumbs[] = Yii::t ( 'site', 'login' );
		$model = new LoginForm();
		if ( isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			$sessionId = session_id();
			if ( $model->validate() && $model->login() ) {

				TempZayavkaHelper::updateByUserId($sessionId);
				if(!empty(Yii::app()->request->cookies['redirect_cookie'])) {
					$redirect_cookie = Yii::app()->request->cookies['redirect_cookie']->value;
					unset(Yii::app()->request->cookies['redirect_cookie']);
					$this->redirect ($redirect_cookie);
				} else
					$this->redirect ('/'.Yii::app()->language.'/personalpage/');
				return;
			}
		}
		$this->render('login', array(
			'model'		=> $model, 
			'h2Message' => $h2Message,
		));
	}
	
	public function actionPassrecovery() {
		if ( !Yii::app()->user->isGuest )
			$this->redirect('/'.Yii::app()->language.'/personalpage/loan/creditstatus/');
		$this->breadcrumbs[] = Yii::t ( 'site', 'password recovery' );
		
		if ( isset($_GET['token']) && !is_null($_GET['token']) ) {
			$model = new NewpassForm();
			if ( isset($_POST['NewpassForm']) ) {
				$model->attributes = $_POST['NewpassForm'];
				if( $model->validate() && $model->saveNewPass() ) {
					$this->render('passrecovery/password_recovery_success', array());
					return false;
				}				
			} else {
				$model->token = $_GET['token'];
				$model->validate(array('token'));
			}
				
			$this->render('passrecovery/password_recovery_newpass', array('model' => $model) );
		}
		
		$model = new RecoveryForm();
		if ( isset($_POST['RecoveryForm']) ) {
			$model->attributes = $_POST['RecoveryForm'];
			if ( $model->validate() ) {
				$model->send_token();
				$this->render('passrecovery/password_recovery_send', array('model' => $model) );
				return;
			}
		}
		$this->render('passrecovery/password_recovery_email', array('model' => $model) );		
	}

	public function actionSections() {
		$this->render ( 'index' );
	}

	public function actionSection() {
		$_alias = '';
		foreach ( $_GET as $key => $val ) {
			$_alias = $key;
			break;
		}
		$article_data = ContentCategs::model ()->with ( array ('lang_data' => array ('joinType' => 'LEFT JOIN', 'condition' => 'ccl_lang = "' . Yii::app ()->language . '"' ) ) )
			->find ( 'cc_alias=:cc_alias AND cc_active = 1', array (':cc_alias' => $_alias ) );
		
		if ( $article_data === NULL )
			throw new CHttpException ( 404, 'неверный запрос' );
		$article_data = $this->compile_article ( $article_data );
		
		$criteria = new CDbCriteria ();
		$criteria->order = 'ca_id DESC';
		$criteria->condition = 'ca_active=1 AND ca_cat_id =' . ( int ) $article_data['cc_id'];
		
		$items = ContentArticles::model ()->with ( array ('lang_data' => array ('joinType' => 'LEFT JOIN', 'condition' => 'cal_lang = "' . Yii::app ()->language . '"' ) ) )
			->findAll ( $criteria );
		
		$items = $this->compile_items ( $items, 'lang_data', array ('ca_alias', 'cal_title', 'cal_text' ) );
		
		$use_form = '';
		if ( $_alias == 'entertainment' )
			$use_form = $this->buildUserSideArticleForm ( 'Add joke', array ('cal_text' => 'Joke text' ), ( int ) $article_data['cc_id'] );
		
		$this->flash_rolik = $_alias;
		$this->render ( 'section', array ('article' => $article_data, 'items' => $items, 'use_form' => $use_form ) );
	}

	public function actionArticle($_alias) {
		$article_data = ContentArticles::model ()->with ( array ('lang_data' => array ('joinType' => 'LEFT JOIN', 'condition' => 'cal_lang = "' . Yii::app ()->language . '"' ) ) )
			->find ( 'ca_alias=:ca_alias AND ca_active = 1', array (':ca_alias' => $_alias ) );
		
		if ( $article_data === NULL )
			throw new CHttpException ( 404, 'неверный запрос' );
		
		$this->flash_rolik = $_alias;
		$article_data = $this->compile_article ( $article_data );
		
		if ( file_exists ( $this->viewPath . DIRECTORY_SEPARATOR . 'specials' . DIRECTORY_SEPARATOR . $_alias . '.php' ) ) {
			$this->render ( 'specials/' . $_alias, array ('article' => $article_data ) );
		} else {
			$this->render ( 'article', array ('article' => $article_data ) );
		}
	}

	public function compile_article($article_data, $lang_prefix = 'lang_data') {
		$return = array ();
		
		foreach ( $article_data as $key => $val ) {
			$return[$key] = $val;
		}
		
		if ( isset ( $article_data[$lang_prefix] ) && is_array ( $article_data[$lang_prefix] ) && isset ( $article_data[$lang_prefix][0] ) ) {
			foreach ( $article_data[$lang_prefix][0] as $key => $val ) {
				$return[$key] = $val;
			}
		}
		
		return $return;
	}

	public function buildSelectArray($items, $val_column, $show_column, $relation_name = 'lang_data') {
		$items_done = array ();
		if ( ! is_array ( $items ) )
			return $items_done;
		
		foreach ( $items as $key => $arr ) {
			$prepared_item = array ('key' => false, 'val' => false );
			foreach ( $arr as $sub_key => $val ) {
				if ( $sub_key == $val_column )
					$prepared_item['key'] = $val;
				// echo "$sub_key => $val<br/>";
			}
			if ( $relation_name && isset ( $arr[$relation_name] ) && is_array ( $arr[$relation_name] ) && isset ( $arr[$relation_name][0] ) ) {
				foreach ( $arr[$relation_name][0] as $sub_key => $val ) {
					if ( $sub_key == $show_column )
						$prepared_item['val'] = $val;
					// echo "2) $sub_key => $val<br/>";
				}
			}
			if ( $prepared_item['key'] && $prepared_item['val'] )
				$items_done[$prepared_item['key']] = $prepared_item['val'];
		}
		return $items_done;
	}

	public function getAliasData($alias, $finish_on_error = true) {
		$alias_data = explode ( '-', $alias, 2 );
		if ( (! is_array ( $alias_data ) || (is_array ( $alias_data ) && count ( $alias_data ) < 2)) && $finish_on_error ) {
			throw new CHttpException ( 404, 'неверный запрос' );
			Yii::app ()->end ();
		}
		return $alias_data;
	}

	public function checkAliasChange($article_alias, $get_alias) {
		$alias_data = $this->getAliasData ( $article_alias );
		if ( isset ( $alias_data[1] ) ) {
			if ( $alias_data[1] !== $get_alias ) {
				$redir_params = $this->id . '/' . $this->action->id;
				Yii::app ()->request->redirect ( $this->createUrl ( $redir_params, array ('_alias' => $article_alias ) ), true, 301 );
			}
		}
	}

	public function buildUserSideArticleForm($h1, $used_fields, $cat_id) {
		if ( Yii::app ()->user->hasFlash ( 'adding_article' ) ) {
			$msg = Yii::app ()->user->getFlash ( 'adding_article' );
			$return = $this->renderPartial ( 'adding_form_success', array ('h1' => $h1, 'msg' => 'Added succsessull' ), true );
			return $return;
		}
		
		$cat_id = ( int ) $cat_id;
		$action_type = 'add';
		$model = new ContentArticles ();
		$model->ca_cat_id = $cat_id;
		$model->ca_active = 0;
		$model->ca_alias = 'comment' . uniqid ();
		$lang_forms = array ();
		foreach ( Yii::app ()->params['languages'] as $key => $name ) {
			$lang_forms[$key] = new ContentArticlesLang ();
			$lang_forms[$key]->cal_lang = $key;
			$lang_forms[$key]->cal_title = 'Запись пользователя сайта';
			if ( isset ( $_POST['ContentArticlesLang'] ) ) {
				foreach ( $_POST['ContentArticlesLang'] as $post_key => $post_val ) {
					if ( isset ( $used_fields[$post_key] ) )
						$lang_forms[$key]->{$post_key} = $post_val;
				}
			}
			if ( $key == $this->_languege )
				$lang_form = $lang_forms[$key];
		}
		$form_errors = '';
		
		if ( isset ( $_POST['user_comment'] ) ) {
			$valid = true;
			foreach ( $_POST as $val ) {
				if ( is_array ( $val ) ) {
					foreach ( $val as $sub_val )
						if ( ! $sub_val )
							$valid = false;
				} elseif ( ! $val )
					$valid = false;
			}
			if ( $valid && isset ( $_POST['ContentArticlesLang'] ) ) {
				$model->lang_data = $lang_forms;
				if ( $model->saveWithRelated ( 'lang_data' ) ) {
					$this->logg_system_journal ( $model, $_POST['user_name'], $action_type );
					Yii::app ()->user->setFlash ( 'adding_article', 'added_element' );
					$this->redirect ( $_SERVER['REQUEST_URI'], true, 301 );
				}
			} else {
				$form_errors = 'Not all fields entered';
			}
		}
		
		$return = $this->renderPartial ( 'adding_form', array ('model' => $model, 'h1' => $h1, 'lang_form' => $lang_form, 'used_fields' => $used_fields, 'form_errors' => $form_errors ), true );
		
		return $return;
	}

	public function build_fileds_from_model($model, $form, $sub_key = 0, $used_fields = array()) {
		if ( method_exists ( $model, 'selectValues' ) )
			$relations = $model->selectValues ();
		else
			$relations = array ();
		
		foreach ( $model->attributeLabels () as $key => $val ) {
			
			if ( count ( $used_fields ) && ! isset ( $used_fields[$key] ) )
				continue;
			
			$field_type = $model->fieldtypes ( $key );
			$form_elem_name = $key;
			if ( $sub_key )
				$form_elem_name = '[' . $sub_key . ']' . $form_elem_name;
			
			if ( $used_fields[$key] )
				$label = '<label class="control-label" for="' . $form_elem_name . '">' . Yii::t ( 'main', $used_fields[$key] ) . '</label>';
			else
				$label = $form->labelEx ( $model, $form_elem_name, array ('class' => 'control-label' ) );
			
			$user_name_val = '';
			if ( isset ( $_POST['user_name'] ) )
				$user_name_val = $_POST['user_name'];
			
			$return = '<div class="control-group">
						<label class="control-label" for="user_name">' . Yii::t ( 'main', 'Your name' ) . '</label>
						<div class="controls">
						<input type="text" value="' . $user_name_val . '" name="user_name" id="user_name" />' . '</div>
					</div>';
			
			switch ($field_type) {
				case 'HiddenField' :
					$return .= $form->{$field_type} ( $model, $form_elem_name );
					break;
				default :
					$return .= '
					<div class="control-group">
						' . $label . '<div class="controls">' . $form->TextArea ( $model, $form_elem_name ) . '</div>
					</div>';
					break;
			}
		}
		return $return;
	}

	public function logg_system_journal($model, $user_name, $action) {
		$log = new SysLogs ();
		$log->sl_user_id = $this->register_sender ( $user_name );
		$log->sl_action = $action;
		$log->sl_table_id_row = $model->primaryKey;
		$log->sl_table_name = $model->tableName ();
		$log->sl_date = new CDbExpression ( 'NOW()' );
		$log->save ();
	}

	public function register_sender($name) {
		$model = new Users ();
		$model->u_email = $name . '_' . uniqid ();
		$model->u_pass = $model->u_pass_confirm = 'not_registred_user';
		$model->u_access_level = 0;
		if ( $model->save () )
			return $model->u_id;
		else
			return 0;
	}

	public function countSklonenie($numb, $variants) {
		$numb = abs ( $numb );
		if ( ! $numb )
			return false;
		if ( ! is_array ( $variants ) )
			return $variants;
		if ( count ( $variants ) < 3 )
			$variants += array_fill ( 0, 3 - count ( $variants ), $variants[0] );
			
			// Множество
		$numb += 100;
		$numb = $numb % 100;
		if ( $numb > 10 && $numb < 15 )
			return $variants[0];
			
			// Несколько
		$numb = $numb % 10;
		if ( $numb > 1 && $numb < 5 )
			return $variants[1];
			
			// Одиночное
		if ( $numb == 1 )
			return $variants[2];
			
			// Иначе множество
		return $variants[0];
	}

	public function buildBreadcrumbs() {
		$bread_path = str_replace ( $this->id, 'site', $this->viewPath );
		$breadcrumbs = $this->breadcrumbs;
		$breadcrumbs_last = $this->breadcrumbs_last;
		ob_start();
		require $bread_path.DIRECTORY_SEPARATOR.'breadcrumbs.php';
		$result = ob_get_clean();
		return $result;
	}

	public function buildPersonalMenu($isMenuDisabled = false, $step = 1) {
		$path = str_replace($this->id, 'site', $this->viewPath);
		ob_start();
		require $path.DIRECTORY_SEPARATOR.'personalMenu.php';
		$result = ob_get_clean();
		return $result;
	}
}