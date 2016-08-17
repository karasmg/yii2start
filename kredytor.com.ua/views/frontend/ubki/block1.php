<?php
$mainoutput = array();
if ( !empty($blocks[1]['cki']) ) {
	$datas = $this->xml2array($blocks[1]['cki']['ident']);
	$history = array();
	$labels = array(
		'fio'		=> 'ФИО',
		'inn'		=> 'ИНН',
		'bdate'		=> 'Дата рождения',
		'csex'		=> 'Пол',
		'family'	=> 'Семейное положение',
		'pass_sn'	=> 'Серия и номер паспорта',
		'dwdt'		=> 'Дата выдачи паспорта',
		'found'		=> 'Признак наличия паспорта в базе МВД',
		'addr_reg'	=> 'Адрес регистрации',
		'addr_live'	=> 'Адрес проживания',
		'wokpo'		=> 'ЕГРПОУ места работы',
	);
	foreach ( $datas as $data ) {
		$vals = array(
			'fio'		=> $data["@attributes"]["lname"].' '.$data["@attributes"]["fname"].' '.$data["@attributes"]["mname"],
			'inn'		=> $data["@attributes"]["inn"],
			'bdate'		=> $data["@attributes"]["bdate"],
			'csex'		=> $data["@attributes"]["csexref"],
			'family'	=> $data["@attributes"]["familyref"],
		);

		$history['fio'][] = array(
			'date'	=> $data["@attributes"]["vdate"],
			'value'	=> $data["@attributes"]["lname"].' '.$data["@attributes"]["fname"].' '.$data["@attributes"]["mname"],
		);

		foreach( $vals as $key=>$val ) {
			if ( empty($mainoutput[$key]) )
			$mainoutput[$key] = $val;
		}
	}

	$datas = $this->xml2array($blocks[1]['cki']['doc']);
	if ( count($datas) == 1 )
		$datas = array($datas);
	foreach ( $datas as $data ) {
		$vals = array(
			'pass_sn'	=> $data["@attributes"]["dser"].' '.$data["@attributes"]["dnom"],
			'dwdt'		=> $data["@attributes"]["dwdt"],
		);

		$history['doc'][] = array(
			'date'		=> $data["@attributes"]["vdate"],
			'pass_sn'	=> $data["@attributes"]["dser"].' '.$data["@attributes"]["dnom"],
			'dwdt'		=> $data["@attributes"]["dwdt"],
			'dwho'		=> $data["@attributes"]["dwho"],
		);

		foreach( $vals as $key=>$val ) {
			if ( empty($mainoutput[$key]) )
			$mainoutput[$key] = $val;
		}
	}
}
if ( isset($blocks[5][0]) ) {
	$datas = $this->xml2array($blocks[5][0]);
	$mainoutput['found'] = $this->convertSprav('found', $datas["@attributes"]["found"]);
}
if ( !empty($blocks[1]['cki']) ) {
	$datas = $this->xml2array($blocks[1]['cki']['addr']);
	foreach ( $datas as $data ) {
		$addr_index = '';
		if ( $data["@attributes"]["adtype"] == 1 )
			$addr_index = 'addr_live';
		elseif ( $data["@attributes"]["adtype"] == 2 )
			$addr_index = 'addr_reg';

		$vals = array(
			'adcountry'	=> $data["@attributes"]["adcountry"],
			'adindex'	=> $data["@attributes"]["adindex"],
			'adstate'	=> $data["@attributes"]["adstate"],
			'adarea'	=> $data["@attributes"]["adarea"],
			'adcity'	=> $data["@attributes"]["adcity"],
			'adstreet'	=> $data["@attributes"]["adstreet"],
			'adhome'	=> $data["@attributes"]["adhome"],
			'adcorp'	=> $data["@attributes"]["adcorp"],
			'adflat'	=> $data["@attributes"]["adflat"],
		);

		$history['addr'][$data["@attributes"]["adtype"]][] = array(
			'date'		=> $data["@attributes"]["vdate"],
			'value'		=> implode(' ', $vals),
		);

		foreach( $vals as $key=>$val ) {
			if ( empty($mainoutput[$addr_index][$key]) )
			$mainoutput[$addr_index][$key] = $val;
		}
	}

	$datas = $this->xml2array($blocks[1]['cki']['work']);
	foreach( $datas as $data ) {
		if ( !empty($data["@attributes"]["wokpo"]) ) {
			$mainoutput['wokpo'] = $data["@attributes"]["wokpo"];
			break;
		}
	}
}
?>
<h2>Персональные данные</h2>
<?php
if ( !empty($blocks[1]['cki']) && !empty($blocks[1]['cki']['foto']) ) {
	$foto = ( $this->xml2array($blocks[1]['cki']['foto']));
	if ( !empty($foto) && !empty($foto["@attributes"]["foto"]) ) {
		$path = $_SERVER['DOCUMENT_ROOT'].'/images/ubki/'.$foto["@attributes"]["inn"].'.jpg';
		if ( !file_exists($path) ) {
			//$this->dodump($path);
			$ifp = fopen($path, "wb"); 
			fwrite($ifp, base64_decode($foto["@attributes"]["foto"])); 
			fclose($ifp); 
		}
		echo '<b>Фото от '.$foto["@attributes"]["vdate"].'</b><br/><img src="/images/ubki/'.$foto["@attributes"]["inn"].'.jpg"/>';
	}
}
?>
<table>
	<?php
	if ( !empty($labels) ) {
		foreach ( $labels as $label_key=>$label_title ) {
			if ( empty($mainoutput[$label_key]) )
				continue;
			$val = $mainoutput[$label_key];
			if ( is_array($val) )
				$val = implode(' ', $val);
			echo '
				<tr>
					<th>'.$label_title.'</th>
					<td>'.$val.'</td>
				</tr>';
		}
	}
	?>
