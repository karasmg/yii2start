<?php 
$this->pageTitle		=	Yii::t('site', 'Order').' № '.$order->o_id.' :: '.Yii::app()->name;  
//$this->pageKeywords		= $article['meta_keywords']; 
//$this->pageDescription	= $article['meta_discriptiion']; 

$state = '';
$states = $order->selectValues();
if ( !empty($states['o_state']) && !empty($states['o_state'][1][$order->o_state]) ) {
	$state = $states['o_state'][1][$order->o_state];
}
?>


<div class="page_content">
	 <h1><?php echo Yii::t('site', 'Order');?> № <?php echo $order->o_id;?></h1>
	 <?php if ( $success ) { ?>
	 <h2 class="success"><?php echo Yii::t('site', 'Your order sended success');?>!</h2>
	 <?php } ?>
	 <p class="order_row"><strong><?php echo Yii::t('main', 'Order date');?>:</strong>  <?php echo $order->o_date;?></p>
	 <?php if ( !empty($state) ) {?>
	 <p class="order_row"><strong><?php echo Yii::t('main', 'Order state');?>:</strong>  <?php echo $state;?></p>
	 <?php } ?>
	 <table class="basket">
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
					<td class="pic"><img src="'.$pic.'" alt="'.$title.'"/></td>
					<td class="title">'.$title.'</td>
					<td class="price">'.number_format($prod['og_price'], 0, '.', ' ').' <span>&euro;</span></td>
				</tr>';
			}
			?>
		 </tbody>
	 </table>
	 <div class="price total"><?php echo Yii::t('site', 'total').': '. number_format($total, 0, '.', ' ');?> <span>&euro;</span></div>
</div>
<br clear="all"/>




