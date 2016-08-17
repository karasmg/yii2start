<?php
$datas = $this->xml2array($blocks[12]['afs']);
?>
<h2>Количество запросов в УБКИ</h2>
<table>
	<tr>
		<th class="header">За час</th>
		<th class="header">За день</th>
		<th class="header">За неделю</th>
		<th class="header">За месяц</th>
		<th class="header">За квартал</th>
		<th class="header">За год</th>
	</tr>
	<tr>
		<td align="center"><?php echo $datas["phone"]["@attributes"]["hr"];?></td>
		<td align="center"><?php echo $datas["phone"]["@attributes"]["da"];?></td>
		<td align="center"><?php echo $datas["phone"]["@attributes"]["wk"];?></td>
		<td align="center"><?php echo $datas["phone"]["@attributes"]["mn"];?></td>
		<td align="center"><?php echo $datas["phone"]["@attributes"]["qw"];?></td>
		<td align="center"><?php echo $datas["phone"]["@attributes"]["ye"];?></td>
	</tr>
</table>
<br/><br/><br/>
	
		
		
		
		
		
		