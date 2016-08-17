<?php

class TempZayavkaHelper {
	
	
	/*
	* Устанавливает параметры для временной заявки
	* @vals array - массив параметров ключ->значение
	* @bySessionOnly - выбор модели без учета логина пользователя
	*/
	public static function setTempZayavkaVals($vals, $bySessionOnly=false) {
		$model = static::getTempZayavka($bySessionOnly);
		if ( !$model ) return false;
		foreach ( $vals as $key=>$val ) {
			$model->$key = $val;
		}
		$model->session = session_id();
		if ( !Yii::app ()->user->isGuest ) {
            $model->t_uid = Yii::app()->user->id;
        }
		$result = ( $model->validate() && $model->save() );
		return $result;
	}
	
	/*
	* Возвращает обьект TempZayavka для пользователя сайта
	*/
	public static function getTempZayavka($bySessionOnly=false, $byUseridOnly=false) {
		if ( !Yii::app ()->user->isGuest && !$bySessionOnly ) {
			//если пользователь залогинен и получаем не по сессии
			$model = TempZayavka::model()->findByAttributes(array('t_uid' => Yii::app()->user->id));
			if ( !empty($model) ) return static::varifyOldModel($model); //если есть временная заявка, но она старая создаем новую, возвращаем
		}

		if ( $bySessionOnly )		//если получаем по сессия (залогинен или нет, не важно)
			$seesionid = (int)$bySessionOnly;
		else //если не залогинен и нет сессии то присваиваем текущую сессию
			$seesionid = session_id();
		$model = TempZayavka::model()->findByAttributes(array('session' => $seesionid));

		if ( !empty($model) ) return static::varifyOldModel($model); //возвращаем завку по сессии, если она не старая, или новую, если старая

		if ($bySessionOnly) return false; //если нет временной заявки по сессии то возвращаем false
		
		$model = new TempZayavka();
		return $model;
	}
	
	/*
	* При данных старше 2х дней, они обнуляются
	*/
	public static function varifyOldModel($model) {
		if ( $model instanceof TempZayavka && ((time() - strtotime($model->dateStart)) / (24 * 60 * 60) > 1) ) {
			$model->delete();
			$model = new TempZayavka();
		}
		return $model;
	}
	
	/*
	* Обновление временной записи ключем id пользователя
	*/
	public static function updateByUserId($sessionId) {
		if ( Yii::app ()->user->isGuest ) return false;
        $current = static::getTempZayavka(false, true);
        $new = static::getTempZayavka($sessionId, false);

        if(!empty($new)){
            if(!empty($current) && $current->t_id != $new->t_id){
				try {
					$current->delete();
				} catch (ExceptionHelper $e) {
				}
            }
            $new->session = session_id();
            $new->t_uid = Yii::app()->user->id;
            $result = ( $new->validate() && $new->save() );
            return $result;
        }
	}

	public static function get_fp_params($zayavka_invoice){
		if ( empty($zayavka_invoice) )
			return false;
		$data = Yii::app()->db->createCommand('SELECT sum(prod_firstpay_rec) as prod_firstpay_min, sum(prod_firstpay_real) as prod_firstpay_real, sum(prod_price) as prod_total FROM `zayavka_invoice_prods` WHERE prod_iid='.$zayavka_invoice)->queryRow();
		return $data;
	}

}
?>