<?php
$math = new annuitetCalcClass();
$firstdayminpay = $math->_creditParams['_firstdayminpay'];
$percentstage = $math->_creditParams['_percentstage'];
$srok_limits = $math->_srok_limits;
$summ_limits = $math->_summ_limits;
$min_credit_summ = $summ_limits['_minsumm'];
$t_summ_fp = $tempZayavka->t_summ_fp;
$t_summ = $tempZayavka->t_summ;
$months = $tempZayavka->t_srok;
$t_monthly_payment = $math->countMonthlySumm($t_summ, $months);
if($min_credit_summ>$t_summ) {
	$min_credit_summ = $t_summ;
}
$max_summ_fp = round(floatval($prod_total - $min_credit_summ), 2);

$this->renderPartial('../partial/monthsformats', array() );
$text_months = ServiceHelper::number2text( $months, Yii::t ( 'site', 'months' ), Yii::t ( 'site', 'monthss' ), Yii::t ( 'site', 'month' ) );
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;

echo $this->buildPersonalMenu($isMenuDisabled, $step);
?>

<div id="get-credit" class="content-page">
	<div class="container_calc">
		<div class="calculator-bg" unselectable="on">
			<input name="long_credit" id="long_credit" type="hidden" value="<?= $min_credit_summ ?>"/>
			<input name="parent_host" id="parent_host" type="hidden" value="<?= Yii::app()->params['parent_host'] ?>"/>
			<h3><?php echo Yii::t('site', 'Choose parameters');?></h3>
			<div class="calculator tech">
				<form id="calc-credit-form" method="post" action="/<?php echo YII::app()->language;?>/personalpage/loan/paymentconfirmation/">
				<div class="calc-body">
					<input name="firstdayminpay" type="hidden" value="<?= $firstdayminpay ?>" class="firstdayminpay"/>
					<input name="sum" id="sum" type="hidden" value="<?= $t_summ ?>" class="sum"/>
					<div class="calc-rows left">
						<div class="row">
							<div class="heading">
								<span><?php echo Yii::t('site', 'First payment');?>:</span>
								<input name="sum_fp" id="sum_fp" type="text" value="<?= $t_summ_fp ?>" class="sum"/>
								<?php echo Yii::t('site', 'uha');?>
							</div>
							<div class="rules" unselectable="on">
								<div class="grids" unselectable="on">
									<div class="rule" unselectable="on"></div>
									<div class="rule passed"  unselectable="on" style="width:0px;"></div>
									<div class="pointer"  unselectable="on" style="left:0px;"></div>
								</div>
								<div class="val max"  id="max_summ_fp" unselectable="on"><?= $max_summ_fp ?></div>
								<div class="val min"  id="min_summ_fp" unselectable="on"><?= $min_summ_fp ?></div>
								<div class="val step">50</div>
							</div>
						</div>

						<div class="row last-row">
							<div class="heading">
								<span><?php echo Yii::t('site', 'Credit term');?>:</span>
								<input name="period" type="text" value="<?= $months ?>" class="perriod"/>
								<span id="text_months"><?= $text_months ?></span>
							</div>
							<div class="rules">
								<div class="grids">
									<div class="rule"></div>
									<div class="rule passed" style="width:0px;"></div>
									<div class="pointer" style="left:0px;"></div>
								</div>
								<div class="val max"><?= $srok_limits['_maxsrok'] ?></div>
								<div class="val min"><?= $srok_limits['_minsrok'] ?></div>
								<div class="val step">1</div>
							</div>
						</div>
						<p><?php echo Yii::t('site', 'percent stage');?> 2% <?php echo Yii::t('site', 'per day');?></p>
					</div>
					<div class="calc-data left">
						<div class="title">
							<?php echo Yii::t('site', 'percent stage');?><br/>
							<span><?= $percentstage ?></span>%
							<?php echo Yii::t('site', 'per day');?><br/>
						</div>
						<table>
							<thead>
							<tr>
								<th class="black" colspan="2"><?php echo Yii::t('site', 'Payment sum');?>:</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td><div class="td"><?php echo Yii::t('site', 'First payment');?>:</div></td>
								<td class="right sum"><div class="td"><span id="first_payment"><?= $t_summ_fp ?></span> <?php echo Yii::t('site', 'uha');?></div></td>
							</tr>
							<tr>
								<td><div class="td"><?php echo Yii::t('site', 'Credit sum');?>:</div></td>
								<td class="right credit"><div class="td"><span id="credit-summ"><?= $t_summ ?></span> <?php echo Yii::t('site', 'uha');?></div></td>
							</tr>
							<tr>
								<td><?php echo Yii::t('site', 'Monthly payment');?>:</td>
								<th class="right"><span id="monthly_payment"><?= $t_monthly_payment ?></span> <?php echo Yii::t('site', 'uha');?></th>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="clear"></div>
					<p id="calc-take-kredit" class="take-kredit"><a id="get-credit" title="<?php echo Yii::t('site', 'Get credit');?>" href="#"><?php echo Yii::t('site', 'Get credit');?></a></p>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php

?>