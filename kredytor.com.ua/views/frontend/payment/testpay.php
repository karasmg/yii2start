<?php
$math = new MathHelper();
?>
<h1>Договора</h1><br/>
<table class="dogovor_table">
	<tr>
		<th>№ Заявки</th>
		<th>Инн</th>
		<th>Vers</th>
		<th>Дата залога</th>
		<th>Срок</th>
		<th>Дата выкупа</th>
		<th>Сумма</th>
		<th>буффер</th>
		<th>Сумма %%</th>
	</tr>
	<?php foreach ( $dogovora as $dogovor ) { 
		$date = explode(' ', $dogovor['d_date_start']);
		$date1 = strtotime($date[0]);
		$date2 = $date1 + ($dogovor['d_term']*60*60*24) + ($dogovor['d_termmodifier']*60*60*24);
		$math->_creditParams = array(
			'_percentstage'		=> $dogovor['d_percentstage'],
			'_penystage'		=> $dogovor['d_penystage'],
			'_panydaystart'		=> $dogovor['d_panydaystart'],
			'_firstdayminpay'	=> $dogovor['d_firstdayminpay'],
			'_termmodifier'		=> $dogovor['d_termmodifier'],
			'_firstdayPayed'	=> ($dogovor['d_version']>1),
		);
	?>
	<tr>
		<td><a href="/payment/testpayclass/zid/<?=$dogovor['d_zid'];?>"><?=$dogovor['d_zid'];?></a></td>
		<td><?=$dogovor['d_aid'];?></td>
		<td><?=$dogovor['d_version'];?></td>
		<td><?=date('d.m.Y', $date1);?></td>
		<td><?=$dogovor['d_term'];?></td>
		<td><?=date('d.m.Y', $date2);?></td>
		<td><?=$dogovor['d_summ'];?></td>
		<td><?=(int)$dogovor['d_boffer'];?></td>
		<td><?=$math->countPercentSumm($dogovor['d_summ'], $dogovor['d_term']);?></td>
	</tr>	
	<?php } ?>
</table>

