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
				<?php echo $form->labelEx($model,'ft_fio'); ?>
				<?php echo $form->textField($model,'ft_fio', array('placeholder'=>$labels['ft_fio'])); ?>
				<?php echo $form->error($model,'ft_fio'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'ft_phone'); ?>
				<?php echo $form->textField($model,'ft_phone', array('placeholder'=>$labels['ft_phone'])); ?>
				<?php echo $form->error($model,'ft_phone'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'ft_city'); ?>
				<?php echo $form->textField($model,'ft_city', array('placeholder'=>$labels['ft_city'])); ?>
				<?php echo $form->error($model,'ft_city'); ?>
			</div>
		</div>
		<div class="block50">			
			<div class="row">
				<?php echo $form->labelEx($model,'ft_clienttype'); ?>
				<?php echo $form->dropDownList($model,'ft_clienttype', $options['ft_clienttype'], array('class'=>'customSelect', 'style'=>'width:273px;') ); ?>
				<?php echo $form->error($model,'ft_clienttype'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'ft_perriod'); ?>
				<?php echo $form->dropDownList($model,'ft_perriod', $options['ft_perriod'], array('class'=>'customSelect', 'style'=>'width:273px;') ); ?>
				<?php echo $form->error($model,'ft_perriod'); ?>
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
			<?php echo $form->labelEx($model,'ft_prodtype'); ?>
			<?php echo $form->textField($model,'ft_prodtype', array('placeholder'=>$labels['ft_prodtype'])); ?>
			<?php echo $form->error($model,'ft_prodtype'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'ft_proizvod'); ?>
			<?php echo $form->textField($model,'ft_proizvod', array('placeholder'=>$labels['ft_proizvod'])); ?>
			<?php echo $form->error($model,'ft_proizvod'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'ft_model'); ?>
			<?php echo $form->textField($model,'ft_model', array('placeholder'=>$labels['ft_model'])); ?>
			<?php echo $form->error($model,'ft_model'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'ft_state'); ?>
			<?php echo $form->radioButtonList($model,'ft_state', $options['ft_state']); ?>
			<?php echo $form->error($model,'ft_state'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'ft_foto'); ?>
			<?php echo $form->fileField($model,'ft_foto', array('class'=>'filefield')); ?>
			<?php
			if ( $model->ft_foto ) {
				echo '<img src="'.$model->ft_foto.'" alt="" style="max-width:40px; max-height:40px;"/><input type="hidden" name="FormsGold[ft_foto]" value="'.$model->ft_foto.'" />';
			}
			?>
			<?php echo $form->error($model,'ft_foto'); ?>
		</div>
	</div>
	<div class="block50">
		<div class="row">
			<?php echo $form->labelEx($model,'ft_commnets'); ?>
			<?php echo $form->textArea($model,'ft_commnets', array('style'=>'height:190px;')); ?>
			<?php echo $form->error($model,'ft_commnets'); ?>
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
