<h1><?php echo Yii::t('site', 'Registration success');?></h1>
<p><?php echo Yii::t('site', 'Hello').' '.$name;?></p>
<?php if ( !empty($login) && !empty($pass) ) {?>
	<p><?php echo Yii::t('site', 'Registration intro');?>:</p>
	<p>
		<?php echo Yii::t('main', 'Login ( email )').': '.$login;?><br/>
		<?php echo Yii::t('main', 'Password').': '.$pass;?>
	</p>
<?php } else { ?>
	<p><?php echo Yii::t('site', 'Registration success text');?>.</p>
<?php }?>
<script id="return_script">
	//setTimeout('location.reload();', 1500);
</script>	