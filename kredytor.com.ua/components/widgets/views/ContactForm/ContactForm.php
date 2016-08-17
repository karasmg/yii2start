<div class="form-box">
	<h2 class="h2-title"><?php echo $form_title;?></h2>
	<form id="contactform-send" class="form-horizontal" method="<?php echo $this->_method;?>" action="#contactForm" autocomplete="off" enctype="multipart/form-data">
		<?php
		if ( !empty($_GET['result']) ) echo '<p class="message">'.Yii::t('site', 'ThanksMsgHead').'</p>';	
		foreach ( $fields as $name=>$fld )
			echo $this->buildField($name, $fld);
		?>
		<div class="inp-block">
			<div class="inp-box align-center">
				<button class="btn" type="submit"><?php echo Yii::t('site', 'Send');?></button>
			</div>
		</div>
	</form>
</div>