<?php
$datas = $this->xml2array($blocks[8]['urating']);
?>
<h2>Кредитный рейтинг</h2>
<table>
	<tr>
		<th class="header">Балл</th>
		<th class="header">Наименование</th>
	</tr>
	<tr>
		<td><?php echo $datas["@attributes"]["score"];?></td>
		<td><?php echo $this->convertSprav('scorelevel', $datas["@attributes"]["scorelevel"]); ?></td>
	</tr>
</table>

<?php
$calendar = array(
	'01'	=> 'Янв',
	'02'	=> 'Фев',
	'03'	=> 'Мар',
	'04'	=> 'Апр',
	'05'	=> 'Май',
	'06'	=> 'Июн',
	'07'	=> 'Июл',
	'08'	=> 'Авг',
	'09'	=> 'Сен',
	'10'	=> 'Окт',
	'11'	=> 'Ноя',
	'12'	=> 'Дек',
);

$credits	= array();
$payments	= array();
if ( !empty($blocks[2]['crdeal']) ) {
	$datas = $this->xml2array($blocks[2]['crdeal']);
	if ( !empty($datas["deallife"]) )
		$datas = array($datas);

	foreach ( $datas as $key=>$val ) {
		$payments[$key] = array();
		foreach ( $val["deallife"] as $payment ) {
			$payment = $this->xml2array($payment);
			if ( !empty($payment["@attributes"]) ) $payment = $payment["@attributes"];		
			$perriod = $payment["dlyear"].'.'.str_pad($payment["dlmonth"], 2, "0", STR_PAD_LEFT);
			$payments[$key][$perriod] = $payment;
			$payments[$key][$perriod]['type']		= $val["@attributes"]["dlcelcredref"].' №'.($key+1);
			$payments[$key][$perriod]['date_info']	= $payments[$key][$perriod]["dlyear"].'-'.str_pad($payments[$key][$perriod]["dlmonth"], 2, "0", STR_PAD_LEFT).'-01 00:00:00';
		}
		//ksort($payments[$key]);
		$last_payment_Data = end($payments[$key]);
		$credits[$key] = array(
			'type'			=> $val["@attributes"]["dlcelcredref"].' №'.($key+1),
			'type_icon'		=> $val["@attributes"]["dlcelcred"],
			'valute'		=> $val["@attributes"]["dlcurrref"],
			'status'		=> $last_payment_Data["dlflstatref"],
			'date_start'	=> $last_payment_Data["dlds"],
			'date_finish'	=> $last_payment_Data["dldpf"],
			'date_end_fact'	=> $last_payment_Data["dldff"],
			'ammount'		=> $val["@attributes"]["dlamt"],
			'curr_ammount'	=> $last_payment_Data["dlamtcur"],
			'pay_summ'		=> $last_payment_Data["dlamtpaym"],
			'prosr_ammount'	=> $last_payment_Data["dlamtexp"],
			'prosr_days'	=> $last_payment_Data["dldayexp"],
			'date_data'		=> str_pad($last_payment_Data["dlmonth"], 2, "0", STR_PAD_LEFT).'.'.$last_payment_Data["dlyear"],
		);
	}
}
?>
<h2>история по кредитам</h2>
<table>
	<tr>
		<th>Тип кредита</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['type'].'</td>'; } ?>
	</tr>
	<tr>
		<th>Валюта</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['valute'].'</td>'; } ?>
	</tr>
	<tr>	
		<th>Статус кредита</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['status'].'</td>'; } ?>
	</tr>
	<tr>	
		<th>Дата выдачи</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['date_start'].'</td>'; } ?>
	</tr>
	<tr>
		<th>Срок действия до</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['date_finish'].'</td>'; } ?>
	</tr>
	<tr>
		<th>Фактическая дата окончания</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['date_end_fact'].'</td>'; } ?>
	</tr>
	<tr>	
		<th>Сумма кредита (лимит)</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['ammount'].'</td>'; } ?>
	</tr>
	<tr>	
		<th>Текущая задолженность</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['curr_ammount'].'</td>'; } ?>
	</tr>
	<tr>
		<th>Сумма обязательного платежа</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['pay_summ'].'</td>'; } ?>
	</tr>
	<tr>
		<th>Ткущая просроченная задолженность</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.( !empty($credit['dlamtexp']) ? $credit['dlamtexp'] : '' ).'</td>'; } ?>
	</tr>
	<tr>	
		<th>Ткущее кол-во дней просрочки</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['prosr_days'].'</td>'; } ?>
	</tr>
	<tr>	
		<th>Дата обновления</th>
		<?php foreach ( $credits as $credit ) { echo '<td>'.$credit['date_data'].'</td>'; } ?>
	</tr>
</table>	
		
<?php 
foreach ( $payments as $key=>$data ) { 
	$first_payment	= reset($data);
	$last_payment	= end($data);
	krsort($data);
	$year_start = date('Y', strtotime($first_payment['date_info']));
	$year_end = date('Y', strtotime($last_payment['date_info']));
?>
<h4><?php echo $last_payment['type'];?></h4>
<table class="calendar">
	<tr>
		<th class="header">Год/мес</th>
		<?php foreach ( $calendar as $month_name ) echo '<th class="header">'.$month_name.'</th>';?>
	</tr>
	<?php 
	for ( $i=$year_end; $i>=$year_start; $i-- ) {
		echo PHP_EOL.'	<tr>';
		echo PHP_EOL.'		<th class="header">'.$i.'</th>';
		foreach ( $calendar as $month_index=>$month_name ) {
			$data_show = '';
			if ( isset($data[$i.'.'.$month_index]) ) {
				$params_pay = $data[$i.'.'.$month_index];
				//$this->dodump($params_pay);
				$title = 'Текущий долг: '.$params_pay['dlamtcur'].PHP_EOL.
						 'Сумма обязат. плат.: '.$params_pay['dlamtpaym'].PHP_EOL.
						 'Текущая проср.: '.$params_pay['dlamtexp'].PHP_EOL.
						 'Срок проср. дней.: '.$params_pay['dldayexp'];
				$data_show = '<a href="#" onclick="return false;" style="color:#'.$this->colorProsroch($params_pay['dldayexp'], $params_pay['dlamtexp']).'" title="'.$title.'">'.$this->scaleProsroch($params_pay['dldayexp']).'</a>';
			}
			echo PHP_EOL.'		<td>'.$data_show.'</td>';
		}
		echo PHP_EOL.'	</tr>';
	}
	?>
</table>
<?php } ?>
<br/><br/><br/>
	
		
		
		
		
		
		