<?php if ( !empty($edit_dog) ) { ?>
<br/><br/>
<h2>настройки договора <?=$edit_dog->d_zid;?></h2><br/>
<form action="" method="post" class="dogovor_form"/>
	<?php
	if ( !empty($error_dog) )
		var_dump($error_dog);
	?>
	<label>
		Дата договора &nbsp;&nbsp;&nbsp;
		<input type="text" name="dogovor[d_date_start]" value="<?=$edit_dog->d_date_start;?>" />
	</label>
	<label>
		Срок &nbsp;&nbsp;&nbsp;
		<input type="text" name="dogovor[d_term]" value="<?=$edit_dog->d_term;?>" />
	</label>
	<label>
		Сумма &nbsp;&nbsp;&nbsp;
		<input type="text" name="dogovor[d_summ]" value="<?=$edit_dog->d_summ;?>" />
	</label>
	<label>
		% ставка &nbsp;&nbsp;&nbsp;
		<input type="text" name="dogovor[d_percentstage]" value="<?=$edit_dog->d_percentstage;?>" />
	</label>
	<label>
		% пени &nbsp;&nbsp;&nbsp;
		<input type="text" name="dogovor[d_penystage]" value="<?=$edit_dog->d_penystage;?>" />
	</label>
	<label>
		дней без пени &nbsp;&nbsp;&nbsp;
		<input type="text" name="dogovor[d_panydaystart]" value="<?=$edit_dog->d_panydaystart;?>" />
	</label>
	<label>
		Мин за перв день &nbsp;&nbsp;&nbsp;
		<input type="text" name="dogovor[d_firstdayminpay]" value="<?=$edit_dog->d_firstdayminpay;?>" />
	</label>
	<label>
		Модификатор срока &nbsp;&nbsp;&nbsp;
		<input type="text" name="dogovor[d_termmodifier]" value="<?=$edit_dog->d_termmodifier;?>" />
	</label>
	<?php if ( !empty($_GET['zid']) ) { ?>
		<input type="submit" name="save_dogovor" value="Изменить настройки договора" class="btn" />
	<?php } ?>
</form>


<form action="" method="post" class="dogovor_form_hidden"/>
	<input type="hidden" name="d_zid" value="<?=$edit_dog->d_zid;?>"/>
	<input type="hidden" name="pay_day" value="" class="pay_day"/>
	<input type="hidden" name="pay_summ" value="" class="pay_summ" />	
</form>

<br/><br/>
<h2>Сделать оплату по заявке <?=$edit_dog->d_zid;?></h2><br/>

<table class="dogovor_table">
	<tr>
		<th>Номер договора</th>
		<th>Дата договора</th>
		<th>Дата сегодня</th>
		<th>Пользования дней</th>
		<th>Сумма по %%</th>
		<th>Пени дней</th>
		<th>Пеня</th>
		<th>Полная задолженность</th>
		<th style="width:180px;">Сделать оплату</th>
	</tr>	
	<?php 
	$total = count($view_dogs);
	foreach ( $view_dogs as $idx=>$view_dog ) { 
		$days = 21;
		if ( $idx < ($total-1) ) {
			$days = ($view_dogs[($idx+1)]->d_date_zalog - $view_dog->d_date_zalog)/(60*60*24)+1;
		}
		if ( !empty($view_dog->d_iid) ) {
			$invoice = Invoices::model()->findByPk($view_dog->d_iid);
			if ( !is_null($invoice) ) { ?>
				<tr>
					<td colspan="9">
						<table class="dogovor_table invoice_table">
							<tr>
								<th>Дата оплаты</th>
								<th>Сумма</th>
								<th>Проценты</th>
								<th>Пеня</th>
								<th>Тело</th>
								<th>Буффер дог</th>
								<th>Пролонгация</th>
								<th>Буффер чел</th>
								<th>Действие</th>
							</tr>
							<tr>
								<td><?=date('d.m.Y', strtotime($invoice->i_pay_day));?></td>
								<td><?=$invoice->i_summ;?></td>
								<td><?=$invoice->i_percent;?></td>
								<td><?=$invoice->i_peny;?></td>
								<td><?=$invoice->i_body;?></td>
								<td><?=$invoice->i_buffer;?></td>
								<td><?=$invoice->i_days_prol;?></td>
								<td><?=$invoice->i_buffer_user;?></td>
								<td><a href="/payment/testpayclass/zid/<?=$view_dog['d_zid'];?>/dodelete/<?=$invoice->i_id;?>">Удалить</a></td>
							</tr>
						</table>
					</td>
				</tr>
			<? }
		}
		for ( $i=0; $i<$days; $i++ ) {
	?>
		<tr>
			<td><?=$view_dog->d_zid;?>#<?=$view_dog->d_version;?></td>
			<td><?=date('d.m.Y', $view_dog->d_date_zalog);?></td>
			<td><?=date('d.m.Y', ($view_dog->d_date_zalog+($i*60*60*24)) );?></td>
			<td><?=($i+1);?></td>
			<td><?=$view_dog->countPercentSumm( date('d.m.Y', ($view_dog->d_date_zalog+($i*60*60*24))) );?></td>
			<td><?=$view_dog->countDaysPeny( date('d.m.Y', ($view_dog->d_date_zalog+($i*60*60*24))) );?></td>
			<td><?=$view_dog->countPenySumm( date('d.m.Y', ($view_dog->d_date_zalog+($i*60*60*24))) );?></td>
			<td><?=$view_dog->countTotalSumm( date('d.m.Y', ($view_dog->d_date_zalog+($i*60*60*24))) );?></td>
			<td>
				<?php if ( $idx == ($total-1) ) { ?>
				<input type="submit" value="Оплатить" class="do_pay" style="width:60px; float:right; height:23px"/>
				<input type="text" value="" name="make_payment" class="make_payment" style="width:100px; float:left;"/>
				<input type="hidden" name="payment_day" class="payment_day" value="<?=date('d.m.Y', ($view_dog->d_date_zalog+($i*60*60*24)) );?>"/>
				<?php } ?>
			</td>
		</tr>	
	<?php 
		} 
	}
	?>
</table>
<?php } ?>

<script>
$(window).load(function(){
	$('input.do_pay').click(function(){
		$('.dogovor_form_hidden .pay_day').val( $(this).parent().find('.payment_day').val() );
		$('.dogovor_form_hidden .pay_summ').val( $(this).parent().find('.make_payment').val() );
		$('.dogovor_form_hidden').submit();
	});
});
</script>