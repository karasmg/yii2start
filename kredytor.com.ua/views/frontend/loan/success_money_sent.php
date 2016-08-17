<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>

<?=$this->buildPersonalMenu($isMenuDisabled, $step); ?>
<script type="text/javascript">
    setTimeout(function(){
        location = '<?php echo $this->createAbsoluteURL('/'.Yii::app()->language.'/personalpage/loan/creditstatus') ?>';
    },60000)
</script>
<div class="widget-content">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Success operation') ?>!</h2>
		<img class="checkmark" src="<?=Yii::app()->params->parent_host?>pic/checkmark.png" alt="">
		<p>
			<?php echo Yii::t('site', 'The money will be sent to your card any time soon') ?>
		</p>
	</div>
</div>
