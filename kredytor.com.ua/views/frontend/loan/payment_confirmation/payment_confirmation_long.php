<?php


$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;

echo $this->buildPersonalMenu($isMenuDisabled, $step);

$math = new annuitetCalcClass();
$t_monthly_payment = $math->countMonthlySumm($model->summ, $model->srok);


?>

<?php

$form = $this->beginWidget ( 'CActiveForm', array ('id' => 'payment-confirmation-form', 'enableClientValidation' => false, 'htmlOptions' => array ('class' => 'form-box change-password' ), 'errorMessageCssClass' => 'tooltip' ) );
?>
<div class="widget-content">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Credit information confirmation') ?></h2>

		<?php
		$this->renderPartial('../partial/invoice', array(
			'zayavka_invoice_prods' => $zayavka_invoice_prods,
			'zayavka_invoice' 		=> $zayavka_invoice,
		));

		?>
		<input id="zayavka_invoice" type="hidden" value="<?= $zayavka_invoice->id ?>" name="zayavka_invoice">
		<?php echo $form->hiddenField($model, 'credit_targeted'); ?>

		<div class="inp-block">
			<?php
			// Период
			$fieldName = 'srok';
			?>
			<label><?php echo Yii::t('main', 'Credit term') ?>:</label>
			<div class="inp-box">
				<?php echo $model->srok.' '.ServiceHelper::number2text($model->srok, Yii::t ( 'site', 'months' ), Yii::t ( 'site', 'monthss' ), Yii::t ( 'site', 'month' ) )?>
				<?php echo $form->hiddenField($model, $fieldName); ?>
				<a style="margin-left: 15px;" href="<?php echo $this->createURL('personalpage/loan/getcredit/?changeparams=1') ?>"><?php echo Yii::t('site', 'Change') ?></a>
			</div>
		</div>


		<div class="inp-block">
			<?php
			// Cумма первого взноса
			$fieldName = 'summ_fp';
			?>
			<label><?php echo Yii::t('main', 'Summ first payment') ?>:</label>

			<div class="inp-box">
				<?php echo $model->summ_fp.' '.Yii::t('site', 'GRN'); ?>
				<?php echo $form->hiddenField($model, $fieldName); ?>
				<a style="margin-left: 15px;" href="<?php echo $this->createURL('personalpage/loan/getcredit/?changeparams=1') ?>"><?php echo Yii::t('site', 'Change') ?></a>
			</div>
		</div>


		<div class="inp-block">
		<?php
		// Cумма кредита
		$fieldName = 'summ';
		?>
			<label><?php echo Yii::t('main', 'Credit sum') ?>:</label>

			<div class="inp-box">
					<?php echo $model->summ.' '.Yii::t('site', 'GRN'); ?>
					<?php echo $form->hiddenField($model, $fieldName); ?>
				<a style="margin-left: 15px;" href="<?php echo $this->createURL('personalpage/loan/getcredit/?changeparams=1') ?>"><?php echo Yii::t('site', 'Change') ?></a>
			</div>
		</div>

		<div class="inp-block">
			<?php
			// Ежемесячный платеж
			?>
			<label><?php echo Yii::t('site', 'Monthly payment') ?>:</label>

			<div class="inp-box">
				<strong><?php echo $t_monthly_payment .' '.Yii::t('site', 'GRN'); ?></strong>
			</div>
		</div>

		<input type="hidden" value="<?= $model->iid ?>" name="iframe_toggle" id="iframe_toggle">
		<input type="hidden" value="annuitet" name="Zayavka__money_type" id="Zayavka__money_type">
		<?php echo $form->hiddenField($model, 'calc_type'); ?>
		<div id="msg_annuitet" ><strong><?= Yii::t('site', 'Read the terms of the contract.')?> <br><?= Yii::t('site', 'The original contract will be issued to you with goods')?></strong>
		</div>
		<div class="inp-block">
			<label><?= Yii::t('site', 'I agree with contract')?></label>
			<input type="checkbox" id="client_agree" name="client_agree">
			<button disabled="disabled" id="payment-confirmation" class="btn btn-big notactive"><?php echo Yii::t('site', 'Confirm') ?></button>
		</div>
		<div class="widget-content widget-other prolongation">
			<h2 class="h2-title"><?php echo Yii::t ( 'site', 'personal data confirmation' ) ?></h2>
			<p>
				<?php echo Yii::t ( 'site', 'personal data confirmation text' ) ?>
			</p>
		</div>
		<!-- </form> -->
		<!-- end form-box -->
	</div>
	<!-- end widget-main -->
</div>
<!-- end widget-content -->
<?php $this->endWidget(); ?>