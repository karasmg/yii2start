<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>

<?=$this->buildPersonalMenu($isMenuDisabled, $step); ?>

<script type="text/javascript">
	$(document).ready(function() {
		ga('send', 'event', 'creditstatus', 'Заявка подана', $('#Zayavka__money_type').val(), Number($('#Zayavka_summ').val()) );
	});
    setTimeout(function(){
        location = '';
    },300000)
</script>
<div class="widget-content">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Your request is accepted') ?></h2>
		<img class="checkmark" src="<?=Yii::app()->params->parent_host?>pic/checkmark.png" alt="">
		<p>
			<input type="hidden" value="<?= $summ ?>" name="Zayavka_summ" id="Zayavka_summ">
			<input type="hidden" value="<?= $money_type ?>" name="Zayavka__money_type" id="Zayavka__money_type">
			<?php echo Yii::t('site', 'Request is accepted text') ?>
		</p>
	</div>
</div>
<script>

</script>
