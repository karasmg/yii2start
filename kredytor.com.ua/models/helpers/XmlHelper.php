<?php

class XmlHelper
{
	public static $_xml = '';
	
	public static function buildXml( $data ) {
		self::$_xml = 
		'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		self::$_xml.=self::recursive_xml($data);
		//array_walk_recursive($data, 'XmlHelper::combine_xml');
		return self::$_xml;
	}
	
	public static function recursive_xml($array, $level = 0, $par_key = '') {
		$respond = '';
		$merge = '';
		for ( $i=0; $i<= $level; $i++ )
			$merge.='	';
		foreach ( $array as $key=>$val ) {
			if ( is_int($key) ) {
				if ( $par_key ) {
					$key = substr($par_key, 0, -1);
				} else {
					$key = 'elem';
				}					
			}			
			if ( is_array($val) ) {
				$respond.=
					$merge.'<'.$key.'>'.PHP_EOL.
					self::recursive_xml($val, ($level+1), $key ).
					$merge.'</'.$key.'>'.PHP_EOL;
			} else {				
				$respond.=
					$merge.'<'.$key.'>'.htmlspecialchars($val).'</'.$key.'>'.PHP_EOL;
			}
		}		
		return $respond;		
	}
}