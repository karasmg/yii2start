<?php
		echo '<h3>'.$subject.'</h3>'; 		
		foreach ( $fields as $fld ) {
			if ( in_array($fld['type'], array('capcha', 'hidden_capcha')) ) continue;
			echo '<b>'.$fld['label'].'</b>: ';
			if ( $fld['type'] == '' ) 
				echo '<br />';
			echo $fld['val'];
			echo '<br /><br />';
		}
?>