<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>

<?= $this->buildPersonalMenu($isMenuDisabled, $step); ?>

<div class="widget-content">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Request is rejected') ?></h2>
		<img class="checkmark" src="<?=Yii::app()->params->parent_host?>pic/exclamation.png" alt="" >
		<p>
			<?=$text ?>
		</p>
		
	</div>
</div>
