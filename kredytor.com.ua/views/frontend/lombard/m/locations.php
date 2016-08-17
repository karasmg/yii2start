<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
<script src="/bootstrap/js/locations.js" type="text/javascript"></script>
<?php
$title = Yii::t('main', 'Lombard adresses');
$select_arr = $cities;
sort($select_arr);
$this->pageTitle = $title.' :: '.Yii::app()->name; 
?>
<div class="page_content">
	<h1><?php echo $title;?></h1>
	
	<form id="data-form" class="form-horizontal">
		<div class ="city_select control-group">
			<label class ="control-label" for ="city_select">
				<?php echo Yii::t('main', 'City');?>
			</label>
			<div class="controls">
				<select name ="city_select" id ="city_select">
					<option value = "0" checked ="checked"> --- </otion>
				<?php
				foreach ($select_arr as $city) echo '<option value = "'.$city.'">'.$city.'</option>';
				?>
				</select>
			</div>
		</div>
	</form>
	<br clear ="all">
	
	<div id="ymaps-map-id_134218434183860715067" style="width: 100%; height: 380px;"></div>
	<table id ="lo_info_content" class ="lombards"></table>
	
	
	<script>
	window.table_heading = '<tr><th><?php echo Yii::t('main', 'City');?></th><th><?php echo Yii::t('main', 'LO title');?></th><th><?php echo Yii::t('main', 'Adress');?></th></tr>';
	</script>
</div>




<script>
<?php
$script = '
var lombards_array = [];';
$array_from = array("\r\n", "\n", '"');
$array_to	= array("", "", '\"');

foreach ( $items as $item ) {
	$text_lo = $item['lang_data'][0]['ll_title'].'<br>'.$item['lang_data'][0]['ll_adress'];
	$text_lo = str_replace($array_from, $array_to, $text_lo);
	
	
	$city = '';
	if ( isset($cities[$item['l_cid']]) ) $city = $cities[$item['l_cid']];
	
	$text_list = '<tr><td class = "lo_city">'.$city.'</td><td class = "lo_title"><a href = "'.$this->createUrl( 'address/'.$item['l_alias']  ).'" alt = "'.$item['lang_data'][0]['ll_title'].'">'.$item['lang_data'][0]['ll_title'].'</a></td><td class = "lo_addr">'.$item['lang_data'][0]['ll_adress'].'</td></tr>';
	$text_list = str_replace($array_from, $array_to, $text_list);
	
	
	$script.='
lombards_array.push(["'.$item['l_coord_lati'].'", "'.$item['l_coord_long'].'", "'.$text_lo.'", "'.$text_list.'"]);'; 	
}

echo $script;
?>
</script>
