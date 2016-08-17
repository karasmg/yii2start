<?php $this->widget('application.components.widgets.Session_reopen');?>
<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>
<?= $this->buildPersonalMenu($isMenuDisabled, $step);?>

<?php
if ( $blocking )
	echo $blocking;
?>

<div class="content-page">
<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'id' => 'verification-form',
		'enableClientValidation' => false,
		'htmlOptions' => array (
				'class' => 'form-box change-password' 
		),
		'errorMessageCssClass' => 'tooltip' 
) );
$labels = $model->attributeLabels ();
// var_dump ( $model->errors );
?>
	<div class="center-box">
		<div class="widget-content widget-landing other-landing">
			<h2 class="h2-title"><?php echo Yii::t('site', 'You can find out') ?></h2>
			<div class="info-row">
				<div class="info-box">
					<figure class="pix-box">
						<img src="/pic/icon-12.png" alt="#" />
					</figure>
					<h3 class="h3-title"><?php echo Yii::t('site', 'Through the SMS banking') ?></h3>
					<p><?php echo Yii::t('site', 'Call the bank employee') ?></p>
				</div>
				<div class="info-box">
					<figure class="pix-box">
						<img src="/pic/icon-13.png" alt="#" />
					</figure>
					<h3 class="h3-title"><?php echo Yii::t('site', 'INTERNET BANKING') ?></h3>
					<p><?php echo Yii::t('site', 'Call the bank employee') ?></p>
				</div>
				<div class="info-box">
					<figure class="pix-box">
						<img src="/pic/icon-14.png" alt="#" />
					</figure>
					<h3 class="h3-title"><?php echo Yii::t('site', 'The call center of its BANK') ?></h3>
					<p><?php echo Yii::t('site', 'Call the bank employee') ?></p>
				</div>
			</div>
			<!-- end info-row -->
			<?php if($model->card_attempts>0){ ?>
			<div class="inp-block verification">
					<?php
					$fieldName = 'card_sum_verification';
					$error = $form->error ( $model, $fieldName );
					?>
					<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
					<?php
					echo Yii::t ( 'site', 'Blocked amount:' );
					echo $form->textField ( $model, $fieldName, array (
							'class' => 'inp-text' 
					) );
					echo $error;
					?>
				</div>
			</div>
			<div class="align-center">
				<button class="btn" type="submit"><?php echo Yii::t('site', 'Continue') ?></button>
			</div>
				<?php } else { ?>
				<div class="align-center">
				<p class="error-message"><?php echo Yii::t('site', 'Cannot verify this card any more') ?></p>
				<a class="btn-add-new-cart" href="<?php echo $this->createURL(YII::app()->language.'/personalpage/cards/addcard') ?>"><?php echo Yii::t('site', 'Add a new card') ?></a>
				</div>
				<?php } ?>
		</div>
		<!-- end center-box -->
	</div>
	<!-- end content -->
	<?php $this->endWidget(); ?>
</div>
