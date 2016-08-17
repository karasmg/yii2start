<?php
$this->pageTitle = Yii::t ( 'site', 'password recovery' ) . '::' . Yii::app ()->name;
?>
<div class="widget-content">
	<?php
		
$form = $this->beginWidget ( 'CActiveForm', array (
			'id' => 'newpass-form', 
			'enableClientValidation' => false, 
			'htmlOptions' => array (
					'class' => 'form-box add-other-cart' 
			), 
			'errorMessageCssClass' => 'tooltip' 
	) );
	$labels = $model->attributeLabels ();
	if($form->error ( $model, 'token' )) 
		echo $form->error ( $model, 'token' );
	elseif ($model->user->errors)
		echo $form->errorSummary( $model->user );
	else {
	echo $form->hiddenField($model, 'token');
	?>
		<h2><?php echo Yii::t('site', 'Enter new password');?></h2>
		<div class="inp-block">
			<?php
			// Пароль
			$fieldName = 'password';
			echo $form->labelEx ( $model, $fieldName );
			$error = $form->error ( $model, $fieldName );
			?>
			<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
				<?php echo $form->passwordField($model, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
				<?php echo $error; ?>			
			</div>
		</div>
		<div class="inp-block">
			<?php
			// Пароль
			$fieldName = 'passwordConfirm';
			echo $form->labelEx ( $model, $fieldName );
			$error = $form->error ( $model, $fieldName );
			?>
			<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
				<?php echo $form->passwordField($model, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
				<?php echo $error; ?>			
			</div>
		</div>
	
	<div class="inp-block">
		<button class="btn btn-big"><?=Yii::t('main', 'Save');?></button>
	</div>
	
	<?php } 
	$this->endWidget(); ?>
</div>
