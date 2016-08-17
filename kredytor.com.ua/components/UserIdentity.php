<?php


use yii\web\IdentityInterface;
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity implements IdentityInterface
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;
	
	public function getId()
    {
        return $this->_id;
    }
	
	public function authenticate()
	{
		$record=Users::model()->findByAttributes(array('u_email'=>$this->username, 'u_active'=>1));
		if ($record===null) 
			$record=Users::model()->findByAttributes(array('u_login'=>$this->username, 'u_active'=>1));
		
		if($record===null) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
		}else if($record->u_pass !== crypt($this->password,$this->password)) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
		}else
        {
            $this->_id=$record->u_id;
            $this->setState('id', $record->u_id);
            $this->setState('access_level', $record->u_access_level);
            $this->setState('name', strtolower($record->u_email));
			$this->setState('passneedchange', $record->u_passneedchange);
			$this->setState('filters', array());
            $this->errorCode=self::ERROR_NONE;	
        }
        return !$this->errorCode;
		
	}

	public function getisMenuDisabled() {
		if ( $this->iid ) {
			$state = Yii::app()->db->createCommand('SELECT state FROM zayavka WHERE iid='.$this->iid.' ORDER BY id DESC')->queryRow();
		}

		if ( empty($state) || $state['state'] < 1 || $state['state'] == 7) {
			return true;
		}
		return false;
	}


}