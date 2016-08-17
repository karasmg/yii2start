<?php

/**
 * This is the model class helper for work with site urls.
 *
 */
class Url_works extends CModel
{
	public function attributeNames() {
		return '';
	}
	
	public function getAliasFromUrl( $url ) {
		$url = trim($url);
		if ( !$url ) return false;
		
		if ( substr($url, -5) == '.html' ) return substr($url, 0, -5);		
		return $url;
	}
}
