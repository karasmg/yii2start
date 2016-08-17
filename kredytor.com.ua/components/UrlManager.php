<?php
class UrlManager extends CUrlManager
{
    public function createUrl($route,$params=array(),$ampersand='&')
    {
		if ( $route !== 'site/captcha' ) {
			if ( !isset($params['_lang']) ) {
				if ( Yii::app()->language != Yii::app()->params['def_languege'] ) {
					//$params['_lang']=Yii::app()->language;
					
					$route = Yii::app()->language.'/'.$route;
				}
				
				
			}
		}
		if ( Yii::app()->params['mobile_sub_categ'] )
			$route = Yii::app()->params['mobile_sub_categ'].$route;
        return parent::createUrl($route, $params, $ampersand);
    }
}
?>