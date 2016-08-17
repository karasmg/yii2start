<?php
$this->pageTitle = Yii::t ( 'site', 'password recovery' ) . '::' . Yii::app ()->name;
?>
<div class="widget-content">
	<h2 class="h2-title"><?php echo Yii::t('site', 'Enter your e-mail');?></h2>
	<?php
	
$form = $this->beginWidget ( 'CActiveForm', array (
			'id' => 'passrecovery-form', 
			'enableClientValidation' => false, 
			'htmlOptions' => array (
					'class' => 'form-box add-other-cart' 
			), 
			'errorMessageCssClass' => 'tooltip' 
	) );
	$labels = $model->attributeLabels ();
	// var_dump($model->errors);
	?>
	<div class="inp-block">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php
		$error = $form->error ( $model, 'username' );
		?>
		<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
			<?php echo $form->textField($model,'username', array('placeholder'=>$labels['username'], 'class'=>'inp-text')); ?>
			<?php echo $error; ?>			
		</div>
	</div>
	<div class="inp-block">
		<button class="btn btn-big"><?=Yii::t('site', 'Send');?></button>
	</div>
	
	<?php $this->endWidget(); ?>
</div>
