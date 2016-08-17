<ul class="breadcrumbs clearfix">                 
<?
$home_link = '/'.Yii::app()->language.'/';
if ( isset($breadcrumbs[$home_link]) ) {
?>
<li><a title="<?php echo htmlspecialchars(Yii::t('site', 'Site name'));?>" href="<?php echo $home_link;?>"><?php echo $breadcrumbs[$home_link];?></a>/</li>
<?php
}
$i = count($breadcrumbs);
foreach ( $breadcrumbs as $link=>$text ) {
	if ( $link && $link == $home_link ) {
		--$i;
		continue;
	}
	if ( (!$link && !--$i) || ($link && $link == $_SERVER['REQUEST_URI']) ) {
		echo '<li>'.$text.'</li>';
		Yii::app()->params['h1_title'] = $text;
	} elseif ($link) {
		echo '<li><a title="'.$text.'" href="'.$link.'">'.$text.'</a>/</li>';
	}
}
if ( !empty($breadcrumbs_last) ) {
	echo '<li>'.$breadcrumbs_last.'</li>';
	Yii::app()->params['h1_title'] = $breadcrumbs_last;
}
?>
</ul>