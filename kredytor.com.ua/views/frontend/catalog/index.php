<?php 
$this->pageTitle		= Yii::t('site', 'Web-shop').'::'.Yii::app()->name;; 
//$this->pageKeywords		= $article['meta_keywords']; 
//$this->pageDescription	= $article['meta_discriptiion']; 
?>
<?php $this->widget('application.components.widgets.SiteSearch');?>
<div class = "catalog left_column">
	<?php $this->widget('application.components.widgets.CatalogCategsLeft', array());?>
</div>


<div class="catalog right_column">
	<h1><?php echo Yii::t('site', 'Day hits');?></h1>
	<div class="items_count">
		<?php
		$count=0;
		foreach ( $hits as $prod ) { 
			$count++;
			$dop_class='';
			if ( $count == 3 ) {
				$count = 0;
				$dop_class=' last_in_row';
			}
			$pic = $prod['ca_pic_small'];
			if ( !$pic )
				$pic = $prod['cm_image_sm'];
			$link = '/'.Yii::app()->language.'/catalog/'.$prod['cc_alias'].'/'.$prod['cm_alias'].'/'.$prod['ca_alias'].'/';
		?>
		<div class="prod<?php echo $dop_class;?>">
			<span class="tizzer-lenta dayhit"></span>
			<a class="pic" href="<?php echo $link;?>" title="<?php echo $prod['cml_title'];?>">
				<img src="<?php echo $pic;?>" alt="<?php echo $prod['cml_title'];?>" />
			</a>
			<a class="title" href="<?php echo $link;?>" title="<?php echo $prod['cml_title'];?>"><?php echo $prod['cml_title'];?></a>
			<div class="price">
				<span><?php echo number_format($prod['ca_price'], 0, '.', ' ');?> €</span>
				<?php echo Yii::app()->user->generateBuyBtn($prod['ca_id']); ?>
			</div>
			<div class="tizz-ico dayhit">hit</div>
		</div>
	<?php } ?>
	</div>
	
	<h2 class="h1"><?php echo Yii::t('site', 'New prods');?></h2>
	<div class="items_count">
		<?php
		$count=0;
		foreach ( $news as $prod ) { 
			$count++;
			$dop_class='';
			if ( $count == 3 ) {
				$count = 0;
				$dop_class=' last_in_row';
			}
			$pic = $prod['ca_pic_small'];
			if ( !$pic )
				$pic = $prod['cm_image_sm'];
			$link = '/'.Yii::app()->language.'/catalog/'.$prod['cc_alias'].'/'.$prod['cm_alias'].'/'.$prod['ca_alias'].'/';
		?>
		<div class="prod<?php echo $dop_class;?>">
			<span class="tizzer-lenta dayhit"></span>
			<a class="pic" href="<?php echo $link;?>" title="<?php echo $prod['cml_title'];?>">
				<img src="<?php echo $pic;?>" alt="<?php echo $prod['cml_title'];?>" />
			</a>
			<a class="title" href="<?php echo $link;?>" title="<?php echo $prod['cml_title'];?>"><?php echo $prod['cml_title'];?></a>
			<div class="price">
				<span><?php echo number_format($prod['ca_price'], 0, '.', ' ');?> €</span>
				<?php echo Yii::app()->user->generateBuyBtn($prod['ca_id']); ?>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
<br clear="all"/>



