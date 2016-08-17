<div class="control-group">
	<label class="control-label" for="<?php echo $this->_nameSpace.'_'.$name;?>"><?php echo Yii::t('site', $fld['label']);?>
	<?php if ($fld['required']) echo '*';?>
	:</label>
	<div class="controls">
		<?php
		$error = '';
		if (!empty($fld['error'])) $error = ' class="error"';
		$val2=0;
		if ( !empty($fld['selectvals'][1]) )
			$val2 = $fld['selectvals'][1];
		
		echo '<select name="'.$this->_nameSpace.'['.$fld['selectname'][0].']" id="'.$this->_nameSpace.'_'.$fld['selectname'][0].'"'.$error.' onChange="doubleSelectChange(this, '.$this->_nameSpace.'_'.$fld['selectname'][0].'_suboptions, \''.$this->_nameSpace.'_'.$fld['selectname'][1].'\', \''.$val2.'\', \''.YII::t('main', $fld['selectname'][1]).'\')">
				<option value="0">-'.YII::t('main', $fld['selectname'][0]).'-</option>
			 ';
		if ( !empty($fld['options'][0]) ) {
			$val = false;
			$script = '
				<script type="text/javascript" language="JavaScript">
					var '.$this->_nameSpace.'_'.$fld['selectname'][0].'_suboptions=new Array();
					'.$this->_nameSpace.'_'.$fld['selectname'][0].'_suboptions[0]=new Array();';
			if ( !empty($fld['selectvals'][0]) )
				$val = $fld['selectvals'][0];
			
			foreach ( $fld['options'][0] as $opt_val=>$opt_text ) {
				$script.= '
					'.$this->_nameSpace.'_'.$fld['selectname'][0].'_suboptions["'.$opt_val.'"]=new Array();';				
				
				echo '<option value="'.$opt_val.'"';
				if ( $opt_val == $val )
					echo ' selected="selected"';
				echo '>'.$opt_text.'</option>';
			}
		}
		echo '</select>';
		
		echo '<select name="'.$this->_nameSpace.'['.$fld['selectname'][1].']" id="'.$this->_nameSpace.'_'.$fld['selectname'][1].'"'.$error.'>
				<option value="0">-'.YII::t('main', $fld['selectname'][1]).'-</option>
			 ';
		if ( !empty($fld['options'][1]) ) {
			$val = false;
			if ( !empty($fld['selectvals'][1]) )
				$val = $fld['selectvals'][1];
			
			foreach ( $fld['options'][1] as $opt_val=>$params ) {
				$script.= '
					var option=new Array();
					option["val"]=\''.$opt_val.'\';
					option["txt"]=\''.$params[0].'\';
					'.$this->_nameSpace.'_'.$fld['selectname'][0].'_suboptions["'.$params[1].'"].push(option);';				
			}
		}
		echo '</select>';
		
		echo $this->showErrors($fld['error']);
		$script.= '
				$(window).load(function(){
					doubleSelectChange(document.getElementById(\''.$this->_nameSpace.'_'.$fld['selectname'][0].'\'), '.$this->_nameSpace.'_'.$fld['selectname'][0].'_suboptions, \''.$this->_nameSpace.'_'.$fld['selectname'][1].'\', \''.$val2.'\', \''.YII::t('main', $fld['selectname'][1]).'\');
				});
				</script>';
		echo $script;
		?>		
	</div>
</div>
