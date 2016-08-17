<?php 
$this->pageTitle= Yii::t('site', 'search').' :: '.Yii::app()->name; 
?>
<?php $this->widget('application.components.widgets.SiteSearch');?>
<div class="page_content">
<h1><?php echo Yii::t('site', 'Search results by query');?>: "<span><?php echo $query;?></span>"</h1>
<?php 
$i=0;
$array_from = array("\r\n", "\n");
$array_to	= array("<br />", "<br />");
foreach ( $items as $type=>$item) { 
	$i++;
	$second = '';
	if ( ($i/2) === (int)($i/2) ) {
		$second = ' second';
		echo '</div></div></div><div class="news_grey_bg"><div class="container '.Yii::app()->language.'">';
	}
	$type = explode('_', $type);
	
	$img_src = '/img/no_pic'.(trim($second)).'.jpg';
	$navigator = Yii::t('site', 'Category').': ';
	
	if ( $type[1] == 'ar' ) {
		if ( $item['ca_pic_small'] )
			$img_src = $item['ca_pic_small'];
		elseif ( $item['cm_image_sm'] )
			$img_src = $item['cm_image_sm'];
		$navigator.=Yii::t('site', 'Web-shop').' / '.$item['ccl_title'];
		$title	= $item['cml_title'].' ('.$item['ca_articul'].')';
		$text	= '<div class="ar">'.strip_tags($item['cml_text']).'</div>';
		$link	= '/'.Yii::app()->language.'/catalog/'.$item['cc_alias'].'/'.$item['cm_alias'].'/'.$item['ca_alias'].'/';
	}elseif ( $type[1] == 'lo' ) {
		$navigator.=Yii::t('site', 'Locations');
		$title	= Yii::t('site', 'Department').' '.$item['ll_title'];
		$text	= '<div class="lo"><div class="addr">'.str_replace($array_from, $array_to, $item['ll_adress']).'</div><div class="phone">'.str_replace($array_from, $array_to, $item['ll_phones']).'</div><div class="graffic">'.str_replace($array_from, $array_to, $item['ll_graffic']).'</div></div>';
		$link	= '/'.Yii::app()->language.'/locations/'.$item['l_alias'].'/';
	}
	
	
	
	
	
	
	?>
	<div class = "news_row<?php  {echo $second;}?>">
		<?php
		echo '<div class="pic"><img src = "'.$img_src.'" alt = "'.  htmlspecialchars($title).'" /></div>';
		echo '<p class ="search_categ">'.$navigator.'</p>';
		?>	
		<div class="title"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></div>
		<?php echo $text;?>
		<br clear="all"/>
	</div>
<?php 
	if ( $second ) {
		echo '</div></div><div class="container '.Yii::app()->language.'"><div class="content"><div class="page_content">';
	}
} ?>
<?php echo $pagination;?>
</div>