<div class="contactForm" id="contactForm">
	<div id="guestbook" class="popup2" style="margin-top:15px;">
		<div class="pupupContent">
			<strong><?php echo Yii::t('site', 'Guestbook h1 text');?></strong>
		</div>
	</div>
	<form id="guestbookform-send" class="form-horizontal" method="<?php echo $this->_method;?>" action="#contactForm" autocomplete="off" enctype="multipart/form-data">
	<?php
		foreach ( $fields as $name=>$fld )
			echo $this->buildField($name, $fld);
	?>	
		<div class="control-group">
			<div class="controls">
				<input class="btn span2" type="submit" name="yt0" value="<?php echo Yii::t('site', 'Send');?>">
				<?php echo $this->showErrors($this->_errors);?>
			</div>
		</div>
	</form>
</div>