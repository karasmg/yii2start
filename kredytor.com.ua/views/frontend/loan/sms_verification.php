<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>
<?= $this->buildPersonalMenu($isMenuDisabled, $step);?>



<div class="content-page">
<?php
$form = $this->beginWidget ( 'CActiveForm', array ('id' => 'sms_verification-form', 'enableClientValidation' => false, 'htmlOptions' => array ('class' => 'form-box change-password' ), 'errorMessageCssClass' => 'tooltip' ) );

?>
	<div class="center-box">
		<div class="widget-content widget-landing other-landing">
			<h2 class="h2-title"><?php echo Yii::t('site', 'Phone verification') ?>.</h2>
			<!-- end info-row -->
			<div id="new-phone" class="inp-block verification">
					<?php
					$fieldName = 'new_phone';
					$error = $form->error ( $modelAnketa, $fieldName );
					?>
					<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
					<?php
					echo Yii::t ( 'site', 'Enter Phone number' ) . ':';
					echo $form->textField ( $modelAnketa, $fieldName, array ('class' => 'inp-text' ) );
					echo $error;
					?>
				</div>
			</div>
			<div class="inp-block verification sms-code">
				<p><?php echo Yii::t('site', 'We are sent SMS') ?> <span
						style="font-weight: bold;"><?php echo $modelAnketa->contact_phone_mobile ?></span>, <?php echo Yii::t('site', 'that you specify in the anketa') ?>. <a
						id="change-phone" href="javascript:void(0)"><?php echo Yii::t('site', 'Specify other number') ?>.</a>
				</p>
				<br>			
					<?php
					$fieldName = 'smsVerifyCode';
					$error = $form->error ( $modelUser, $fieldName );
					?>
					<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
					<?php
					echo Yii::t ( 'site', 'Enter SMS:' );
					echo $form->textField ( $modelUser, $fieldName, array ('class' => 'inp-text' ) );
					echo $error;
					?>
				</div>
			</div>
			<br>
			<div class="align-center">
				<button class="btn" formaction="<?php echo '/' . Yii::app ()->language . '/personalpage/loan/smsverification'?>" type="submit"><?php echo Yii::t('site', 'Continue') ?></button>
				<br>
				<br>
				<p class="sms-code"><?php echo Yii::t('site', 'SMS does not come') ?>? <input
						type="submit"
						formaction="<?php echo '/' . Yii::app ()->language . '/personalpage/loan/smsverification?resend=1'?>"
						value="<?php echo Yii::t('site', 'Resend SMS') ?>."></input>
				</p>
			</div>
		</div>
		<!-- end center-box -->
	</div>
	<!-- end content -->
	<?php $this->endWidget(); ?>
</div>
