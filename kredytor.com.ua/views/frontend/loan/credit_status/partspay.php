<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;

$this->renderPartial('../partial/daysformats', array() );

$min_pay_summ			= $dogovor->minimalPayment();
$max_pay_summ			= $dogovor->countTotalSumm();
$defaultPayment_summ	= (float)$dogovor->firstMinPay + $dogovor->countPenySumm();


if ( $defaultPayment_summ > $max_pay_summ ) {
	$defaultPayment_summ = $max_pay_summ;
}elseif ( $defaultPayment_summ < $min_pay_summ ) {
	$defaultPayment_summ = $min_pay_summ;
}
$step_val		= ($max_pay_summ-$min_pay_summ)/10;

?>

<?=$this->buildPersonalMenu ( $isMenuDisabled, $step );
$form = $this->beginWidget ( 'CActiveForm', array ('id' => 'credit-status-form', 'enableClientValidation' => false, 'action' => '', 'htmlOptions' => array ('class' => 'form-box change-password' ), 'errorMessageCssClass' => 'tooltip' ) );?>

<input type="hidden" name="dog_id" value="<?=$dogovor->d_id ?>" />

<div class="widget-content widget-other">
	<div class="about-credit-box">
		<div class="left-box">
			<div class="slider-box noBorder">
				<h2 class="h2-title"><?php echo Yii::t ( 'site', 'Make payment' ) ?>:</h2>
				<div class="calculator tech">
					<div class="calc-body">
						<div class="calc-rows">
							<div class="row">
								<div class="heading">
									<span id="payment_sum_title"><?php echo Yii::t ( 'site', 'Regular payment pay' ) ?></span> <input
										type="text" value="<?php echo $defaultPayment_summ; ?>"
										name="sum" class="sum" />
								</div>
								<p><?php echo Yii::t ( 'site', 'Minimum payment' ) ?></p>
								<strong><?php echo $min_pay_summ. ' ' . Yii::t ( 'site', 'GRN' ) ?>.</strong>
								<div class="rules" unselectable="on">
									<div class="grids" unselectable="on">
										<div class="rule" unselectable="on"></div>
										<div class="rule passed" unselectable="on" style="width: 0px;"></div>
										<div class="pointer" unselectable="on" style="left: 0px;"></div>
									</div>
									<div class="val max" unselectable="on">
										<?php echo $max_pay_summ; ?>
									</div>
									<div class="val min" unselectable="on">
										<?php 
										echo $min_pay_summ;
										?>
									</div>
									<div class="val step"><?= $step_val; ?></div>
								</div>
							</div>
						</div>
						
						<?php 
						$first = false;
						foreach ( Yii::app()->params['sitePaySys'] as $paySys=>$payTexts ) { ;?>
						<div class="paysys <?= $paySys;?>">
							<label>
								<input class="paysys_radio" type="radio" value="<?=$paySys;?>" name="paysystem"<?=( !$first ? ' checked="checked"' : '');?>>
								<?= Yii::t('site', $payTexts['title']); ?>
							</label>
							<p<?=( $first ? ' class="hide"' : '');?>><?= Yii::t('site', $payTexts['text 1']); ?></p>
							<p class="discription<?=( $first ? ' hide' : '');?>"><?= Yii::t('site', $payTexts['text 2']); ?></p>	
							<p class="servise_tax<?=( $first ? ' hide' : '');?>"><?= Yii::t('site', 'Servise tax'); ?> - <span></span> <?= Yii::t('site', 'GRN'); ?></p>				
						</div>
						<? 
							if ( !$first ) {
								$first = $paySys;
							}
						} ?>
						
						<div class="bottom-form">							
							<button class="btn btn-primary createPayment" data-provider="<?=$first;?>"><?php echo Yii::t ( 'site', 'Do pay' ) ?></button>
							<p id="prolongation-info" class="hide">
								<?php echo Yii::t ( 'site', 'Prolongation till' ) ?> <strong id="end_date"></strong>. <br>
								<?php echo Yii::t ( 'site', 'credit body' )?> <strong id="body_sum"></strong>,
								<?php echo Yii::t ( 'site', 'credit percent' )?> <strong
									id="calculation_sum"></strong>.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="right-box">
			<p><?php echo Yii::t ( 'site', 'If you want you should pay' ) ?></p>
			<?php $this->widget('application.components.widgets.IdcsBlock', array( 'zayavkaNumb' =>  $fileContract ));?>
		</div>
	</div>
</div>

<div class="widget-content widget-other">
	<div class="about-credit-box">
		<div class="left-box noBorder">
			<h2 class="h2-title"><?php echo Yii::t ( 'site', 'Agreement conditions' ) ?>:</h2>
			<table class="payment_perriod_table">
				<thead>
					<tr>
						<th rowspan="2"><?php echo Yii::t ( 'site', '№ R/P' ) ?></th>
						<th rowspan="2"><?php echo Yii::t ( 'site', 'Payment date' ) ?></th>
						<th rowspan="2"><?php echo Yii::t ( 'site', 'Payment sum' ).', '.Yii::t ( 'site', 'uha' ) ?></th>
						<th colspan="2"><?php echo Yii::t ( 'site', 'Payment parts' ) ?></th>
						<th rowspan="2"><?php echo Yii::t ( 'site', 'Credit left' ).', '.Yii::t ( 'site', 'uha' )?></th>
					</tr>
					<tr>
						<th><?php echo '%, '.Yii::t ( 'site', 'uha' ) ?></th>
						<th><?php echo Yii::t ( 'site', 'Credit payment part' ).', '.Yii::t ( 'site', 'uha' )?></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$math = new partspayCalcClass();
				$calculation = $math->makeCalculation(
						$firstDogovor->d_summ,
						$firstDogovor->d_term,
						date('Y-m-d', strtotime($firstDogovor->d_PaymentDay)),
						$firstDogovor->d_termmodifier,
						$firstDogovor->d_firstdayminpay,
						$firstDogovor->d_percentstage
				);
				foreach($calculation['table'] as $row) {
					?>
					<tr<?=( time() > strtotime($row['PayTimeStamp']) ? ' class="gray"' : '' );?>>
						<td><?=$row['i'];?></td>
						<td><?=$row['PayTimeStamp']?></td>
						<td><?=number_format($row['paySum'], 2);?></td>
						<td><?=number_format($row['percentUse'], 2);?></td>
						<td><?=number_format($row['bodyPayment'], 2);?></td>
						<td><?=number_format($row['left'], 2);?></td>
					</tr>
				<? 	} ?>
				<tr>
					<td></td>
					<td><strong><?=Yii::t('site', 'Total')?></strong></td>
					<td><strong><?=number_format($calculation['total']['paysum'], 2);?></strong></td>
					<td><strong><?=number_format($calculation['total']['percent'], 2);?></strong></td>
					<td><strong><?=number_format($calculation['total']['body'], 2);?></strong></td>
					<td></td>
				</tr>
				</tbody>
			</table>
			<span id="return-date" class="hide"><?php  echo date('d.m.Y', $dogovor->d_date_vikup) ?></span>
		</div>
	</div>	
</div>

<div class="widget-content widget-other prolongation">
	<h2 class="h2-title"><?php echo Yii::t ( 'site', 'Prolongation' ) ?></h2>
	<p>
		<?php echo Yii::t ( 'site', 'Prolongation text' )?>
	</p>
</div>
<?php $this->endWidget(); ?>


<div class="overlay"></div>
<div id="ajax_response"></div>