<?php 
$this->pageTitle		=	Yii::t('site', 'Basket').' :: '.Yii::app()->name; 
//$this->pageKeywords		= $article['meta_keywords']; 
//$this->pageDescription	= $article['meta_discriptiion']; 
?>


<div class="page_content">
	 <h1><?php echo Yii::t('site', 'Basket');?></h1>
	 <table class="basket">
		 <thead>
			 <tr>
				 <th><?php echo Yii::t('site', 'Foto');?></th>
				 <th><?php echo Yii::t('site', 'Prod name');?></th>
				 <th><?php echo Yii::t('site', 'Price');?></th>
				 <th>&nbsp;</th>
			 </tr>
		 </thead>
		 <tbody>
			<?php
			$total=0;
			foreach ( $prods as $prod ) {
				$total+=$prod['ca_price'];
				$pic = $prod['ca_pic_small'];
				if ( !$pic )
					$pic = $prod['cm_image_sm'];
				$link = '/'.Yii::app()->language.'/catalog/'.$prod['cc_alias'].'/'.$prod['cm_alias'].'/'.$prod['ca_alias'].'/';
				$title=$prod['cml_title'].' ('.$prod['ca_articul'].')';
				echo '
				<tr>
					<td class="pic"><a href="'.$link.'" title="'.$title.'"><img src="'.$pic.'" alt="'.$title.'"/></a></td>
					<td class="title"><a href="'.$link.'" title="'.$title.'">'.$title.'</a></td>
					<td class="price">'.number_format($prod['ca_price'], 0, '.', ' ').' <span>&euro;</span></td>
					<td class="remove"><a href="/'.Yii::app()->language.'/catalog/remove_from_basket/'.$prod['ca_id'].'/" title="'.Yii::t('site', 'Remove').'">'.Yii::t('site', 'Remove').'</a></td>
				</tr>';
			}
			?>
		 </tbody>
	 </table>
	 <div class="price total"><?php echo Yii::t('site', 'total').': '. number_format($total, 0, '.', ' ');?> <span>&euro;</span></div>
	 <?php
	 if ( !Yii::app()->user->isGuest ) { ?>
		<form action="/<?php echo Yii::app()->language;?>/catalog/order/" class="order_form">
			<input type="submit" class="basket submit" name="order" value="<?php echo Yii::t('site', 'Reserve');?>"/>
		</form>
	 <?php } else { ?>
		<div class="order_auth">
			<?php echo Yii::t('site', 'To make reserve');?>
			<a class="ajax_form" title="<?php echo Yii::t('site', 'login');?>" href="/<?php echo Yii::app()->language;?>/login/"><?php echo Yii::t('site', 'login');?></a>
			<span> <?php echo Yii::t('site', 'Or');?> </span>
			<a class="ajax_form" title="<?php echo Yii::t('site', 'register');?>" href="/<?php echo Yii::app()->language;?>/register/"><?php echo Yii::t('site', 'register');?></a>
			<?php echo Yii::t('site', 'Needs');?>.
		</div>
	 <?php } ?>	 
</div>
<br clear="all"/>