</table>
<h2>Контакты</h2>
<table>
	<tr>
		<th class="header">Дата обновления</th>
		<th class="header">Тип контакта</th>
		<th class="header">Значение</th>
	</tr>
	<?php
	if ( !empty($blocks[10]['cont']) ) {
		$datas = $this->xml2array($blocks[10]['cont']);
		foreach( $datas as $data ) {
			if ( empty($data["cval"]) && !empty($data["@attributes"]) )
				$data = $data["@attributes"];
			echo '
				<tr>
					<td>'.$data["vdate"].'</td>
					<td>'.$data["ctyperef"].'</td>
					<td>'.$data["cval"].'</td>
				</tr>';
		}
	}
	?>
</table>

<h2>История изменения данных</h2>
<div class="rollower">
	<a class="control" href="#">Отобразить/скрыть</a><br><br>
	<div class="content hide">
		<table>
			<tr>
				<th class="header">Дата обновления</th>
				<th class="header">ФИО</th>
			</tr>
			<?php
			if ( !empty($history['fio']) ) {
				foreach( $history['fio'] as $data ) {
					echo '
						<tr>
							<td>'.$data["date"].'</td>
							<td>'.$data["value"].'</td>
						</tr>';
				}
			}
			?>
		</table><br><br>
		<table>
			<tr>
				<th class="header">Дата обновления</th>
				<th class="header">Паспорт (Серия, номер)</th>
				<th class="header">Дата выдачи</th>
				<th class="header">Орган выдачи</th>
			</tr>
			<?php
			if ( !empty($history['doc']) ) {
				foreach( $history['doc'] as $data ) {
					echo '
						<tr>
							<td>'.$data["date"].'</td>
							<td>'.$data["pass_sn"].'</td>
							<td>'.$data["dwdt"].'</td>
							<td>'.$data["dwho"].'</td>
						</tr>';
				}
			}
			?>
		</table>
		<?php
		if ( !empty($history['addr']) ) {
			foreach ( $history['addr'] as $type=>$vals ) {?>
			<br><br>
			<table>
				<tr>
					<th class="header">Дата обновления</th>
					<th class="header"><?php echo $this->convertSprav('adtype', $type); ?></th>
				</tr>
				<?php
				foreach( $vals as $data ) {
					echo '
						<tr>
							<td>'.$data["date"].'</td>
							<td>'.$data["value"].'</td>
						</tr>';
				}
				?>
			</table>
		<?php 
			}
		}
		?>
	</div>
</div>
<br/><br/><br/>