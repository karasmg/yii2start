<div class="comment_from">
	<h2><?php echo Yii::t('main', $h1);?></h2><br />
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'data-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>false,
		),
		'htmlOptions'=>array(
			'class'=>'form-horizontal',
			'enctype'=>'multipart/form-data',
			'autocomplete'=>'off',
		),

	)); 
	?>
	<fieldset>
		<?php
		echo $this->build_fileds_from_model( $model, $form, 0, $used_fields );
		echo $this->build_fileds_from_model( $lang_form, $form, 0, $used_fields );
		?>
	
		<div class="control-group">
			<div class="controls">
				<?php echo CHtml::submitButton(Yii::t('main', 'Send'), array('class'=>'btn span2', 'name'=>'user_comment') ); ?>
			</div>
		</div>
		<?php
			if ( $form_errors ) echo '<pre>'.Yii::t('main', $form_errors).'</pre>';
		?>
	</fieldset>
	<?php $this->endWidget(); ?>
</div>