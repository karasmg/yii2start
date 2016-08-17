<?php
class SiteHart extends CWidget
{	
	public $_lang;
	public $_pic;
    public function run()
    {		
		return false;
		$langs = array(
			'ru'	=> 'ru',
			'ua'	=> 'uk',
		);
		if ( empty($langs[Yii::app()->language]) )
			$this->_lang = 'ru';
		else
			$this->_lang = $langs[Yii::app()->language];
		
		$this->render('SiteHart', array(
		));		
    }
}
?>