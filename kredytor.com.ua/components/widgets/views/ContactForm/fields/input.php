<div class="inp-block">
	<label><?php echo Yii::t('site', $fld['label']);?><?php if ($fld['required']) echo '*';?>:</label>
	<div class="inp-box<?php if ( !empty($fld['error']) ) echo ' error-field';?>">
		<input type="text" class="inp-text" value="<?php echo htmlspecialchars($fld['val']);?>" name="<?php echo $this->_nameSpace.'['.$name.']';?>" id="<?php echo $this->_nameSpace.'_'.$name;?>" />
		<?php
		if ( !empty($fld['additionalinfo']) )
			echo '<div class="additionalinfo">'.$fld['additionalinfo'].'</div>';
		echo $this->showErrors($fld['error']);
		?>
	</div>
</div>