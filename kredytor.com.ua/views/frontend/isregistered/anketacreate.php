<?php $this->widget('application.components.widgets.Session_reopen');?>
<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::t('site', 'register').' :: '.Yii::app()->name;
?>
<div class="center-box">
<?= $this->buildPersonalMenu($isMenuDisabled, $step);?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	'htmlOptions'	=> array(
		'class'=>'form-box',
	),
	'errorMessageCssClass' => 'tooltip',
)); 
$labels		= $model_anketa->attributeLabels();
$selects	= $model_anketa->selectValues();

echo $form->hiddenField($model_anketa, 'srok');
echo $form->hiddenField($model_anketa, 'credit_for');
echo $form->hiddenField($model_anketa, 'credit_summ');
echo '<input type="hidden" value="'.$step.'" name="currStep"/>';

for ( $i=2; $i<=4; $i++ ) {
		echo $this->buildRegisterForm($form, $isMenuDisabled, $i, $model_anketa, $labels, $selects);
}
?>
<?php $this->endWidget(); ?>
</div>