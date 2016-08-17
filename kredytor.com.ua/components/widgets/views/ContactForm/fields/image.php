<div class="inp-block">
	<label><?php echo Yii::t('site', $fld['label']);?><?php if ($fld['required']) echo '*';?>:</label>
	<div class="inp-box<?php if ( !empty($fld['error']) ) echo ' error-field';?>">
		<?php 
		for ( $i=0; $i<$fld['count']; $i++ ) { 
			$value_this = '';
			if ( !empty($fld['vals'][$i]) )
				$value_this = $fld['vals'][$i];
			if ( $value_this ) echo '<img src = "'.$value_this.'" class="preview"/>';
		?>
			<input type="file" name="<?php echo $this->_nameSpace.'['.$name.']['.$i.']';?>" id="<?php echo $this->_nameSpace.'_'.$name.'_'.$i;?>"<?php if (!empty($fld['error'])) echo ' class="error"';?> />
			<input type="hidden" name="<?php echo $this->_nameSpace.'['.$name.'_hidden]['.$i.']';?>" id="<?php echo $this->_nameSpace.'_'.$name.'_hidden_'.$i;?>" value="<?php echo $value_this;?>" />
		<?php } ?>
		
		<?php echo $this->showErrors($fld['error']);?>
	</div>
</div>