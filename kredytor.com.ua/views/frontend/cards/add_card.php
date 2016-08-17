<?php
$this->pageTitle = Yii::t ('site', 'Personal page') . ' :: ' . Yii::app ()->name;
?>
<?= $this->buildPersonalMenu($isMenuDisabled, $step);?>

<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'id' => 'card-form',
		'enableClientValidation' => false,
		'htmlOptions' => array (
				'class' => 'form-box change-password' 
		),
		'errorMessageCssClass' => 'tooltip', 
) );
$labels = $model->attributeLabels ();
?>

<div class="widget-content">
	<div class="widget-main">
		<h2 class="h2-title"><?php echo Yii::t('site', 'Registration card') ?></h2>
		<p>
			<?php echo Yii::t('site', 'Fill in the details of the card') ?><br /> <?php echo Yii::t('site', 'Card will be temporarily blocked')?>
		</p>
		<form class="form-box add-other-cart" action="#">
			<div class="inp-block">
			<?php
			// Номер карты
			$fieldName = 'card_number';
			echo $form->labelEx ( $model, $fieldName );
			$error = $form->error ( $model, $fieldName );
			?>
			<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
				<?php echo $form->textField($model, $fieldName, array('placeholder'=>'1234 5678 9012 3456', 'class'=>'inp-text card-number')); ?>
				<?php echo $error; ?>
			</div>

			</div>
			<div class="inp-block">
				<?php
				// Месяц
				$fieldName = 'month';
				?>
				<label><?php echo Yii::t('site', 'Expiration Date') ?>:<span
					class="required">*</span></label>
				<div class="inp-box">
				<?php echo $form->dropDownList($model, $fieldName, array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'), array('placeholder'=>'01', 'class'=>'select select-month')); ?>
				<?php
				// Год
				$fieldName = 'year';
				$yy = array();
				for ($x=0; $x<10; $x++)
					$yy[date('y')+$x] = date('Y')+$x;

				echo $form->dropDownList($model, $fieldName, $yy, array('placeholder'=>date('Y'), 'class'=>'select select-year')); ?>
				</div>
			</div>
			<div class="inp-block">
				<?php
				// Именная карта?
				$fieldName = 'card_named';
				$error = $form->error ( $model, $fieldName );
				?>	
				<label class="checkbox-label">
				<?php echo $form->checkBox($model, $fieldName, array('class'=>'checkbox', 'data-id'=>'#imen-cart')); ?>
				<?php echo Yii::t('site', 'Personalized card') ?>
				<?php echo $error; ?>	
				</label>
			</div>
			<div id="imen-cart" class="inp-block">
			<?php
			// Владелец карты
			$fieldName = 'card_holder';
			echo $form->labelEx ( $model, $fieldName, array (
					'class' => 'disable' 
			) );
			$error = $form->error ( $model, $fieldName );
			?>	
				<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
					<?php
					$opt_attr =  array('placeholder'=>Yii::t('site', 'Enter Cardholder name'), 'disabled'=>'disabled', 'class'=>'inp-text disable');
					if($model->card_named==1) $opt_attr = array('placeholder'=>Yii::t('site', 'Enter Cardholder name'), 'class'=>'inp-text');
					echo $form->textField($model, $fieldName, $opt_attr); ?>
					<?php echo $error; ?>
				</div>
			</div>
			<div class="inp-block">
				<input type="button" class="btn btn-big back" onclick="history.back(-2);" value="<?php echo Yii::t('site', 'back')?>" >
				<button class="btn btn-big"><?php echo Yii::t('site', 'Register') ?></button>
			</div>
		</form>
		<!-- end form-box -->
	</div>
	<!-- end widget-main -->
<?php $this->endWidget(); ?>
</div>
<!-- end widget-content -->
