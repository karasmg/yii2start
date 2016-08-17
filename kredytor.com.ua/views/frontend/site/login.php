<?php
$this->pageTitle = Yii::t ( 'site', 'login' ) . '::' . Yii::app ()->name;
?>
<div class="widget-content">
	<h2 class="h2-title"><?php echo Yii::t('site', $h2Message);?></h2>
	<?php
	
$form = $this->beginWidget ( 'CActiveForm', array (
			'id' => 'login-form', 
			'enableClientValidation' => false, 
			'htmlOptions' => array (
					'class' => 'form-box add-other-cart' 
			), 
			'action'=>'/'. Yii::app ()->language . '/login',
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
		<?php echo $form->labelEx($model,'password'); ?>
		<?php
		$error = $form->error ( $model, 'password' );
		?>
		<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
			<?php echo $form->passwordField($model,'password', array('class'=>'inp-text')); ?>
			<?php echo $error; ?>
		</div>
	</div>
	<div class="inp-block">
		<button id="login-input" class="btn btn-big"><?=Yii::t('site', 'login');?></button>
	</div>
	<div class="inp-block">
		<a href="<?= '/' . Yii::app ()->language . '/register/' ?>"><?=Yii::t('site', 'register');?></a>
	</div>
	<div class="inp-block">
		<a href="<?= '/' . Yii::app ()->language . '/passrecovery/' ?>"><?=Yii::t('site', 'password recovery');?></a>
	</div>
	
	<?php $this->endWidget(); ?>
</div>
