<?php
class LanguageSelectorSite extends CWidget
{
    public function run()
    {
        $currentLang = Yii::app()->language;
        $languages = Yii::app()->params->languages;
		
		$url_array = explode('/', Yii::app()->request->requestUri);
		$url_array2 = array();
		$params_arr = array();
		if ( !empty($_GET['_page']) )
			$params_arr[]=$_GET['_page'];
		if ( !empty($_GET['page']) )
			$params_arr[]=$_GET['page'];	
		if ( !empty($_GET['month']) )
			$params_arr[]=$_GET['month'];
		if ( !empty($_GET['year']) )
			$params_arr[]=$_GET['year'];
		
		foreach ( $url_array as $url_part ) {
			if ( in_array($url_part, $params_arr) )
				continue;
			if ( $url_part == $currentLang )
				$url_part = '<lang_replacement>';
			$url_array2[] = $url_part;
		}
		$url = implode('/', $url_array2);
		if ( $url == '/' )
			$url = '/<lang_replacement>/';
        $this->render('LanguageSelectorSite', array(
			'currentLang'	=> $currentLang, 
			'languages'		=> $languages,
			'url'			=> $url,
		));
    }
}
?>