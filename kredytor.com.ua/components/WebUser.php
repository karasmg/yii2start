<?php
use yii\web\user;

class WebUser extends User {
	public $_order = array ();
	public $_states = array ();

	public function init() {
		$this->_states = array (
				'isRegistered' => array (
						'val' => null,
						'link' => 'login/'
				),
				'hasAnketa' => array (
						'val' => null,
						'link' => 'anketa/'
				),
				'hasActiveCard' => array (
						'val' => 1,
						'link' => 'personalpage/cards/'
				),
				'canGetCredit' => array (
						'val' => null,
						'link' => 'personalpage/loan/creditstatus/'
				),
				'PaymentConfirmation' => array (
						'val' => null,
						'link' => 'personalpage/loan/getcredit/'
				),
				'smsVerification' => array (
						'val' => null,
						'link' => 'personalpage/loan/paymentconfirmation/'
				),
				'hasActiveCredit' => array (
						'val' => null,
						'link' => 'personalpage/loan/smsverification/'
				),
		);

		parent::init ();

		if ( ! empty ( $_SESSION['_order'] ) ) {
			$this->_order = $_SESSION['_order'];
		}

		if ( ! empty ( $_SESSION['_states'] ) ) {
			$this->_states = $_SESSION['_states'];
		}
		/*
		echo '<pre>';
		var_dump($this->_states);
		echo '</pre>';
		exit(0);
		 * 
		 */
	}


	public function __get($name)
	{
		if ($this->hasState('__userInfo')) {
			$user=$this->getState('__userInfo',array());
			if (isset($user[$name])) {
				return $user[$name];
			}
		}

		return parent::__get($name);
	}

	public function getiid() {
		$anketa = Anketa::model()->findByAttributes(array('site_userId'=>$this->id));
		if (!empty($anketa)) return $anketa->iid;
		return false;
	}

	public function generateBuyBtn( $prod_id ) {
		$prod_id = (int)$prod_id;
		$reserved = Yii::app()->cache->get($prod_id.'_reserved_data');
		if ( $reserved === false ) {
			$data = Yii::app()->db->createCommand('SELECT ca_reserved FROM catalog_articules WHERE ca_id = '.$prod_id)->queryAll();
			$reserved = $data[0]['ca_reserved'];
			Yii::app()->cache->set($prod_id.'_reserved_data', $reserved);
		}
		$reserved = (int)$reserved;
		$in_order = !empty($this->_order[$prod_id]);
		$btn = new BuyBtn($prod_id, $in_order, $reserved);
		return $btn->run();
	}

	public function addProdToBasket( $prod_id ) {
		if ( empty($this->_order[$prod_id]) )
			$this->_order[$prod_id]=1;
		$_SESSION['_order'] = $this->_order;
	}
	public function removeProdFromBasket( $prod_id ) {
		if ( !empty($this->_order[$prod_id]) )
			unset($this->_order[$prod_id]);
		$_SESSION['_order'] = $this->_order;
	}
	public function clearBasket() {
		$this->_order = array();
		$_SESSION['_order'] = $this->_order;
	}

	public function getu_state($stateName) {
		if ( is_null($this->_states[$stateName]['val']) ) {
			$this->_states[$stateName]['val'] = $this->$stateName();
			$_SESSION['_states'] = $this->_states;
		}
		return $this->_states[$stateName]['val'];
	}

	public function checku_state($stateName) {
		if ( !array_key_exists($stateName, $this->_states) ) {
			die('User State Error: key '.$stateName.' not exists');
		}

		if ( $this->isGuest || !$this->getu_state($stateName) ) {
			if( empty(Yii::app()->request->cookies['redirect_cookie']) ) {
				$redirect_cookie = new CHttpCookie('redirect_cookie', Yii::app()->request->requestUri);
				$redirect_cookie->expire = time()+60*5;
				Yii::app()->request->cookies['redirect_cookie'] = $redirect_cookie;
			}
			Yii::app()->getRequest()->redirect(Yii::app()->createUrl($this->_states[$stateName]['link']));
		}
		return true;
	}

	public function isRegistered() {
		if ( !empty($this->id) ) {
			return true;
		} else {
			return false;
		}
	}


	public function hasAnketa() {
		$anketa = Anketa::model()->findByAttributes(array(
				'site_userId' => $this->id,
		));

		if ( !is_null($anketa) ) {
			return true;
		} else {
			return false;
		}
	}

	public function hasActiveCard() {
		$cards = AnketasCards::model()->findAllBySql('SELECT * FROM anketas_cards WHERE card_state>=0 and ac_uid='.$this->id);
		if ( !empty($cards) ) {
			return true;
		} else {
			return false;
		}
	}

	public function canGetCredit() {
		if ( $this->iid ) {
			$state = Yii::app()->db->createCommand('SELECT state FROM zayavka WHERE iid='.$this->iid.' ORDER BY id DESC')->queryRow();
		}
		if ( empty($state) || in_array($state['state'], array(4, 6)) ) {
			return true;
		}
		return false;
	}


	public function PaymentConfirmation() {
		if ( $this->iid ) {
			$state = Yii::app()->db->createCommand('SELECT state FROM zayavka WHERE iid='.$this->iid.' ORDER BY id DESC')->queryRow();
		}
		$tempZayavka = TempZayavka::model()->findByAttributes(array('t_uid' => Yii::app()->user->id));

		if ( (empty($state) && !empty($tempZayavka) && !empty($tempZayavka->t_srok) && !empty($tempZayavka->t_summ)) )
			return true;
		elseif( $state['state'] == 2 || (in_array($state['state'], array(4,6)) && !empty($tempZayavka) && !empty($tempZayavka->t_srok) && !empty($tempZayavka->t_summ)) ) {

			return true;
		}
		return false;
	}

	public function smsVerification() {
		if ( $this->iid ) {
			$state = Yii::app()->db->createCommand('SELECT state, dateStart FROM zayavka WHERE iid='.$this->iid.' ORDER BY id DESC')->queryRow();
		}

		if(empty($state))
			return false;

		if ( $state['state'] == 0 ) {
			return true;
		}
		return false;
	}

	public function hasActiveCredit() {
		if ( $this->iid ) {
			$zayavka = Yii::app()->db->createCommand('SELECT id, state, dateStart FROM zayavka WHERE iid='.$this->iid.' ORDER BY id DESC')->queryRow();
		}

		if(empty($zayavka))
			return false;

		if ( in_array($zayavka['state'], array(1,2,3,5,7)) || ($zayavka['state'] == 4 && (time() - strtotime($zayavka['dateStart'])) < 3600 * 24 )) {
			return true;
		}

/*
  		if ( in_array($zayavka['state'], array(1,2,3,5,7)) ) {
			return true;
		}
		if ( $zayavka['state'] == 4 ) {
			$state_log = Yii::app()->db->createCommand('SELECT s_action, s_controller  FROM state_log WHERE s_zid=' . $zayavka['id']. ' AND s_state = 4')->queryRow();
			if( (time() - strtotime($zayavka['dateStart'])) < 3600 * 24  ){
				if( !empty($state_log) && $state_log['s_action'] === 'reject' && $state_log['s_controller'] === 'loan' )
					return false;
				else
					return true;
			}
		}
*/
		return false;
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

?>
