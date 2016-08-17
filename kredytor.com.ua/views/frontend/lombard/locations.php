<?php
$maps_lang = 'en-US';
if ( Yii::app()->language == 'ru' )
	$maps_lang = 'ru-RU';

?>
<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=<?php echo $maps_lang;?>" type="text/javascript"></script>
<script src="/bootstrap/js/locations.js" type="text/javascript"></script>

<?php
$title = Yii::t('site', 'Departments adresses');
$select_arr = $cities;
sort($select_arr);
$this->pageTitle = $title.' :: '.Yii::app()->name; 
?>

<div class="map-box" id="ymaps-map-id_134218434183860715067">
</div>

<?php
$array_from = array("\r\n", "\n", '"');
$array_to	= array("<br />", "<br />", '\"');
$script = '
var lombards_array = [];';

foreach ( $items as $item ) {
	$link = '/'.Yii::app()->language.'/locations/'.$item['l_alias'].'/';
?>
<div class="office-section">
	<div class="widget-content office-widget">
		<a class="view-office" href="<?=$link;?>"></a>
		<figure class="pix-box">
			<a href="<?=$link;?>"><?=( !empty($item['lp_path']) ? '<img src="'.$item['lp_path'].'" alt="" />' : '' );?></a>
		</figure>
		<div class="desc-box clearfix">
			<h2 class="h2-title"><a href="<?=$link;?>"><?=Yii::t('site', 'Department');?> № <?=$item['l_id'];?></a></h2>
			<div>
				<p><?=str_replace($array_from, $array_to, $item['ll_adress']);?><br/><?=$item['ll_title'];?></p>
			</div>
			<?php if ( !empty($item['ll_phones']) ) { ?>
			<div>                            
				<span class="title">тел:</span> 
				<?=str_replace($array_from, $array_to, $item['ll_phones']);?>
			</div>
			<?php } ?>
			<div>                        
				<?=str_replace($array_from, $array_to, $item['ll_graffic']);?>
			</div>
		</div>
	</div>
</div>

<?php 
	if ( !$item['l_coord_lati'] || !$item['l_coord_long'] )	continue;
	$city = '';
	if ( isset($cities[$item['l_cid']]) ) $city = $cities[$item['l_cid']].', ';
	$text_lo = '<a href = "'.$link.'">'.Yii::t('site', 'Department').' № '.$item['l_id'].'</a><br/>'.$city.$item['ll_adress'];
	$text_lo = str_replace($array_from, $array_to, $text_lo);
	$script.='
lombards_array.push(["'.$item['l_coord_lati'].'", "'.$item['l_coord_long'].'", "'.$text_lo.'"]);'; 	
} ?>


<script type="text/javascript">
<?php
echo $script; 
?>
	
</script>
