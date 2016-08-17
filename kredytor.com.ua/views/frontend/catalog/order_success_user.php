<h1><?php echo Yii::t('site', 'Your order sended success');?>!</h1>
<h3><?php echo Yii::t('site', 'Order');?> № <?php echo $order->o_id;?></h3>

<table width="100%" border="1" cellpadding="4">
	<thead>
		<tr>
			<th><?php echo Yii::t('site', 'Foto');?></th>
			<th><?php echo Yii::t('site', 'Prod name');?></th>
			<th><?php echo Yii::t('site', 'Price');?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$total=0;
		foreach ( $prods as $prod ) {
			$total+=($prod['og_price']*$prod['og_ammount']);
			$pic = $prod['ca_pic_small'];
			if ( !$pic )
				$pic = $prod['cm_image_sm'];
			$title=$prod['cml_title'].' ('.$prod['ca_articul'].')';
			echo '
			<tr>
				<td class="pic"><img src="https://'.$_SERVER['HTTP_HOST'].$pic.'" alt="'.$title.'" height="100"/></td>
				<td class="title">'.$title.'</td>
				<td class="price">'.number_format($prod['og_price'], 0, '.', ' ').' <span>&euro;</span></td>
			</tr>';
			}
			?>
	</tbody>
</table>
<p><strong><?php echo Yii::t('site', 'total').': '. number_format($total, 0, '.', ' ');?> &euro;</strong></p>
<p><?php echo Yii::t('site', 'mail administration');?></p>




