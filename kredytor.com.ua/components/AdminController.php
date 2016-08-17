<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminController extends Controller
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/admin';
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
	
	public $renderview = true;
	public $helper;
	
	/**
	 * @var $action_accsess_level int Минимальный уровень доступа пользователей
	 */
	public $action_accsess_level = 2;
	
	/**
	 * @var $cur_page int Страница списков по умолчанию
	 */
	public $cur_page = 1;
	
	/**
	 * @var $per_page int Кол-во элементов отображаемое на страницу в списках
	 */
	public $per_page = 20;
	
	
	public function gotoLoginPage() {
		if ( !empty(Yii::app()->user->id) )
			$this->redirect( '/admin/Main/accsesserror' );
		else 
			$this->redirect( '/admin/Main/login' );
		Yii::app()->end();
	}
	
	public function init() {
		//$domain = $_SERVER['HTTP_HOST'];
		//if ( $domain !== Yii::app()->params['base_domain'] ) throw new CHttpException(404, 'Not found');
		Yii::app()->params['mobile_sub_categ'] = '';
		$this->helper = new AdminHelper();
		Yii::app()->user->loginUrl = 'adminMain/login';
		Yii::app()->user->returnUrl = 'adminMain/index';	
		Yii::app()->user->allowAutoLogin = false;
		if ( isset($_GET['page']) && (int)$_GET['page'] ) $this->cur_page = (int)$_GET['page'];
		Yii::app()->errorHandler->errorAction = 'AdminMain/error';
		parent::init();
	}
	
	protected function beforeAction($action) {		
		$this->checkPasswordChange($action);
		return parent::beforeAction($action);
	}
	
	public function buildPaginationCriteria($addCondition=false, $get=false) {
		$criteria = new CDbCriteria;
		$criteria->limit = $this->per_page;
		$criteria_cindition = array();
		$criteria_params	= array();
		if(!$get)
			$get = $_GET;
		
		//Кол-во к выводу на странице						
		if ( !empty($get['perpageshow_select']) ) {
			if ( (int)$get['perpageshow_select'] > 0 )
				$criteria->limit = $this->per_page = (int)$get['perpageshow_select'];
			else
				$this->per_page = 9999999999999999999;
		}
		Yii::app()->user->setState('perpageshow_select', $this->per_page);
		
		foreach ( $get as $key=>$val ) {
			$is_filter_param = explode('_', $key, 2);
			if ( is_array($is_filter_param) && count($is_filter_param) == 2 && $is_filter_param[0] === 'filtering' ) {
				if ( $is_filter_param[1] && $val != '-1' ) {
					$criteria_cindition[] = $is_filter_param[1].'=:'.$is_filter_param[1];
					$criteria_params[':'.$is_filter_param[1]] = $val;
				}
			} elseif ( is_array($is_filter_param) && count($is_filter_param) == 2 && $is_filter_param[0] === 'textfiltering' &&  stripos($is_filter_param[1], '_start')===false &&  stripos($is_filter_param[1], '_finish')===false ) {
				if ( $is_filter_param[1] && $val != '' ) {
					$criteria_cindition[] = $is_filter_param[1].'=:'.$is_filter_param[1];
					$criteria_params[':'.$is_filter_param[1]] = $val;
				}
			} elseif ( is_array($is_filter_param) && count($is_filter_param) == 2 && $is_filter_param[0] === 'likefiltering'  ) {
			    if ( $is_filter_param[1] && $val != '' ) {
                $criteria_cindition[] = $is_filter_param[1].' LIKE "%'.$val.'%"';
                }
		    } elseif ( is_array($is_filter_param) && count($is_filter_param) == 2 && $is_filter_param[0] === 'textfiltering' &&  stripos($is_filter_param[1], '_start') ) {
				if ( $is_filter_param[1] && $val != '' ) {
					$date_form = explode( '.', $val );
					$criteria_cindition[] = str_replace("_start", "", $is_filter_param[1]).' >= "'.$date_form[2].'-'.$date_form[1].'-'.$date_form[0].'"';
				}
			} elseif ( is_array($is_filter_param) && count($is_filter_param) == 2 && $is_filter_param[0] === 'textfiltering'  &&  stripos($is_filter_param[1], '_finish')) {
				if ( $is_filter_param[1] && $val != '' ) {
					$date_form = explode( '.', $val );
					$criteria_cindition[] = str_replace("_finish", "", $is_filter_param[1]).' <= "'.$date_form[2].'-'.$date_form[1].'-'.$date_form[0].' 23:59:59"';
				}
			} elseif ( is_array($is_filter_param) && count($is_filter_param) == 2 && $is_filter_param[0] === 'idfiltering' ) {
                if ( $is_filter_param[1] && $val != '' ) {
                    $multi_val = explode(',', str_replace (array(' ','.'), array('',','), $val));
                    foreach($multi_val as $value) {
                         $like_condition[] = $is_filter_param[1] . ' LIKE "%Request id=\"' . $value . '\"%"';
                    }
                    $criteria_cindition[] = '('.implode(' OR ', $like_condition).')';
                }
            }
		}
		
		if ( !empty(Yii::app()->user->perpageshow_select) ) {
			$criteria->limit = $this->per_page = (int)Yii::app()->user->perpageshow_select;
		}

		if ( count($criteria_cindition) ) {
			$criteria->condition = implode(' AND ', $criteria_cindition);
			$criteria->params = $criteria_params;
		}
		if ( $addCondition ) {
			if ( $criteria->condition )
				$criteria->condition.= ' AND ';
			$criteria->condition.= $addCondition;
		}
		
		if ( $this->cur_page > 1 ) $criteria->offset = ($this->cur_page-1) * $this->per_page;
		return $criteria;
	}
	
	public function buildEditCriteria( $elem_id ) {
		$elem_id = (int)$elem_id;
		$criteria = new CDbCriteria;
		//'c_admin=:c_admin AND c_id=:c_id', array(':c_admin' => Yii::app()->user->id, ':c_id' => (int)$_GET['id'] )
		
		if ( $this->cur_page > 1 ) $criteria->offset = ($this->cur_page-1) * $this->per_page;
		return $criteria;
	}
	
	public function buildUserCriteria() {
		$criteria = new CDbCriteria;
		if ( Yii::app()->user->access_level < 9 ) {
			$criteria->condition='c_admin=:c_admin';
			$criteria->params=array(':c_admin'=>Yii::app()->user->id);
		}
		return $criteria;
	}

	public function buildPagination( $total_results = 0 ) {
		$return = '';
		$pages_count = $total_results/$this->per_page;
		if ( $pages_count != (int)$pages_count ) $pages_count++;
		if ( $pages_count < 2 ) return $return;

		//доп парметры в ссылки для фильтра
		$filter_links_addon = '';
		$curr_url = $_SERVER['REQUEST_URI'];
		$filter_params = explode('?', $_SERVER['REQUEST_URI'], 2);
		if ( is_array($filter_params) && isset($filter_params[1]) ) $filter_links_addon = '?'.$filter_params[1];

		$return = '<div class="pagination pagination-small pagination-centered"><ul>';

		//назад
		if ( $this->cur_page == 1 ) $return.= '<li class="disabled"><span>«</span></li>';
		elseif ( $this->cur_page == 2 ) $return.= '<li><a href="'.$this->createUrl($this->id.'/'.$this->action->id).$filter_links_addon.'">«</a></li>';
		else $return.= '<li><a href="'.$this->createUrl($this->id.'/'.$this->action->id, array('page'=>($this->cur_page-1))).$filter_links_addon.'">«</a></li>';

		for ( $i = 1; $i <= $pages_count; $i++  ) {
			if ( $this->cur_page == $i ) $return.= '<li class="disabled"><span>'.$i.'</span></li>';
			elseif ( $i == 1 ) $return.= '<li><a href="'.$this->createUrl($this->id.'/'.$this->action->id).$filter_links_addon.'">1</a></li>';
			else $return.= '<li><a href="'.$this->createUrl($this->id.'/'.$this->action->id, array('page'=>$i)).$filter_links_addon.'">'.$i.'</a></li>';
		}

		//вперед
		if ( $this->cur_page == $pages_count ) $return.= '<li class="disabled"><span>»</span></li>';
		else $return.= '<li><a href="'.$this->createUrl($this->id.'/'.$this->action->id, array('page'=>($this->cur_page+1))).$filter_links_addon.'">»</a></li>';

		$return.= '</ul></div>';
		return $return;
	}
	
	/**
	 * Функция преобразования результатов реляционного запроса в двухмерный ассоциативный масив.
	 * Возвращаються только столбцы по ключам масива $column_names
	 * 
	 * @param array $items - результат реляционного запроса
	 * @param type $relation_name - имя связи запроса
	 * @param type $column_names - возвращаемые столбцы по ключам
	 * @return array
	 */
	public static function compile_items( $items, $relation_name, $column_names = array(), $select_vals = array(), $images_rows = array(), $links_rows = array() ) {
		$items_done = array();
		if ( !is_array($items) ) return $items_done;
		
		foreach ( $items as $key => $arr ) {
			$prepared_item = array();			
			
			foreach ( $arr as $sub_key => $val ) {
				if ( isset($select_vals[$sub_key]) && isset($select_vals[$sub_key][$val]) ) $val = $select_vals[$sub_key][$val];
				if ( in_array($sub_key, $images_rows) ) $val = '<a href = "'.$val.'" target = "_blank"><img src = "'.$val.'" /></a>';
				if ( in_array($sub_key, $links_rows) )  $val = '<a href = "https://'.$val.'" target = "_blank">'.$val.'</a>';
				if ( isset($column_names[$sub_key]) ) $sub_key = Yii::t('main', $column_names[$sub_key]);
				else continue; 
				$prepared_item[$sub_key] = $val;
			}
						
			if ( $relation_name && isset($arr[$relation_name]) ) {
				
				if ( is_array($arr[$relation_name]) && isset($arr[$relation_name][0]) ) {
					foreach ( $arr[$relation_name][0] as $sub_key => $val ) {
						if ( isset($select_vals[$sub_key]) && isset($select_vals[$sub_key][$val]) ) $val = $select_vals[$sub_key][$val];
						if ( in_array($sub_key, $images_rows) ) $val = '<a href = "'.$val.'" target = "_blank"><img src = "'.$val.'" /></a>';
						if ( in_array($sub_key, $links_rows) )  $val = '<a href = "https://'.$val.'" target = "_blank">'.$val.'</a>';
						if ( isset($column_names[$sub_key]) ) $sub_key = Yii::t('main', $column_names[$sub_key]);
						else continue;
						$prepared_item[$sub_key] = $val;
					}
				}elseif ( is_object($arr[$relation_name]) ) {
					foreach ($column_names as $sub_key => $val) {
						if ( isset($arr[$relation_name]->{$sub_key}) ) {
							$prepared_item[Yii::t('main', $column_names[$sub_key])] = $arr[$relation_name]->{$sub_key};
						}
					}
				}
			}
			
			if ( isset($column_names[0]) && !empty($arr->{$column_names[0]}) )
				$key = $arr->{$column_names[0]};
			$items_done[$key] = $prepared_item;			
		}			
		
		return $items_done;		
	}
	
	public function buildSelectArray ( $items, $val_column, $show_column, $relation_name = 'lang_data', $not_used_vals=array(), $zeroval=true ) {
		$items_done = array();
		if ( $zeroval )
			$items_done[0] = Yii::t('main', 'Not selected');
		
		if ( !is_array($items) ) return $items_done;
		
		foreach ( $items as $key => $arr ) {
						
			$prepared_item = array(
				'key' => false,
				'val' => false
			);
			foreach ( $arr as $sub_key => $val ) {
				if ( $sub_key == $val_column ) $prepared_item['key'] = $val;
				//echo "$sub_key => $val<br/>";
			}
			
			if ( in_array($prepared_item['key'], $not_used_vals) ) continue;
			
			if ( $relation_name && 
				isset($arr[$relation_name]) && is_array($arr[$relation_name]) &&
				isset($arr[$relation_name][0])
			) {
				foreach ( $arr[$relation_name][0] as $sub_key => $val ) {
					if ( $sub_key == $show_column ) $prepared_item['val'] = $val;
					//echo "2) $sub_key => $val<br/>";
				}
			}
			if ( $prepared_item['key'] && $prepared_item['val'] ) $items_done[$prepared_item['key']] = $prepared_item['val'];
		}
		return $items_done;
	}
	
	public function build_fileds_from_model ( $model, $form, $sub_key = 0 ) {
		$return = '';
		if ( method_exists($model, 'selectValues') )
			$relations = $model->selectValues();
		else 
			$relations = array();

		foreach ( $model->attributeLabels() as $key => $val ) {
			$return.= $this->build_field_model($model, $form, $key, $val, $relations, $sub_key);				
		}
		return $return;		
	}
	
	public function build_field_model ($model, $form, $key, $val = null, $relations = array(), $sub_key = 0) {
		$return = '';
		if ( is_null($val) ) {
			$val = $model->{$key};
		}
		if ( empty($relations) && method_exists($model, 'selectValues') ) {
			$relations = $model->selectValues();
		}
		
		$field_type = $model->fieldtypes($key);
		$form_elem_name = $key;
		if ( $sub_key ) $form_elem_name = '['.$sub_key.']'.$form_elem_name;
			
		$form_elem_real_name = '['.$key.']';
		if ( $sub_key ) $form_elem_real_name = '['.$sub_key.']['.$key.']';
		$form_elem_real_name = get_class($model).$form_elem_real_name;
		
		$label = '';
		switch ( $field_type ) {
			case 'HiddenField' :
				$return.= $form->{$field_type}($model, $form_elem_name );
				break;
				
			case 'DateField' :
				if ($model->{$form_elem_name} == 'NOW()' || $model->{$form_elem_name} == 'NULL')
					$model->{$form_elem_name} = '';
				$return .= '
				<div class="control-group">
					' . $form->labelEx ( $model, $form_elem_name, array ('class' => 'control-label' ) ) . 
					'<div class="controls input-append datepickertime">' . 
						$form->TextField ( $model, $form_elem_name, array (
							'class' => 'datepicker',
							'readonly' => 'readonly' 
							) ) . '<span class="add-on"><i class="icon-calendar"></i></span>' . $form->error ( $model, $key ) . '</div>
					</div>';
				break;				
			case 'DateYearsField' :
				if ( $model->{$form_elem_name} == 'NOW()' || $model->{$form_elem_name} == 'NULL' )
					$model->{$form_elem_name} = '';
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls input-append datepickertimeyears">'.
						$form->DateField($model, $form_elem_name, array('class'=>'datepicker') ).
						'<span class="add-on"><i class="icon-calendar"></i></span>'.
						$form->error($model, $key).
					'</div>
				</div>';
				break;
			case 'DisabledField' :
				if ( isset($relations[$key]) ) {
					$items = $this->build_field_select_vals( $model, $relations[$key] );
				}
					
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls">'.
						$form->DropDownList($model, $form_elem_name, $items, array('readonly'=>'readonly', 'disabled'=>'disabled')).
						$form->error($model, $key).
					'</div>
				</div>';
				break;
			case 'DropDownList' :
				$items = array(0=>(Yii::t('main', 'Not selected')));				
				if ( isset($relations[$key]) ) {
					$items = $this->build_field_select_vals( $model, $relations[$key] );
				}
					
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls">'.
						$form->{$field_type}($model, $form_elem_name, $items, array('class'=>'autocomplete')).
						$form->error($model, $key).
					'</div>
				</div>';
				break;
			case 'FileField':
					
				$file_val = $file_val_pre = '';
				$model_field_php5_2		= $model->{$key};
				$model_errors_php5_2	= $model->getErrors();
				if ( $model_field_php5_2 && !isset($model_errors_php5_2[$key]) ) {
					$file_val = $model->{$key};
					$file_val_pre = '<a href = "'.$model->{$key}.'" target = "_blank"><img src = "'.$model->{$key}.'" class = "input_file_pic"></a>';
				}
				
				$field_save_name = $key.'_save';
				if ( $sub_key ) $field_save_name = $sub_key.'_'.$key.'_save';;
				$field_save_name = get_class($model).'_'.$field_save_name;
					
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls">'.
						$form->{$field_type}($model, $form_elem_name ).
						'<input type = "hidden" class="file_save_version" name = "'.$field_save_name.'" value = "'.$file_val.'">'.
						$form->error($model, $key).
						$file_val_pre.
						'<div class = "delete_file_part"> <input type = "checkbox" value = "1" name = "'.$key.'_delete">'.
							'<span class="del_file">'.Yii::t('main', 'Delete file').'</span>&nbsp;&nbsp;&nbsp;<span class="browse_file"><a href="#" class="browse_file">Посмотреть на сервере</a></span>
							    <div class="alert alert-block hide">
									<h4>Список файлов</h4>
									<div class="filetreecontainer empty">
									</div>
								</div>
						</div>'.
					'</div>
				</div>';
				break;
			case 'TextAreaSimpleEditor':
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls">'.
						$form->TextArea($model, $form_elem_name ).
						$form->error($model, $key).
					'</div>
				</div>';
				break;
			case 'TextAreaSimpleEditorRedactor':
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls">'.
						$form->TextArea($model, $form_elem_name ).
						$form->error($model, $key).
					'</div>
				</div>
				<script type="text/javascript"><!--
				var oFCKeditor = new FCKeditor("'.$form_elem_real_name.'");
				oFCKeditor.BasePath = "/bootstrap/js/fckeditor/";
				oFCKeditor.Config["CustomConfigurationsPath"] = "../FCKeditorConfig.js" ;
				oFCKeditor.ToolbarSet = "ToolbarSmall" ;
				oFCKeditor.Width = "80%";
				oFCKeditor.Height = "100";
				oFCKeditor.Config["EnterMode"] = "br";
				oFCKeditor.Config["ShiftEnterMode"] = "p";
				oFCKeditor.ReplaceTextarea();
				//-->
				</script>';
				break;
			case 'TextArea':
				if ( !$model->{$key} ) $model->{$key} = '<br />';
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls">'.
						$form->{$field_type}($model, $form_elem_name ).
						$form->error($model, $key).
					'</div>
				</div>
				<script type="text/javascript"><!--
				var oFCKeditor = new FCKeditor("'.$form_elem_real_name.'");
				oFCKeditor.BasePath = "/bootstrap/js/fckeditor/";
				oFCKeditor.Config["CustomConfigurationsPath"] = "../FCKeditorConfig.js" ;
				oFCKeditor.ToolbarSet = "ToolbarMain" ;
				oFCKeditor.Width = "100%";
				oFCKeditor.Height = "220";
				oFCKeditor.Config["EnterMode"] = "br";
				oFCKeditor.Config["ShiftEnterMode"] = "p";
				oFCKeditor.ReplaceTextarea();
				//-->
				</script>
				';
				break;
			case 'YaMapCoords':
				$return.= '
				<div class="control-group">
					<a href = "'.$val.'" onClick="getCoord(this); return false;" class="YaMapCoords">Определить</a>
				</div>';
				break;
			case 'YaMapCheck':
				$return.= '
				<div class="control-group">
					<a href = "'.$val.'" onClick="getCheckCoord(this);" target="_blank" class="YaMapCoords">Проверить</a>
				</div>';
				break;
			case 'DisabledText':
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls">'.
						$form->TextField($model, $form_elem_name, array('disabled'=>'disabled')  ).
						$form->error($model, $key).
					'</div>
				</div>';
				break;
			default :
				$return.= '
				<div class="control-group">
					'.$form->labelEx($model, $form_elem_name, array('class'=>'control-label') ).
					'<div class="controls">'.
						$form->{$field_type}($model, $form_elem_name ).
						$form->error($model, $key).
					'</div>
				</div>';
				break;
		}
		return $return;
	}
	
	public function build_field_select_vals( $model, $relations_data ) {
		
		$return = array();
		if ( $relations_data[0] == 'DoneVals' ) {
			foreach( $relations_data[1] as $key=>$val ) {
				$return[$key] = $val;
			}
			return $return;
		}
		elseif ( is_array($relations_data[1]) ) {
			$relation_params = array();
			foreach ( $relations_data[1] as $temp )
				$relation_params[] = $temp;
		}else 
			$relation_params = array($relations_data);
						
		foreach ( $relation_params as $relation_params_opt ) {
			$use_zero=true;
			if ( isset($relation_params_opt[7]) )
				$use_zero=(bool)$relation_params_opt[7];
			
			$not_used_vals = array();	
			$model_name = $relation_params_opt[1];
			$model_object_php5_2 = new $model_name();
			$condition = array();
			if ( isset($relation_params_opt[6]) ) {
				foreach ( $relation_params_opt[6] as $exept_key=>$exept_val ) {
					if ( is_array($exept_val) ) $exept_val = ' NOT IN ("'.implode('" ,"', $exept_val).'")';
					elseif ( $exept_val ) $exept_val = ' != "'.$exept_val.'"';
					elseif ( is_null($exept_val) ) $exept_val = '!="'.$model->{$exept_key}.'"';
					else continue;
					$condition[]=$exept_key.$exept_val;
				}
			}
			$condition = implode(' AND ', $condition);
			
			if ( $relation_params_opt[3] != $relation_params_opt[1] ) {
				$items = $model_object_php5_2->with(array(
					'lang_data' => array(
						'select'=>$relation_params_opt[4],
						'joinType'=>'LEFT JOIN',
						'condition'=>$relation_params_opt[5].' = "'.Yii::app()->params['def_languege'].'"',
					),
				))->findAll( array('condition'=>$condition, 'order'=>$relation_params_opt[4].' ASC') );
			}else {
				$items = $model_object_php5_2->
					findAll( array('condition'=>$condition, 'order'=>$relation_params_opt[4].' ASC') );
			}

			if ( $items ) $items = $this->buildSelectArray( $items, $relation_params_opt[2], $relation_params_opt[4], 'lang_data', $not_used_vals, $use_zero );
			else $items = array();
			$return[$relation_params_opt[0]] = $items;
		}
		if ( count($return) == 1 ) return current($return);
		/*
		else {
			$temp_return = array();
			foreach ( $return as $key=>$vals_array ) {
				$temp_return[(Yii::t('main', $key))] = array();
				foreach($vals_array as $vals_key=>$vals_val)
					$temp_return[(Yii::t('main', $key))][$key.'___'.$vals_key] = $vals_val;
			}
			$return = $temp_return;
		}
		*/
		return $return;
	}
	
	public function workWithPics( $model, $field_key, $path = 'upload/images', $dimantions = array(800, 600), $quality = 95 ) {
		
		if ( isset($_POST[$field_key.'_delete']) && $_POST[$field_key.'_delete'] ) return false;
		
		$path = $path.'/';
		$pic_data = false;		
				
		if ( $model instanceof CActiveRecord ) {
			$pic_data = CUploadedFile::getInstance($model, $field_key);
		}elseif ( is_array($model) && isset($_FILES[$model[0]]) && isset($_FILES[$model[0]]['tmp_name'][$model[1]][$field_key]) && $_FILES[$model[0]]['tmp_name'][$model[1]][$field_key] ) {
			$pic_data = new PsevdoCUploadedFile();
			$pic_data->tempName = $_FILES[$model[0]]['tmp_name'][$model[1]][$field_key];
			$pic_data->name		= $_FILES[$model[0]]['name'][$model[1]][$field_key];
		}elseif ( isset($_FILES[$field_key]) && is_array($_FILES[$field_key]) && isset($_FILES[$field_key]['tmp_name']) ) {
			$pic_data = new PsevdoCUploadedFile();
			$pic_data->tempName = $_FILES[$field_key]['tmp_name'];
			$pic_data->name		= $_FILES[$field_key]['name'];
		}
		if ( !$pic_data ) {
			if ( is_array($model) ) $fld_name = $model[0].'_'.$model[1].'_'.$field_key.'_save'; //MenuLang_2_ml_image_save
			elseif($model instanceof CActiveRecord) $fld_name = get_class($model).'_'.$field_key.'_save'; //Menu_m_image_save
			else $fld_name = $field_key.'_save'; //Menu_m_image_save
			if ( isset($_POST[$fld_name]) && $_POST[$fld_name] ) return $_POST[$fld_name];
			return false;
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
	
	public function workWithFile( $model, $field_key, $path = 'upload_files') {
		$path = $path.'/';
		$file_data = false;
		if ( $model instanceof CActiveRecord ) {
			$file_data = CUploadedFile::getInstance($model, $field_key);
		}elseif ( isset($_FILES[$field_key]) && is_array($_FILES[$field_key]) && isset($_FILES[$field_key]['tmp_name']) ) {
			$file_data = new PsevdoCUploadedFile();
			$file_data->tempName	= $_FILES[$field_key]['tmp_name'];
			$file_data->name		= $_FILES[$field_key]['name'];
		}
		if ( !$file_data ) {
			if ( isset($_POST[$field_key.'_save']) && $_POST[$field_key.'_save'] ) return $_POST[$field_key.'_save'];
			return false;
		}
		
		$dop_name = 0;
		$up_file = $path.$dop_name.'_'.$file_data->name;
		while ( file_exists($up_file) ) {
			$dop_name++;
			$up_file = $path.$dop_name.'_'.$file_data->name;
		}
		
		move_uploaded_file($file_data->tempName, $up_file);		
		return '/'.$up_file; 
	}
	
	public function logg_system_journal($model, $action) {
		$log = new SysLogs();
		$log->sl_user_id		= Yii::app()->user->id;
		$log->sl_action			= $action;
		$log->sl_table_id_row	= $model->primaryKey;
		$log->sl_table_name		= $model->tableName();
		$log->sl_date			= new CDbExpression('NOW()');
		$log->save();
	}
	
	public function activity_list_items() {
		return array(0=>(Yii::t('main', 'No')), 1=>(Yii::t('main', 'Yes')));
	}
	
	public function redirectBackToList($message = '', $first_page = false) {
		$parent_action = explode('_', $this->action->getId());
		if ( is_array($parent_action) && isset($parent_action[1]) ) $parent_action = $parent_action[1];
		else $parent_action = $this->action->getId();
		$controller_route = str_replace('admin', 'admin/', $this->id);
		$redirect_url = $controller_route.'/'.$parent_action;
		$params = array();
		if ( $this->cur_page > 1 && !$first_page )
			$redirect_url.='/'.$this->cur_page;		
		
		if ($message) 
			Yii::app()->user->setFlash('message', Yii::t('main', $message));
		
		$this->redirect( array($redirect_url) );
		Yii::app()->end();
	}
	
	public function buildAlias( $alias_form_key, $lang_forms_key, $alias_fld_key, $title_fld_key ) {
		$alias = '';
		if ( isset($_POST[$alias_form_key]) && is_array($_POST[$alias_form_key]) && isset($_POST[$alias_form_key][$alias_fld_key]) && isset($_POST[$lang_forms_key]) && is_array($_POST[$lang_forms_key]) ) {
			 
			if ( !$_POST[$alias_form_key][$alias_fld_key] ) { //Создать алиас из полей наименования
				
				if ( $alias_form_key == $lang_forms_key && $_POST[$lang_forms_key][$title_fld_key] ) {
					$_POST[$alias_form_key][$alias_fld_key] = $_POST[$lang_forms_key][$title_fld_key];
				} else {
					foreach ( $_POST[$lang_forms_key] as $lang_form ) {
						if ( isset($lang_form[$title_fld_key]) && $lang_form[$title_fld_key] ) {
							$_POST[$alias_form_key][$alias_fld_key] = $lang_form[$title_fld_key];
							break;
						}
					}	
				}							
			}
			
			$_POST[$alias_form_key][$alias_fld_key] = trim(str_replace('&nbsp;', '', $_POST[$alias_form_key][$alias_fld_key]));
			$_POST[$alias_form_key][$alias_fld_key] = $this->translitarate($_POST[$alias_form_key][$alias_fld_key]);
		}
		return;
	}
	
	public function translitarate( $string ) {
		$chars = array(
			'а' => 'a',
			'б' => 'b',
			'в' => 'v',
			'г' => 'g',
			'д' => 'd',
			'е' => 'e',
			'ё' => 'e',
			'ж' => 'j',
			'з' => 'z',
			'и' => 'i',
			'й' => 'i',
			'к' => 'k',
			'л' => 'l',
			'м' => 'm',
			'н' => 'n',
			'о' => 'o',
			'п' => 'p',
			'р' => 'r',
			'с' => 's',
			'т' => 't',
			'у' => 'u',
			'ф' => 'f',
			'х' => 'h',
			'ц' => 'c',
			'ч' => 'ch',
			'ш' => 'sh',
			'щ' => 'sch',
			'ъ' => '',
			'ы' => 'y',
			'ь' => '',
			'э' => 'e',
			'ю' => 'yu',
			'я' => 'ya',
			'і' => 'i',
			'ї' => 'ji',
			'є' => 'je',
			'ґ' => 'g',
			' ' => '-',
			'x'	=> 'x',
			'w'	=> 'w',
		);
		$converted = strtr(mb_strtolower($string, 'utf8'), $chars);
		$len = mb_strlen($converted);
		$alias = '';
		for ( $i = 0; $i < $len; $i++ ) {
			$char = mb_substr($converted, $i, 1);
			$int_char = (int)$char;
			if ( in_array($char, $chars) || $int_char ) $alias.=$char;
		}
		return $alias;
	}
	
	public function buildAjaxPictureUploader( $action = '', $dop_fld_val = '' ) {
		$return = '';
		//				<form name = "fileuppform" action="'.$action.'" method="post" target="hiddenframe" enctype="multipart/form-data">
		$return.= '
			<div class = "AjaxPictureUploader">
				<form name = "fileuppform" action="'.$action.'" method="post" target="hiddenframe" enctype="multipart/form-data">
					<input type = "hidden" name = "dop_fld_val" value = "'.$dop_fld_val.'" />
					<input type = "file" class = "userfile" name = "userfile" onchange = "this.form.submit();">
				</form>
				<div class = "res"></div>
			</div>';
		return $return;
	}
	
	public function buildParentJsResponse ($jsFunc, $func_param) {
		$func_param = str_replace("'", "\'", $func_param);		
		$return = '<script type="text/javascript">';
		$return.= 'window.parent.'.$jsFunc.'(\''.$func_param.'\');';
		$return.= '</script>';
		return $return;
	}
	
	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	public function checkPasswordChange($action) {
		//var_dump(Yii::app()->user->passneedchange);
		if ( $this->id == 'adminMain' && $this->action->id == 'logout' ) {
			return true;
		}
		
		if ( !Yii::app()->user->isGuest && 
			  !empty(Yii::app()->user->passneedchange) &&
			  $this->id !== 'adminUsers' &&
			  $_GET['id'] != Yii::app()->user->id
			) {
			Yii::app()->user->setFlash('message', Yii::t('main', 'Passwoed needs to be changed'));
			$this->redirect( array('/admin/Users/add_users/id/'.Yii::app()->user->id.'/page/1') );
			Yii::app()->end();
		}
	}
}