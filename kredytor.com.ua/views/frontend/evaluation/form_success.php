
<?php if ( !empty($name) && !empty($phone) && !empty($city) && !empty($h1) ) {?>
	<h1><?php echo Yii::t('site', $h1);?></h1>
	<p>
		<?php echo Yii::t('site', 'name').': '.$name;?><br/>
		<?php echo Yii::t('site', 'Phone number').': '.$phone;?><br/>
		<?php echo Yii::t('site', 'city').': '.$city;?><br/>
	</p>
<?php } else { ?>
	<div class="form register">
	<div class="form_body">
		<h3><?php echo Yii::t('site', 'ThanksMsgHead');?>.</h3>
		<p><?php echo Yii::t('site', 'ThanksMsg');?>.</p>
	</div>
	</div><!-- form -->
<?php } ?>