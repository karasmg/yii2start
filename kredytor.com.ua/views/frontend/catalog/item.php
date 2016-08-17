<?php 
$this->pageTitle		= ''.$categ->lang_data[0]->ccl_title.' '.$model->lang_data[0]->cml_title.' купить б/у'; 
//$this->pageKeywords		= $article['meta_keywords']; 
//$this->pageDescription	= $article['meta_discriptiion']; 

$image_big			= $article->ca_pic_big;
if ( !$image_big )
	$image_big = $model->cm_image_big;

?>
<?php $this->widget('application.components.widgets.SiteSearch');?>
<div class = "catalog left_column">
	<?php $this->widget('application.components.widgets.CatalogCategsLeft', array('cat_id'=>$categ->cc_id));?>
</div>
<div class="catalog right_column itempage">
	<div class="items_count">
		<h1><?php echo $model->lang_data[0]->cml_title.' ('.$article->ca_articul.')';?></h1>
		<div class="item_pics">
			<?php echo $this->buildGallery( $article->ca_id, $image_big);?>
			<?php echo $this->buildLo( $lo );?>
		</div>
		<div class="heading_article">
			<?php echo Yii::app()->user->generateBuyBtn($article->ca_id); ?>
			<div class="price"><?php echo number_format($article->ca_price, 0, '.', '');?> <span>&euro;</span></div>
			<div class="state"><?php echo $state->csl_title;?></div>
		</div>
		
		<?php
		if ($model->lang_data[0]->cml_text) {?>
		<p class="discription">
			<h4><?php echo Yii::t('site', 'Discription');?></h4>
			<?php echo $model->lang_data[0]->cml_text; ?>
		</p>
		<?php } ?>		
		
		<br clear="all"/>
	</div>
</div>
<br clear="all"/>




