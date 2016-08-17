<?php 
$summ = 4500;
$days = 7;
$min_summ = 500;
$math = new daylyCalcClass();
$firstdayminpay = $math->_creditParams['_firstdayminpay'];
$percentstage = $math->_creditParams['_percentstage'];
if ( $_SERVER['REMOTE_ADDR'] == '46.182.81.50' || $_SERVER['REMOTE_ADDR'] == '83.99.145.9' ) {
	$min_summ = 1;
}

if(!empty($tempZayavka) && !empty($tempZayavka->t_summ) && !empty($tempZayavka->t_srok)) {
	$summ	= (int)$tempZayavka->t_summ;
	$days = (int)$tempZayavka->t_srok;
}
?>
<div class="slider-wrap">
	<div class="under-top">
			<div class="container">
				<div class="girl"></div>
				<div class="right">
					<h1><?php echo Yii::t('site', 'Instant money on the credit card!');?></h1>
					<h2><?php echo Yii::t('site', 'Give credit to 9000 UAH on the card for 5 minutes.');?></h2>
				</div>
				<div class="clear"></div>
			</div>
	</div>
			<div class="under-top bike_bg">
				<div class="container">
					<div class="girl bike_<?=Yii::app()->language;?>"></div>
					<div class="right">
						<h1><?= Yii::t('site', 'Credits up to 12 moths on technics');?></h1>
						<h2><?= Yii::t('site', 'More then 900 000 comissioning goods');?></h2>
					</div>
					<div class="clear"></div>
				</div>
			</div>
</div>
<div class="container_calc">
	<div class="calculator-bg" unselectable="on">
		<h3><?php echo Yii::t('site', 'mortgage calculator');?></h3>
		<div class="calculator tech">
			<form id="calc-credit-form" method="post" action="/<?php echo YII::app()->language;?>/personalpage/loan/paymentconfirmation/">
				<div class="calc-body">
					<input name="firstdayminpay" type="hidden" value="<?= $firstdayminpay ?>" class="firstdayminpay"/>
					<div class="calc-rows left">
						<div class="row">
							<div class="heading">
								<span><?php echo Yii::t('site', 'loan amount');?>:</span>
								<input name="sum" type="text" value="<?= $summ ?>" class="sum"/>
								<?php echo Yii::t('site', 'uha');?>
							</div>
							<div class="rules" unselectable="on">
								<div class="grids" unselectable="on">
									<div class="rule" unselectable="on"></div>
									<div class="rule passed"  unselectable="on" style="width:0px;"></div>
									<div class="pointer"  unselectable="on" style="left:0px;"></div>
								</div>
								<div class="val max" unselectable="on">9000</div>
								<div class="val min"  unselectable="on"><?=$min_summ;?></div>
								<div class="val step">50</div>
							</div>
						</div>

						<div class="row last-row">
							<div class="heading">
								<span><?php echo Yii::t('site', 'Credit term');?>:</span>
								<input name="period" type="text" value="<?= $days ?>" class="perriod"/>
								<?php echo Yii::t('site', 'days');?>
							</div>
							<div class="rules">
								<div class="grids">
									<div class="rule"></div>
									<div class="rule passed" style="width:0px;"></div>
									<div class="pointer" style="left:0px;"></div>
								</div>
								<div class="val max">30</div>
								<div class="val min">1</div>
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
								<td><div class="td"><?php echo Yii::t('site', 'sum');?>:</div></td>
								<td class="right sum"><div class="td"><span>4700</span> <?php echo Yii::t('site', 'uha');?></div></td>
							</tr>
							<tr>
								<td><div class="td"><?php echo Yii::t('site', 'percent');?>:</div></td>
								<td class="right percent"><div class="td"><span>1265</span> <?php echo Yii::t('site', 'uha');?></div></td>
							</tr>
							<tr>
								<td><?php echo Yii::t('site', 'total');?>:</td>
								<th class="right total"><span>5765</span> <?php echo Yii::t('site', 'uha');?></th>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="clear"></div>
					<p id="calc-take-kredit" class="take-kredit"><a id="get-credit" title="Получить кредит" href="#"><?php echo Yii::t('site', 'Get credit');?></a></p>
				</div>
			</form>
		</div>
	</div>
</div>