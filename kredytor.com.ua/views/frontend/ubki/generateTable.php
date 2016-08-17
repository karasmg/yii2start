<?php
$hystory_total = count($history);
?>
<table>
	<tr>
		<td> <a href="#" class="show_hystory">показать/скрыть историю</a> </td>
		<th class="header">Основные данные</th>
		<?php
		foreach ( $history as $hys ) {
			echo '
					<th class="hide header togglehide">Данные за '.$hys['history'].'</th>';
		}
		?>
	</tr>
	<?php
	foreach ( $labels as $label_key=>$label_title ) {
		echo '
			<tr>
				<th>'.$label_title.'</th>
				<td>'.$mainoutput[$label_key].'</td>';
		for ( $i=0; $i<$hystory_total; $i++ ) {
			echo '
				 <td class="hide togglehide">'.$history[$i][$label_key].'</td>';
		}
		echo '
			</tr>';
	}
	?>
</table>