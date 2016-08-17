<div class="control-group">
	<label class="control-label" for="<?php echo $this->_nameSpace.'_'.$name;?>"><?php echo Yii::t('site', $fld['label']);?>
	<?php if ($fld['required']) echo '*';?>
	:</label>
	<div class="controls">
		<?php
		$error = '';
		if ( !empty($fld['options']) ) {
			$val = $fld['vals'];
			$i = 0;
			foreach ( $fld['options'] as $opt_val=>$opt_text ) {
				$i++;
				echo '<input id="'.$this->_nameSpace.'_'.$name.'_'.$i.'" type="checkbox" value="'.$opt_val.'" name="'.$this->_nameSpace.'['.$name.'][]"';
				if ( in_array($opt_val, $val) )
					echo ' checked="checked"';
				echo '><label class="control-label-checkbox" for="'.$this->_nameSpace.'_'.$name.'_'.$i.'">'.$opt_text.'</label><br/>';
			}
		}
		if ( !empty($fld['additionalinfo']) )
			echo '<div class="additionalinfo">'.$fld['additionalinfo'].'</div>';
		echo $this->showErrors($fld['error']);
		?>		
	</div>
</div>
