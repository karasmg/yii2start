<div class="control-group">
	<label class="control-label" for="<?php echo $this->_nameSpace.'_'.$name;?>"><?php echo Yii::t('site', $fld['label']);?>
	<?php if ($fld['required']) echo '*';?>
	:</label>
	<div class="controls">
		<?php
		$error = '';
		if ( !empty($fld['options']) ) {
			$val = $fld['val'];
			
			foreach ( $fld['options'] as $opt_val=>$opt_text ) {
				echo '<input type="radio" value="'.$opt_val.'" name="'.$this->_nameSpace.'['.$name.']"';
				if ( $opt_val == $val )
					echo ' checked="checked"';
				echo '><strong>'.$opt_text.'</strong>';
			}
		}
		echo $this->showErrors($fld['error']);
		?>		
	</div>
</div>
