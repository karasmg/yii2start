<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>

<?= $this->buildPersonalMenu($isMenuDisabled, $step); ?>

<div class="widget-content">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Credit status troubles') ?></h2>
		<p>
			<?php echo Yii::t('site', 'Credit status troubles text') ?> <?php echo Yii::t('site', 'phone_1') ?>.<br />
		</p>
		
	</div>
</div>
