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
										name="sum" class="sum" id="status-pay-sum"/>
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
							<button id="create-payment" class="btn btn-primary createPayment" data-provider="<?=$first;?>"><?php echo Yii::t ( 'site', 'Do pay' ) ?></button>
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
			<a id="status-read-contract" class="btn-read-contract" href="<?php echo $this->createURL('personalpage/loan/contract/'.$fileContract.'.pdf') ?>" target="_blank"><?php echo Yii::t('site', 'Read contract') ?></a>
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

<?php /*


<div class="overlay"></div>
<div class="popup popup-2">
	<a class="close"></a>
	<p class="title-popup"><?php echo Yii::t ( 'site', 'Confirmation of prolongation payment' ) ?> <span
			class="days_prol">0</span>
	</p>
	<div class="popup-main">
		<p>
			<?php echo Yii::t ( 'site', 'Registered card' ) ?> | <a href="javascript:link_other_card();"><?php echo Yii::t ( 'site', 'Other card' ) ?></a>
		</p>
		<p>
			<strong><?php echo Yii::t ( 'site', 'Select a card from the list of cards' ) ?></strong>
		</p>
		<form class="form-box" action="#">
			<div class="inp-block">
			<?php
			// Карточка
			$fieldName = 'i_placement';
			echo $form->labelEx ( $invoice, $fieldName );
			?>
				<div class="inp-box">
					<?php echo $form->dropDownList($invoice, $fieldName, $select_items['cards'], array('placeholder'=>'XXXX XXXX XXXX XXXX', 'class'=>'select')); ?>
				</div>
			</div>
			<div class="inp-block">
			<?php
			// Cумма кредита
			$fieldName = 'summ';
			?>
				<label><?php echo Yii::t('main', 'Credit sum') ?>:</label>
					<p id="prolongation_sum">
						<strong>0.00</strong> <span><?php echo Yii::t ( 'site', 'GRN' ) ?></span>
					</p>
			</div>
			<div class="inp-block">
				<button class="btn btn-primary" type="submit"><?php echo Yii::t ( 'site', 'Execute' ) ?></button>
			</div>
		</form>
	</div>
</div>
<div class="popup popup-4">
	<a class="close"></a>
	<p class="title-popup"><?php echo Yii::t ( 'site', 'Confirmation of prolongation payment' ) ?> <span
			class="days_prol">0</span>
	</p>
	<div class="popup-main">
		<p>
			<a href="javascript:link_registered_card();"><?php echo Yii::t ( 'site', 'Registered card' ) ?></a> | <?php echo Yii::t ( 'site', 'Other card' ) ?>
		</p>
		<p>
			<strong><?php echo Yii::t ( 'site', 'Select a card from the list of cards' ) ?></strong>
		</p>
		<form class="form-box add-other-cart" action="#">
			<div class="inp-block">
				<label for="field-1">Номер карты:</label>
				<div class="inp-box">
					<input id="field-1" type="text" class="inp-text"
						placeholder="1234 5678 9012 3456" />
				</div>
			</div>
			<div class="inp-block">
				<label>Действительна до:</label>
				<div class="inp-box">
					<select class="select select-month">
						<option>01</option>
						<option>02</option>
						<option>03</option>
						<option>04</option>
						<option>05</option>
						<option>06</option>
						<option>07</option>
						<option>08</option>
						<option>09</option>
						<option>10</option>
						<option>11</option>
						<option>12</option>
					</select> <select class="select select-year">
						<option>2015</option>
						<option>2016</option>
						<option>2017</option>
						<option>2018</option>
						<option>2019</option>
						<option>2020</option>
					</select>
				</div>
			</div>
			<div class="inp-block">
				<label for="field-2">CVV код карточки:</label>
				<div class="inp-box">
					<input id="field-2" type="text" class="inp-text" placeholder="123" />
					<div id="keyboard">
						<div id="vkeyboard">
							<input type="button" class="inp-btn" value="1" /> <input
								type="button" class="inp-btn" value="2" /> <input type="button"
								class="inp-btn" value="3" /> <input type="button"
								class="inp-btn" value="4" /> <input type="button"
								class="inp-btn" value="5" /> <input type="button"
								class="inp-btn" value="6" /> <input type="button"
								class="inp-btn" value="7" /> <input type="button"
								class="inp-btn" value="8" /> <input type="button"
								class="inp-btn" value="9" /> <input type="button"
								class="inp-btn" value="0" /> <input class="clearInp inp-btn"
								type="button" value="Стереть" />
						</div>
					</div>
				</div>
			</div>
			<div class="inp-block">
				<label class="checkbox-label"><input data-id="#imen-cart"
					type="checkbox" class="checkbox" />Именная карта</label>
			</div>
			<div id="imen-cart" class="inp-block">
				<label for="field-3" class="disable">Владелец карты:</label>
				<div class="inp-box">
					<input id="field-3" type="text" class="inp-text disable"
						disabled="disabled" placeholder="Введите имя владельца" />
				</div>
			</div>
			<div class="inp-block">
				<label>Сумма: <strong>40 грн.</strong></label>
			</div>
			<div class="inp-block align-right">
				<button class="btn btn-primary">Оплатить</button>
			</div>
		</form>
		<!-- end form-box -->
	</div>
</div>
<div class="popup popup-1">
	<a class="close"></a>
	<p class="title-popup"><?php echo $conditions; ?></p>
	<div class="popup-main">
		<p>Платеж успешно проведен</p>
		<div class="icon-box">
			<img src="images/icon-ok.png" alt="" />
		</div>
		<a href="#">Оставить отзыв</a>
	</div>
</div>
<div class="popup popup-3">
	<a class="close"></a>
	<p class="title-popup"><?php echo $conditions; ?></p>
	<div class="popup-main">
		<ul>
			<li><span>Номер карты:</span> 1234-ХХХХ-ХХХХ-5678</li>
			<li><span>Сумма:</span> 40 грн.</li>
		</ul>
		<p>
			Ждите, пока выполняется запрос <img src="images/ajax_loader.gif"
				alt="" />
		</p>
	</div>
</div>
 * 
 */?>