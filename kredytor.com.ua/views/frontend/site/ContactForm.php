<div class="form register">
	<div class="form_body">
		<h3><?php echo Yii::t('site', 'Contact form');?></h3>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'register-form',
		'enableClientValidation'=>false,
	)); 
	$labels = $model->attributeLabels();
	?>
		<div class="row">
			<?php echo $form->labelEx($model,'name'); ?>
			<?php echo $form->textField($model,'name', array('placeholder'=>$labels['name'])); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'phone'); ?>
			<?php echo $form->textField($model,'phone', array('placeholder'=>$labels['phone'])); ?>
			<?php echo $form->error($model,'phone'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email', array('placeholder'=>$labels['email'])); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'text'); ?>
			<?php echo $form->textArea($model,'text', array('placeholder'=>$labels['text'])); ?>
			<?php echo $form->error($model,'text'); ?>
		</div>

		<?php if(CCaptcha::checkRequirements()): /*проверка загружена ли каптча*/ ?>
		<div class="row capcha">
			<?php echo $form->labelEx($model,'verifyCode'); /*вывод текстовой метки verifyCode*/?>
			<?php echo $form->textField($model,'verifyCode'); /*выводим текстовое поле для ввода каптчи*/?>
			<?php $this->widget('CCaptcha'); /*выводим саму каптчу*/?>
			<?php echo $form->error($model,'verifyCode'); /*ошибка при вводе каптчи*/?>
		  </div>
		<?php endif; ?>

		<div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('site', 'Send')); ?>
		</div>

	<?php $this->endWidget(); ?>
	</div>
</div><!-- form -->