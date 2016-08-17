<div class="control-group">
	<label class="control-label" for="<?php echo $this->_nameSpace.'_'.$name;?>"><?php echo Yii::t('site', $fld['label']);?>
	<?php if ($fld['required']) echo '*';?>
	:</label>
	<div class="controls">
		<?php
		$error = '';
		if (!empty($fld['error'])) $error = ' class="error"';
				
		echo '<select name="'.$this->_nameSpace.'['.$name.']" id="'.$this->_nameSpace.'_'.$name.'"'.$error.'>
			 ';
		if ( !empty($fld['options']) ) {
			$val = $fld['val'];
			
			foreach ( $fld['options'] as $opt_val=>$opt_text ) {
								
				echo '<option value="'.$opt_val.'"';
				if ( $opt_val == $val )
					echo ' selected="selected"';
				echo '>'.$opt_text.'</option>';
			}
		}
		echo '</select>';		
		echo $this->showErrors($fld['error']);
		?>		
	</div>
</div>
