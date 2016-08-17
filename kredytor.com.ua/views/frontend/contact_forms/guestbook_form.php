<?php
		echo '<h3>'.$subject.'</h3>'; 		
		foreach ( $fields as $fld ) {
			if ( in_array($fld['type'], array('capcha', 'hidden_capcha')) ) continue;
			$label = $fld['label'];
			if ( !empty($fld['mail_label']) )
				$label = $fld['mail_label'];
			echo '<b>'.$label.'</b>: ';
			echo '<br />';
			echo $fld['val'];
			echo '<br /><br />';
		}
?>