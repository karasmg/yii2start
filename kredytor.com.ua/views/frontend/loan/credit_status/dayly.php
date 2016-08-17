<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;

$this->renderPartial('../partial/daysformats', array() );

$min_pay_summ	= $dogovor->minimalPayment();
$max_pay_summ	= $dogovor->countTotalSumm();
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
									<span id="payment_sum_title"><?php echo Yii::t ( 'site', 'Total credit payment' ) ?></span> <input
										type="text" value="<?php echo $max_pay_summ; ?>"
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
			<?php if($money_type === 'card') {?>
				<a class="btn-read-contract" href="<?php echo $this->createURL('personalpage/loan/contract/'.$fileContract.'.pdf') ?>" target="_blank"><?php echo Yii::t('site', 'Read contract') ?></a>
				<?php $this->widget('application.components.widgets.IdcsBlock', array( 'zayavkaNumb' =>  $fileContract ));?>
			<?php } ?>
		</div>
	</div>
</div>

<div class="widget-content widget-other">
	<div class="about-credit-box">
		<div class="left-box noBorder">
			<h2 class="h2-title"><?php echo Yii::t ( 'site', 'Agreement conditions' ) ?>:</h2>
			<div class="head-box noBorder">
				<div class="head-section">
					<p><?php echo Yii::t ( 'main', 'Date of issue' ) ?></p>
					<strong><?php  echo date('d.m.Y', $dogovor->d_date_zalog) ?></strong>
				</div>
				<div class="head-section">
					<p><?php echo Yii::t ( 'main', 'Credit sum' ) ?></p>
					<strong><?php  echo $dogovor->d_summ ?> <?php echo Yii::t ( 'site', 'GRN' ) ?>.</strong>
				</div>
				<div class="head-section">
					<p><?php echo Yii::t ( 'main', 'Return date' ) ?></p>
					<strong id="return-date"><?php  echo date('d.m.Y', $dogovor->d_date_vikup) ?></strong>
				</div>
				<div class="head-section">
					<p><?php echo Yii::t ( 'main', 'Return sum' ) ?></p>
					<strong><?php echo $dogovor->countTotalSumm(date('d.m.Y', $dogovor->d_date_vikup)); ?> <?php echo Yii::t ( 'site', 'GRN' ) ?>.</strong>
				</div>
			</div>
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