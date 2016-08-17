<?php 
$this->pageTitle		= 'Купить '.$categ->lang_data[0]->ccl_title.' б/у'; 
//$this->pageKeywords		= $article['meta_keywords']; 
//$this->pageDescription	= $article['meta_discriptiion']; 
?>
<?php $this->widget('application.components.widgets.SiteSearch');?>
<div class = "catalog left_column">
	<?php $this->widget('application.components.widgets.CatalogCategsLeft', array('cat_id'=>$categ->cc_id));?>
	<?php $this->widget('application.components.widgets.CatalogFilterLeft', array('prods'=>$prodsFilter, 'seleted'=>$filter_params));?>
</div>

<div class="catalog right_column">
	<h1><?php echo $categ->lang_data[0]->ccl_title;?></h1>
	<?php $this->widget('application.components.widgets.CatalogSorting', array('seleted'=>$filter_params));?>
	<div class="items_count">
		<?php
		$count=0;
		foreach ( $prods as $prod ) { 
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





