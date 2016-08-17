<?php

/**
 * This is the model class helper for work with site urls.
 *
 */
class AdminHelper extends CModel
{
	public function attributeNames() {
		return '';
	}
	
	public function checkAccess( $access_level = 3 ) {
		
		if ( Yii::app()->user->isGuest ) {
			Yii::app()->user->setFlash('message', Yii::t('main', 'Authorisation requared'));
			return false;
		}
		$user = Users::model()->findByPk(Yii::app()->user->id);
		if ( !is_null($user) && $user->u_session != session_id() ) {
			Yii::app()->user->logout();
			Yii::app()->user->setFlash('message', Yii::t('main', 'Authorisation was in other place'));
			return false;
		}
		
		if ( Yii::app()->user->access_level < $access_level ) {
			Yii::app()->user->setFlash('message', Yii::t('main', 'Not alowed for this user'));
			return false;
		}
		return true;
	}
	
	public static function primaryKeyAlias( $db_object, $alias_fld_name, $max_len = 100 ) {
		$primary_key = $db_object->getPrimaryKey();
		$db_object = $db_object->findByPk($primary_key);
		$alias = $db_object->{$alias_fld_name};
		if ( !$primary_key || !$alias ) return;
		
		$alias_start = $primary_key.'-';
		if ( strpos($alias, $alias_start) !== 0 ) {
			$alias = substr($alias_start.$alias, 0, 99);			
			$db_object->{$alias_fld_name} = $alias;
			$db_object->save();
		}
	}
}
