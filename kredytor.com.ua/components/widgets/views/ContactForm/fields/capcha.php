<div class="control-group capcha_image">
	<label class="control-label" for="<?php echo $this->_nameSpace.'_'.$name;?>">
	<?php echo Yii::t('site', $fld['label']);?>
	<?php if ($fld['required']) echo '*';?>
	:
	<img src="/capcha_image/image.php" alt="<?php echo Yii::t('site', $fld['label']);?>" />
	</label>
	<div class="controls">
		<input type="text" value="<?php echo htmlspecialchars($fld['val']);?>" name="<?php echo $this->_nameSpace.'['.$name.']';?>" id="<?php echo $this->_nameSpace.'_'.$name;?>"<?php if (!empty($fld['error'])) echo ' class="error"';?> />
		<?php echo $this->showErrors($fld['error']);?>
	</div>
</div>
