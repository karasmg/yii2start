<?php
class Send1cHelper  {
	
	private  $anketa;
	private  $zayavka;

	public function __construct($anketa=false, $zayavka=false) {
		$this->anketa = $anketa;
		$this->zayavka = $zayavka;
	}


	public function send_anketa() {
		if ( $this->anketa->state != 0 && $this->anketa->state != 2 ) {
			return false;
		}
		Yii::app()->params['applicationWorkType'] = 'console';
		$method = 'CheckAnketData';
		$data = array('Request'=>$this->anketa->getRealdata());
		if ( isset($data['Request']['highlight']) )
			unset($data['Request']['highlight']);
		if ( isset($data['Request']['credit_for']) )
			unset($data['Request']['credit_for']);
		if ( isset($data['Request']['srok']) )
			unset($data['Request']['srok']);
		if ( !empty($data['Request']['birth_date']) )
			$data['Request']['birth_date'] = C1Helper::formatDate($data['Request']['birth_date']);
		if ( !empty($data['Request']['birth_date']) )
			$data['Request']['passport_date'] = C1Helper::formatDate($data['Request']['passport_date']);
		if ( !empty($data['Request']['tdate']) )
			$data['Request']['tdate'] = C1Helper::formatDate($data['Request']['tdate']);
		$pics = AnketasPics::model()->findAll('ap_lid = :ap_lid', array('ap_lid'=>$this->anketa->iid));
		if ( $pics ) {
			$data['Request']['files'] = array();
			foreach ( $pics as $count=>$pic )
				$data['Request']['files'][] = 'https://'.$_SERVER['SERVER_NAME'].$pic->ap_path;
		}
		$xml = XmlHelper::buildXml($data);
		$webRequest = C1Helper::sendDataTo1C($xml);
		if ( !$webRequest ) {
			$result = C1Helper::saveRequestTo1C($xml, $method, $this->anketa->iid, 'expert');
			if ( $result ) {
				$this->anketa->state = 1;
				$this->anketa->save();
			}
			return $result;
		}
	}
	
	public function send_zayavka() {
		if ( $this->zayavka->state != 0 && $this->zayavka->state != 2 && $this->zayavka->state != 4 ) {
			return false;
		}
		Yii::app()->params['applicationWorkType'] = 'console';
		$method = 'NewZayavka';
		
		$data = array('Request'=>$this->zayavka->getRealdata());
		if ( isset($data['Request']['highlight']) )
			unset($data['Request']['highlight']);
		if ( !empty($data['Request']['dateStart']) )
			$data['Request']['dateStart'] = C1Helper::formatDate($data['Request']['dateStart']);
		
		$math = new MathHelper($this->zayavka);
		$data['Request']['minPayDay']	= $math->_firstdayminpay;
		$data['Request']['usePercent']	= $this->zayavka->percentstage;
		$data['Request']['penyPercent']	= $this->zayavka->penystage;		
		$data['Request']['srokdays']	= $math->countsrokDays($this->zayavka->srok);
		
		if ( $this->zayavka->manager ) {
			$sync = ManagersToLo::model()->findByAttributes(array('user_id'=>$this->zayavka->manager));
			if ( !is_null($sync) ) {
				$data['Request']['zayavkaLO'] = $sync->user_lo;
			}
			//$data['Request']['zayavkaLO'] = '';
		}
				
		$xml = XmlHelper::buildXml($data);
		$webRequest = C1Helper::sendDataTo1C($xml);
		if ( !$webRequest ) {
			$result = C1Helper::saveRequestTo1C($xml, $method, $this->zayavka->id, 'expert');
			if ( $result && $this->zayavka->state != 4 ) {
				$this->zayavka->state = 1;
				$this->zayavka->save(true, array('state'));
			}
			return $result;
		}		
	}
	
	public function activateDog() {		
		if ( empty($this->zayavka->dogNumb) )
			return false;
		
		$method = 'StartDogovorFromDate';
		$data = array('Request'=>array(
			'number'	=> $this->zayavka->dogNumb,
			'date'		=> C1Helper::formatDate(date('Y-m-d H:i:s')),
		));
		$xml = XmlHelper::buildXml($data);
		$result = C1Helper::saveRequestTo1C($xml, $method, $this->zayavka->id, 'expert');
		return $result;
	}

	public function syncDogs($params) {
				$method = 'SyncDogovorsForDates';
		$data = array('Request'=>array(
				'start_date'	=> C1Helper::formatDate($params['start_date']),
				'end_date'		=> C1Helper::formatDate($params['end_date']),
		));
		$xml = XmlHelper::buildXml($data);
		$result = C1Helper::saveRequestTo1C($xml, $method, '0', $params['dyrectedto']);
		return $result;
	}
}