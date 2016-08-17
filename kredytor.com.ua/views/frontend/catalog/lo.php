<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
<script src="/bootstrap/js/locations.js" type="text/javascript"></script>
<script>
<?php
$script = '
var lombards_array = [];';
$array_from = array("\r\n", "\n", '"');
$array_to	= array("", "", '\"');

$lo_title = $lo->lang_data[0]->ll_title;
if ( !$lo_title ) $lo_title = strip_tags ( $lo->lang_data[0]->ll_adress );

$text_lo = $lo_title.'<br>'.$lo->lang_data[0]->ll_adress;
$text_lo = str_replace($array_from, $array_to, $text_lo);
	
$script.='
lombards_array.push(["'.$lo->l_coord_lati.'", "'.$lo->l_coord_long.'", "'.$text_lo.'", ""]);'; 	
echo $script;
?>
</script>
<div id="article_lo">
	<div class="title"><?php echo Yii::t('site', 'Department');?></div>
	<p><?php echo $city->lang_data[0]->cl_title.', '.$lo->lang_data[0]->ll_adress;?></p>
	<div id="ymaps-map-id_134218434183860715067" class="no_control" style="width: 100%; height: 143px;"></div>
</div>
