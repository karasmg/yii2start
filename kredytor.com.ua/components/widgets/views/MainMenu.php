<a class="btn-nav" href="#"></a>
<ul class="nav">
<?php
$urlManager = new UrlManager();
$linkbase = '/'.Yii::app()->language.'/';
if ( empty(Yii::app()->controller->breadcrumbs) )
	Yii::app()->controller->breadcrumbs = array($linkbase=>Yii::t('main', 'Main'));
else 
	Yii::app()->controller->breadcrumbs = array_merge ( array($linkbase=>Yii::t('main', 'Main')),  Yii::app()->controller->breadcrumbs);

foreach ( $menu[0] as $item ) {
	if ( is_array($links[$item['m_type']]) && !empty($links[$item['m_type']][$item['m_resource_val']]) )
		$link = $links[$item['m_type']][$item['m_resource_val']];
	else 
		$link = $links[$item['m_type']];
	
	if ( $link != '/' )
		$link = $linkbase.$link.'/';
	else {
		if ( Yii::app()->language != Yii::app()->params['def_languege'] )
			$link = $linkbase;
		Yii::app()->controller->breadcrumbs[$link] = $item['ml_title'];
	}
	$activeMenu = '';
	if ( (strpos($_SERVER['REQUEST_URI'], $link) === 0 && $link != '/' && $link != $linkbase) || $_SERVER['REQUEST_URI'] == $link ) {
		$activeMenu = ' class="active"';
		Yii::app()->controller->breadcrumbs[$link] = $item['ml_title'];
		Yii::app()->params['h1_title'] = $item['ml_title'];
		if ( $item['ml_image'] )
			Yii::app()->params['panner_img'] = $item['ml_image'];
	}
	
	$submenu_class = $submenu_punkts = $submenu_class_a = '';
	if ( !empty($menu[$item['m_id']]) ) {
		$submenu_class		= ' class="dropdown-li"';
		$submenu_class_a	= ' class="dropdown"';
		$submenu_punkts = '<ul>';
		foreach ( $menu[$item['m_id']] as $sub_item ) {
			//$sub_link = $link.$links[$sub_item['m_type']][$sub_item['m_resource_val']].'/';
			$sub_link = $linkbase.$links[$sub_item['m_type']][$sub_item['m_resource_val']].'/';
			$sub_activeMenu = '';
			if ( strpos($_SERVER['REQUEST_URI'], $sub_link) === 0 ) {
				$sub_activeMenu = ' class="active"';
				Yii::app()->params['h1_title'] = $sub_item['ml_title'];
				Yii::app()->controller->breadcrumbs[$link] = $item['ml_title'];
				Yii::app()->controller->breadcrumbs[$sub_link] = $sub_item['ml_title'];
				if ( $sub_item['ml_image'] )
					Yii::app()->params['panner_img'] = $sub_item['ml_image'];
				elseif ( $item['ml_image'] )
					Yii::app()->params['panner_img'] = $item['ml_image'];
			}
			$submenu_punkts.='<li><a href="'.$sub_link.'" title="'.$sub_item['ml_title'].'">'.str_replace(' ', '&nbsp;', $sub_item['ml_title']).'</a></li>';
		}
		$submenu_punkts.= '</ul>';
	}
	echo '<li'.$submenu_class.'>
			<a href="'.$link.'" title="'.$item['ml_title'].'"'.$submenu_class_a.'>'.$item['ml_title'].'</a>
			'.$submenu_punkts.'
		  </li>';
} ?>
</ul><!--/menu-->