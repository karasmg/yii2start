<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::t('site', 'register').' :: '.Yii::app()->name;
?>
<div class="center-box">
<?= $this->buildPersonalMenu($isGuest, $step);?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	'htmlOptions'	=> array(
		'class'=>'form-box change-password',
	),
	'errorMessageCssClass' => 'tooltip',
)); 
$labels		= $model->attributeLabels();
?>
	<?= $form->errorSummary( $model );?>
	<div class="widget-content">
		<h2 class="h2-title"><?=Yii::t('site', 'Account data');?></h2>
		<div class="inp-block">
			<?php
			//Имейл
			$fieldName = 'u_email';
			echo $form->labelEx($model, $fieldName); 
			$error = $form->error($model, $fieldName);
			?>
			<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
				<?php echo $form->textField($model, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
				<?php echo $error; ?>			
			</div>
		</div>
		<div class="inp-block">
			<?php
			//Пароль
			$fieldName = 'u_pass';
			echo $form->labelEx($model, $fieldName); 
			$error = $form->error($model, $fieldName);
			?>
			<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
				<?php echo $form->passwordField($model, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
				<?php echo $error; ?>			
			</div>
		</div>
		<div class="inp-block">
			<?php
			//Пароль
			$fieldName = 'u_pass_confirm';
			echo $form->labelEx($model, $fieldName); 
			$error = $form->error($model, $fieldName);
			?>
			<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
				<?php echo $form->passwordField($model, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
				<?php echo $error; ?>			
			</div>
		</div>
		<div class="inp-block ">
			<button class="btn" type="submit"><?=Yii::t('site', 'next');?></button>
		</div>
		<label class="checkbox-label">
			<?php echo $form->checkBox($model, 'u_subscribe', array('class'=>'checkbox')); ?>
			<?=Yii::t('site', 'Want to subscribe');?>
		</label>
	</div>	
<?php $this->endWidget(); ?>
</div>