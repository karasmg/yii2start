<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>

<?= $this->buildPersonalMenu($isMenuDisabled, $step); ?>
<script>
	$(document).ready(function() {
		ga('send', 'event', 'creditstatus', 'Заявка утверждена', $('#Zayavka__money_type').val(), Number($('#Zayavka_summ').val()) );
	});
</script>

<div class="widget-content">
	<input type="hidden" value="<?= $summ ?>" name="Zayavka_summ" id="Zayavka_summ">
	<input type="hidden" value="<?= $money_type ?>" name="Zayavka__money_type" id="Zayavka__money_type">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Your request is approved') ?></h2>
		<img class="checkmark" src="<?=Yii::app()->params->parent_host?>pic/checkmark.png" alt="" >
		<p>
			<?php echo Yii::t('site', 'Request is approved text online card') ?>
		</p>
		<div class="inp-block">
			<a href="<?php echo $this->createURL('personalpage/loan/contract/'.$fileContract.'.pdf') ?>" target="_blank"><?php echo Yii::t('site', 'Read contract') ?></a>
		</div>
		<br>
		<div class="inp-block">
            <a href="<?php echo $this->createURL('personalpage/loan/sendmoneytocard') ?>" class="btn btn-big"><?php echo Yii::t('site', 'Receive') ?></a>
			<a id="loan_reject" href="reject" class="btn btn-big back"><?php echo Yii::t('site', 'loan cancel')?></a>
        </div>
	</div>
</div>

