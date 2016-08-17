<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::t('site', 'Personal page').'::'.Yii::app()->name;
?>

<div class="form register">
	<h1><?php echo Yii::t('site', 'Personal page');?></h1>
	<div class="form_body">
	<?php 
	if ( $try_save )
		echo '<h2 class="success">'.Yii::t('site', 'Personal data changed').'!</h2><br/><br/>';
		
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'register-form',
		'enableClientValidation'=>false,
	)); 
	$labels = $model->attributeLabels();
	?>

		<div class="row">
			<?php echo $form->labelEx($model,'u_fio'); ?>
			<?php echo $form->textField($model,'u_fio', array('placeholder'=>$labels['u_fio'])); ?>
			<?php echo $form->error($model,'u_fio'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'u_phone'); ?>
			<?php echo $form->textField($model,'u_phone'); ?>
			<?php echo $form->error($model,'u_phone'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'u_email'); ?>
			<?php echo $form->textField($model,'u_email', array('placeholder'=>$labels['u_email'])); ?>
			<?php echo $form->error($model,'u_email'); ?>
		</div>
		
		<div class="row change_pass">
			<p><?php echo Yii::t('site', 'Changing password');?>:</p>
			<?php echo $form->labelEx($model,'u_pass'); ?>
			<?php echo $form->passwordField($model,'u_pass', array('autocomplete'=>'off')); ?>
			<?php echo $form->error($model,'u_pass'); ?>
			
			<?php echo $form->labelEx($model,'u_pass_confirm'); ?>
			<?php echo $form->passwordField($model,'u_pass_confirm'); ?>
			<?php echo $form->error($model,'u_pass_confirm'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'u_pass_current'); ?>
			<?php echo $form->passwordField($model,'u_pass_current'); ?>
			<?php echo $form->error($model,'u_pass_current'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('site', 'Change')); ?>
		</div>
		
		<?php echo $form->errorSummary( $model ); ?>

	<?php $this->endWidget(); ?>
	</div>
</div><!-- form -->

<?php if ( !empty($orders) ) { ?>
<div class="userOrders">
	<h2 class="h1"><?php echo Yii::t('site', 'Orders');?>:</h2>
	<table class="basket">
		 <thead>
			 <tr>
				 <th><?php echo Yii::t('site', 'Order');?></th>
				 <th><?php echo Yii::t('main', 'Order date');?></th>
				 <th><?php echo Yii::t('main', 'Order state');?></th>
				 <th><?php echo Yii::t('site', 'total');?></th>
			 </tr>
		 </thead>
		 <tbody>
			<?php
			$states = Orders::model()->selectValues();
			
			foreach ( $orders as $order ) {
				$state = '';
				if ( !empty($states['o_state']) && !empty($states['o_state'][1][$order['o_state']]) ) {
					$state = $states['o_state'][1][$order['o_state']];
				}
				echo '
				<tr>
					<td><a href="/'.Yii::app()->language.'/catalog/orderview/'.$order['o_id'].'/" alt="">'.Yii::t('site', 'Order').' № '.$order['o_id'].'</a></td>
					<td>'.$order['o_date'].'</td>
					<td>'.$state.'</td>
					<td class="price">'.number_format($order['total'], 0, '.', ' ').' <span>&euro;</span></td>
				</tr>';
			}
			?>
		 </tbody>
	 </table>
</div>
<?php } ?>