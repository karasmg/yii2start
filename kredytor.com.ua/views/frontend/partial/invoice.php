<?php
$invoice_title = $zayavka_invoice->number.' '.Yii::t('site', 'from').' '.date_create($zayavka_invoice->date_from_mag)->format('d.m.Y');
?>
<h4><?= Yii::t('site', 'Zayavka-invoice') ?> № <?= $invoice_title ?> </h4>
<br>
<table class="table table-striped table-hover">
	<thead>
	<tr>
		<th><?= Yii::t('site', '№ R/P') ?></th>
		<th><?= Yii::t('main', 'Articul') ?></th>
		<th><?= Yii::t('site', 'Prod name') ?></th>
		<th><?= Yii::t('site', 'Price of goods') ?></th>
		<th><?= Yii::t('site', 'Amount') ?></th>
		<th><?= Yii::t('site', 'Summ') ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$pp = 0;
	$total = 0;
	foreach($zayavka_invoice_prods as $prod){
		$pp++;
		try {
			$prod_cena = round($prod->prod_price / $prod->prod_count, 2);
		} catch(Exception $e){
			$prod_cena = 0;
			$prod->prod_price = 0;
			$prod->prod_count = 0;
		}
		$total += $prod->prod_price;
		echo '
		<tr>
			<td>'.$pp.'</td>
			<td>'.$prod->prod_article.'</td>
			<td>'.$prod->prod_name.'</td>
			<td>'.$prod_cena.'</td>
			<td>'.$prod->prod_count.'</td>
			<td>'.$prod->prod_price.'</td>
		</tr>
		';
	}
	?>

	</tbody>
	<tfoot>
	<tr>
		<th colspan="4" style="text-align: right;"><?= Yii::t('site', 'Total') ?></th>
		<th></th>
		<th><?= number_format($total, 2) ?></th>
	</tr>
	</tfoot>
</table>
