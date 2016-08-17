</div></div>

<div class="form evaluation">
	<div class="form_body">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'register-form',
		'enableClientValidation'=>false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	)); 
	?>

<div class="container <?php echo Yii::app()->language;?>">
<div class="content">
<div class="page_content art1icle">
	<h1><?php echo Yii::t('site', $h1);?></h1>
</div>
</div></div>

<div class="grey-bg topM10">
	<div class="container <?php echo Yii::app()->language;?>">
		<h3><?php echo Yii::t('site', 'Client infiormation');?></h3>
		<div class="block50 mL0">
			<div class="row">
				<?php echo $form->labelEx($model,'fg_fio'); ?>
				<?php echo $form->textField($model,'fg_fio', array('placeholder'=>$labels['fg_fio'])); ?>
				<?php echo $form->error($model,'fg_fio'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'fg_phone'); ?>
				<?php echo $form->textField($model,'fg_phone', array('placeholder'=>$labels['fg_phone'])); ?>
				<?php echo $form->error($model,'fg_phone'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'fg_city'); ?>
				<?php echo $form->textField($model,'fg_city', array('placeholder'=>$labels['fg_city'])); ?>
				<?php echo $form->error($model,'fg_city'); ?>
			</div>
		</div>
		<div class="block50">			
			<div class="row">
				<?php echo $form->labelEx($model,'fg_clienttype'); ?>
				<?php echo $form->dropDownList($model,'fg_clienttype', $options['fg_clienttype'], array('class'=>'customSelect', 'style'=>'width:273px;') ); ?>
				<?php echo $form->error($model,'fg_clienttype'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'fg_perriod'); ?>
				<?php echo $form->dropDownList($model,'fg_perriod', $options['fg_perriod'], array('class'=>'customSelect', 'style'=>'width:273px;') ); ?>
				<?php echo $form->error($model,'fg_perriod'); ?>
			</div>
		</div>
		<br clear="all"/>
	</div>
</div>

<div class="container <?php echo Yii::app()->language;?>">
<div class="content">
	<h3><?php echo Yii::t('site', 'Product information');?></h3>
	<div class="block50 mL0">
		<div class="row">
			<?php echo $form->labelEx($model,'fg_prodname'); ?>
			<?php echo $form->textField($model,'fg_prodname', array('placeholder'=>$labels['fg_prodname'])); ?>
			<?php echo $form->error($model,'fg_prodname'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'fg_weight'); ?>
			<?php echo $form->textField($model,'fg_weight', array('placeholder'=>$labels['fg_weight'], 'style'=>'width:50px;')); ?>
			<?php echo Yii::t('site', 'Gramms');?>.
			<?php echo $form->error($model,'fg_weight'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'fg_cleimo'); ?>
			<?php echo $form->dropDownList($model,'fg_cleimo', $options['fg_cleimo'], array('class'=>'customSelect', 'style'=>'width:273px;') ); ?>
			<?php echo $form->error($model,'fg_cleimo'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'fg_probe'); ?>
			<?php echo $form->textField($model,'fg_probe', array('placeholder'=>$labels['fg_probe'])); ?>
			<?php echo $form->error($model,'fg_probe'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'fg_color'); ?>
			<?php echo $form->textField($model,'fg_color', array('placeholder'=>$labels['fg_color'])); ?>
			<?php echo $form->error($model,'fg_color'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'fg_state'); ?>
			<?php echo $form->radioButtonList($model,'fg_state', $options['fg_state']); ?>
			<?php echo $form->error($model,'fg_state'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'fg_foto'); ?>
			<?php echo $form->fileField($model,'fg_foto', array('class'=>'filefield')); ?>
			<?php
			if ( $model->fg_foto ) {
				echo '<img src="'.$model->fg_foto.'" alt="" style="max-width:40px; max-height:40px;"/><input type="hidden" name="FormsGold[fg_foto]" value="'.$model->fg_foto.'" />';
			}
			?>
			<?php echo $form->error($model,'fg_foto'); ?>
		</div>
	</div>
	<div class="block50">
		<div class="row">
			<?php echo $form->labelEx($model,'fg_vstavki'); ?>
			<?php echo $form->checkBoxList($model,'fg_vstavki', $options['fg_vstavki']); ?>
			<?php echo $form->textArea($model,'fg_vstavki_other'); ?>
			<?php echo $form->error($model,'fg_vstavki'); ?>
			<?php echo $form->error($model,'fg_vstavki_other'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'fg_brilliants'); ?>
			<?php echo $form->textArea($model,'fg_brilliants'); ?>
			<?php echo $form->error($model,'fg_brilliants'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'fg_commnets'); ?>
			<?php echo $form->textArea($model,'fg_commnets'); ?>
			<?php echo $form->error($model,'fg_commnets'); ?>
		</div>
	</div>
	<br clear="all"/>
	<?php if(CCaptcha::checkRequirements()): /*проверка загружена ли каптча*/ ?>
	<div class="row capcha">
		<?php echo $form->labelEx($model,'verifyCode'); /*вывод текстовой метки verifyCode*/?><br/>
		<?php echo $form->textField($model,'verifyCode'); /*выводим текстовое поле для ввода каптчи*/?><br/>
		<?php $this->widget('CCaptcha'); /*выводим саму каптчу*/?>
		<?php echo $form->error($model,'verifyCode'); /*ошибка при вводе каптчи*/?>
	</div>
	<?php endif; ?>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site', 'Send')); ?>
	</div>
	
</div></div>

	<?php $this->endWidget(); ?>
	</div>
</div><!-- form -->

<div class="container <?php echo Yii::app()->language;?>">
<div class="content">

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
