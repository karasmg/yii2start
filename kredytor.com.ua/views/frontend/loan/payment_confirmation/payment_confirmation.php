<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>


<?php


$form = $this->beginWidget ( 'CActiveForm', array ('id' => 'payment-confirmation-form', 'enableClientValidation' => false, 'htmlOptions' => array ('class' => 'form-box change-password' ), 'errorMessageCssClass' => 'tooltip' ) );
$conditions = Yii::t ( 'site', 'You lend' ) . ' '; // Вы оформлюте займ на сумму
$conditions .= $model->summ . ' '; // 4500
$conditions .= Yii::t ( 'site', 'GRN' ) . ' '; // грн
$conditions .= Yii::t ( 'site', 'term on' ) . ' '; // сроком на
$conditions .= $model->srok . ' '; // 7
$conditions .= ServiceHelper::number2text( $model->srok, Yii::t ( 'site', 'days' ), Yii::t ( 'site', 'dayss' ), Yii::t ( 'site', 'day' ) ) . '.<br>'; // день
$conditions .= date ( "d.m.Y" , $model->getVikupDate() ) . ' '; // 03.11.2015
$conditions .= Yii::t ( 'site', 'you pay' ) . ' '; // вы платите
$conditions .= $model->summ + $model->countsummPercent(). ' '; // 5130
$conditions .= Yii::t ( 'site', 'GRN' ) . ' '; // грн

?>
<?= $form->errorSummary( $model );?>
<div class="top-heading">
	<h2><?php echo $conditions; ?></h2>
	<?php 
	if ( $model->state != 2 ) { ?>
	<a
		href="<?php echo $this->createURL('personalpage/loan/getcredit/?changeparams=1') ?>"><?php echo Yii::t('site', 'Change') ?></a>
	<?php } ?>
	<br style="clear: both;">
</div>
<div class="widget-content">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Confirm entry') ?></h2>
		<p>
			<?php echo Yii::t('site', 'Please, enter your data') ?><br />
		</p>
		<!--  <form class="form-box add-other-cart" action="#"> -->
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
			// Период
			$fieldName = 'srok';
			?>
			<label><?php echo Yii::t('main', 'Credit term') ?>:</label>
			<div class="inp-box">
					<?php echo $model->srok.' '.ServiceHelper::number2text( $model->srok, Yii::t ( 'site', 'days' ), Yii::t ( 'site', 'dayss' ), Yii::t ( 'site', 'day' ) )?>
					<?php echo $form->hiddenField($model, $fieldName); ?>
			</div>
		</div>

		<div class="inp-block">
			<?php
			// К оплате
			$fieldName = 'summPercent';
			?>
				<label><?php echo Yii::t('site', 'To pay') ?>:</label>
			<div class="inp-box">
					<?php echo $model->summ+$model->summPercent.' '.Yii::t('site', 'GRN') ; ?>
					<?php echo $form->hiddenField($model, $fieldName); ?>
				</div>
		</div>


        <div class="inp-block">
            <?php
            // Получение денег
            $fieldName = '_money_type';
            echo $form->labelEx ( $model, $fieldName );
            $selected = (!empty($model->_money_type))?$model->_money_type:'0';
            ?>
            <div class="inp-box">
                <?php echo $form->dropDownList($model, $fieldName, $select_items['_money_type'], array('options' => array($selected => array('selected'=>true)), 'class'=>'select')); ?>
            </div>
        </div>

	    	<div class="inp-block hidden">
			<?php
			// Карточка
			$fieldName = 'card';
			echo $form->labelEx ( $model, $fieldName );

			foreach($select_items['cards'] as $key=>$value){
				$last_key = $key;
			}
			$selected = count($select_items['cards'])>2?'0':$last_key;
			?>
				<div class="inp-box">
					<?php echo $form->dropDownList($model, $fieldName, $select_items['cards'], array('options' => array($selected=>array('selected'=>true)), 'class'=>'select')); ?>
				</div>
                <div class="inp-block">
                    <a class="btn-add-new-cart" href="<?php echo $this->createURL('/personalpage/cards/addcard') ?>"><?php echo Yii::t('site', 'Add a new card') ?></a>
                </div>
		</div>

		<div class="inp-block hidden">
			<?php
			// Менеджер
			$fieldName = 'manager';
			echo $form->labelEx ( $model, $fieldName );
			?>
				<div class="inp-box">
					<?php echo $form->dropDownList($model, $fieldName, $select_items['managers'], array('options' => array('0'=>array('selected'=>true)), 'class'=>'select')); ?>
				</div>
		</div>
		<input type="hidden" value="<?= $model->iid ?>" name="iframe_toggle" id="iframe_toggle">
		<div id="msg_online" ><strong><?= Yii::t('site', 'Read the terms of the contract.')?> <br><?= Yii::t('site', 'The original agreement will be available in your account after processing the loan.')?></strong>
		</div>
		<div id="msg_cash" ><strong><?= Yii::t('site', 'Read the terms of the contract.')?> <br><?= Yii::t('site', 'The original contract will be issued to you at the department in obtaining credit.')?></strong>
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