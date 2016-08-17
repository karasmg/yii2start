<?php
    // Render options as dropDownList
	$links = array();
	foreach($languages as $key=>$lang) {
		if ( $key == Yii::app()->language ) {
			$links[] = '<span class="active-lg">'.$key.'</span>';
		}else {
			$link = str_replace('<lang_replacement>', $key, $url);
			if ( $link == '/'.Yii::app()->params['def_languege'].'/' )
				$link = '/';
			$links[] = '<a class="no-active-lg" href="'.$link.'" title = "'.$lang.'">'.$key.'</a>';
		}
	}
	echo implode(' / ', $links);
?>