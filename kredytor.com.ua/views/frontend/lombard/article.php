<script>
	$( document ).ready(function() {
		$('.icon-phone a').click(function( ){
			ga('send', 'event', 'recall me', 'click');
		});
	});
</script>
<?php
$metatitle = Yii::t('site', 'Department').' '.$article['ll_title'];
if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
$this->pageTitle		= $metatitle.' '.mb_strtolower(Yii::t('site', 'Departments adresses'), 'utf8').' :: '.Yii::app()->name; 
$this->pageKeywords		= $article['meta_keywords']; 
$this->pageDescription	= $article['meta_discriptiion']; 

$maps_lang = 'en-US';
if ( Yii::app()->language == 'ru' )
	$maps_lang = 'ru-RU';

$link = '/'.Yii::app()->language.'/locations/'.$article['l_alias'].'/';
$array_from = array("\r\n", "\n", '"');
$array_to	= array("<br />", "<br />", '\"');
$script = '
var lombards_array = [];';
if ( $article['l_coord_lati'] && $article['l_coord_long'] ) {
	$city = '';
	if ( isset($cities[$article['l_cid']]) ) $city = $cities[$article['l_cid']].', ';
	$text_lo = '<a href = "'.$link.'">'.Yii::t('site', 'Department').' № '.$article['l_id'].'</a><br/>'.$city.$article['ll_adress'];
	$text_lo = str_replace($array_from, $array_to, $text_lo);
	$script.='
lombards_array.push(["'.$article['l_coord_lati'].'", "'.$article['l_coord_long'].'", "'.$text_lo.'"]);'; 	
}
?>
<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=<?php echo $maps_lang;?>" type="text/javascript"></script>
<script src="/bootstrap/js/locations.js" type="text/javascript"></script>

<div class="widget-content clearfix">
	<?php echo $this->buildLombardGallery($article['l_id']);?>
	<div class="office-box">
		<h2 class="h2-title"><?=Yii::t('site', 'Department');?> № <?=$article['l_id'];?></h2>
		<ul class="list-contact">
			<li class="icon-local">
				<?=( !empty($cities[$article['l_cid']]) ? $cities[$article['l_cid']].', ' : '' ) ;?>				
				<?=$article['ll_adress'];?> <br />
				<?=$article['ll_title'];?>
			</li>
			<?php
			if ( !empty($article['ll_phones']) ) { ?>
			<li class="icon-phone">
				<?=nl2br($article['ll_phones']);?>
			</li>
			<?php } ?>
			<?php
			if ( !empty($article['ll_graffic']) ) { ?>
			<li class="icon-clock">
				<?=Yii::t('site', 'graffic');?>: <br />
				<?=nl2br($article['ll_graffic']);?>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
<script type="text/javascript">
<?php
echo $script; 
?>
</script>
<div class="map-box" id="ymaps-map-id_134218434183860715067">
</div>