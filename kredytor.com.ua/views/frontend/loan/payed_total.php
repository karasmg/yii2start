<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>

<?=$this->buildPersonalMenu($isMenuDisabled, $step); ?>

<div class="widget-content">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Credit payed') ?></h2>
		<img class="checkmark" src="<?=Yii::app()->params->parent_host?>pic/checkmark.png" alt="">
		<p>
			<?php echo Yii::t('site', 'Credit payed total') ?>
		</p>
	</div>
</div>
