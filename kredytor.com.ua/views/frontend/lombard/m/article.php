<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
<script src="/bootstrap/js/locations.js" type="text/javascript"></script>
<?php 
$metatitle = $article['ll_title'];
if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
$this->pageTitle		= $metatitle.' :: '.Yii::app()->name; 
$this->pageKeywords		= $article['meta_keywords']; 
$this->pageDescription	= $article['meta_discriptiion']; 


if ( $article['l_allday'] ) $grafic = '<span class = "workhours_allday">'.Yii::t('main', 'All day').'</span>';
else $grafic = $article['ll_graffic'];

?>

<script>
<?php
$script = '
var lombards_array = [];';
$array_from = array("\r\n", "\n", '"');
$array_to	= array("", "", '\"');

$text_lo = $article['ll_title'].'<br>'.$article['ll_adress'];
$text_lo = str_replace($array_from, $array_to, $text_lo);
	
$script.='
lombards_array.push(["'.$article['l_coord_lati'].'", "'.$article['l_coord_long'].'", "'.$text_lo.'", ""]);'; 	
echo $script;
?>
</script>


<div class="page_content">
	<h1><?php echo $article['ll_title'];?></h1>
	
	<div id="ymaps-map-id_134218434183860715067" style="width: 100%; height: 380px;"></div>
	
	<p class ="lo_param graffic"><span class ="title"><?php echo Yii::t('main', 'Work graphic');?></span><?php echo $grafic;?></p>
	<p class ="lo_param phones"><span class ="title"><?php echo Yii::t('main', 'Phones');?></span><?php echo $article['ll_phones'];?></p>
	<p class ="lo_param phones"><span class ="title"><?php echo Yii::t('main', 'Adress');?></span><?php echo $article['ll_adress'];?></p>
	
	<p class ="lo_text"><?php echo $article['ll_text'];?></p>
	
	<?php echo $this->buildLombardGallery($article['l_id']);?>

</div>