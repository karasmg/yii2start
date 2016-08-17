<?php
$days = array('-'=>'---');
for ( $i=1; $i<=31; $i++ ) {
	$key = $i;
	if ( $i<10 )
		$key = '0'.$i;
	$days[$key] = $i;
}

$month = array(
	'-'		=> '---',
	'01'	=> Yii::t('dates', 'January_sm'),
	'02'	=> Yii::t('dates', 'February_sm'),
	'03'	=> Yii::t('dates', 'March_sm'),
	'04'	=> Yii::t('dates', 'April_sm'),
	'05'	=> Yii::t('dates', 'May_sm'),
	'06'	=> Yii::t('dates', 'June_sm'),
	'07'	=> Yii::t('dates', 'July_sm'),
	'08'	=> Yii::t('dates', 'August_sm'),
	'09'	=> Yii::t('dates', 'September_sm'),
	'10'	=> Yii::t('dates', 'October_sm'),
	'11'	=> Yii::t('dates', 'November_sm'),
	'12'	=> Yii::t('dates', 'December_sm'),	
);

$years = array('-'=>'---');
for ( $i=2000; $i>=1920; $i-- )
	$years[$i] = $i;

?>



<div class="control-group">
	<label class="control-label" for="<?php echo $this->_nameSpace.'_'.$name.'_0';?>"><?php echo Yii::t('site', $fld['label']);?>
	<?php if ($fld['required']) echo '*';?>
	:</label>
	<div class="controls birth_date">
		<select name="<?php echo $this->_nameSpace.'['.$name.'][0]';?>" id="<?php echo $this->_nameSpace.'_'.$name.'_0';?>"<?php if (!empty($fld['error'])) echo ' class="error"';?>>
			<?php
			foreach ( $days as $opt_val=>$opt_text ) {
				$selected='';
				if ( $fld['vals'][0] == $opt_val )
					$selected=' selected="selected"';
				echo '<option value="'.$opt_val.'"'.$selected.'>'.$opt_text.'</option>';
			}
			?>
		</select>
		
		<select name="<?php echo $this->_nameSpace.'['.$name.'][1]';?>" id="<?php echo $this->_nameSpace.'_'.$name.'_1';?>"<?php if (!empty($fld['error'])) echo ' class="error"';?>>
			<?php
			foreach ( $month as $opt_val=>$opt_text ) {
				$selected='';
				if ( $fld['vals'][1] == $opt_val )
					$selected=' selected="selected"';
				echo '<option value="'.$opt_val.'"'.$selected.'>'.$opt_text.'</option>';
			}
			?>
		</select>
		
		<select name="<?php echo $this->_nameSpace.'['.$name.'][2]';?>" id="<?php echo $this->_nameSpace.'_'.$name.'_2';?>"<?php if (!empty($fld['error'])) echo ' class="error"';?>>
			<?php
			foreach ( $years as $opt_val=>$opt_text ) {
				$selected='';
				if ( $fld['vals'][2] == $opt_val )
					$selected=' selected="selected"';
				echo '<option value="'.$opt_val.'"'.$selected.'>'.$opt_text.'</option>';
			}
			?>
		</select>
		<?php
		if ( !empty($fld['additionalinfo']) )
			echo '<div class="additionalinfo">'.$fld['additionalinfo'].'</div>';
		echo $this->showErrors($fld['error']);
		?>
	</div>
</div>
