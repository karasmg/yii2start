<div class="control-group">
	<label class="control-label" for="<?php echo $this->_nameSpace.'_'.$name;?>"><?php echo Yii::t('site', $fld['label']);?>
	<?php if ($fld['required']) echo '*';?>
	:</label>
	<div class="controls">
		<strong>+380</strong><input type="text" value="<?php echo htmlspecialchars($fld['val']);?>" name="<?php echo $this->_nameSpace.'['.$name.']';?>" id="<?php echo $this->_nameSpace.'_'.$name;?>"<?php if (!empty($fld['error'])) echo ' class="error"';?> style="width:200px; margin-left:10px;" maxlength="9" />
		<?php
		if ( !empty($fld['additionalinfo']) )
			echo '<div class="additionalinfo">'.$fld['additionalinfo'].'</div>';
		echo $this->showErrors($fld['error']);
		?>
	</div>
</div>
