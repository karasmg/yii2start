<form class="form-box ajax_form" method="<?php echo $this->_method;?>" action="" autocomplete="off" enctype="multipart/form-data">
<?php
	if ( !empty($result) ) {
		echo '<p class="message">'.Yii::t('site', 'ThanksMsgHead').'</p>';	
	} else {
		foreach ( $fields as $name=>$fld )
			echo $this->buildField($name, $fld);
?>	
	 <div class="inp-block">
		 <button type="submit" class="btn"><?php echo Yii::t('site', 'Send');?></button>
     </div>
	<?php } ?>
</form>