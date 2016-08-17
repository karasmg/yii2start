<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>

<?= $this->buildPersonalMenu($isMenuDisabled, $step); ?>
<script type="text/javascript">
	$(document).ready(function() {
		ga('send', 'event', 'creditstatus', 'Заявка утверждена', $('#Zayavka__money_type').val(), Number($('#Zayavka_summ').val()) );
	});
	setTimeout(function(){
		location = '<?php echo $this->createAbsoluteURL('/'.Yii::app()->language.'/personalpage/loan/creditstatus') ?>';
	},60000)
</script>
<div class="widget-content">
	<input type="hidden" value="<?= $summ ?>" name="Zayavka_summ" id="Zayavka_summ">
	<input type="hidden" value="<?= $money_type ?>" name="Zayavka__money_type" id="Zayavka__money_type">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Your request is approved') ?></h2>
		<img class="checkmark" src="<?=Yii::app()->params->parent_host?>pic/checkmark.png" alt="" >
		<p>
			<?php echo Yii::t('site', 'Request is approved text') ?>
		</p>
		<a href="reject"><input type="button" class="btn btn-big back" onclick="" value="<?php echo Yii::t('site', 'loan cancel')?>" ></a>
	</div>
</div>
