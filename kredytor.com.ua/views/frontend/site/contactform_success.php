
<?php if ( !empty($name) && !empty($phone) && !empty($email) && !empty($text) ) {?>
	<h1><?php echo Yii::t('site', 'ThanksMsgHead');?></h1>
	<p><?php echo Yii::t('site', 'Registration intro');?>:</p>
	<p>
		<?php echo Yii::t('site', 'name').': '.$name;?><br/>
		<?php echo Yii::t('site', 'Phone number').': '.$phone;?><br/>
		<?php echo Yii::t('site', 'email').': '.$email;?><br/>
		<?php echo '<b>'.Yii::t('site', 'Text').'</b>:<br/>'.$text;?>
	</p>
	<p><?php echo Yii::t('site', 'ThanksMsg');?>.</p>
<?php } else { ?>
	<div class="form register">
	<div class="form_body">
		<h3><?php echo Yii::t('site', 'Contact form');?></h3>
		<p><?php echo Yii::t('site', 'ThanksMsgHead');?>.</p>
		<p><?php echo Yii::t('site', 'ThanksMsg');?>.</p>
	</div>
	</div><!-- form -->
<?php } ?>