<?php $this->widget('application.components.widgets.Session_reopen');?>
<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>
<?= $this->buildPersonalMenu($isMenuDisabled, $step);?>

<?php
if ( $blocking )
	echo $blocking;
?>

<div class="widget-content">
	<h2 class="h2-title"><?php echo Yii::t('site', 'List of bank cards') ?></h2>
	<table class="table">
		<thead>
		<tr>
			<td><?php echo Yii::t('site', 'Type') ?></td>
			<td><?php echo Yii::t('site', 'Card number') ?></td>
			<td><?php echo Yii::t('site', 'Expiration Date') ?></td>
			<td><?php echo Yii::t('site', 'Actions') ?></td>
		</tr>
		</thead>
		<tbody>
		<?php

		foreach ($cards as $card) {
			if ( $card['card_state'] < 0 ) {
				continue;
			}

			$actions = array();
			$act = array();
			if ( $card['card_state'] == '1' ) {
				$actions[1]['text'] = Yii::t('site', 'Set as main');
				$actions[1]['link'] = $this->createURL('/personalpage/cards/generalcard/'.$card['ac_id']);
			} else {
				$actions[1]['text'] = Yii::t('site', 'Activate');
				$actions[1]['link'] = $this->createURL('/personalpage/cards/verification/'.$card['ac_id'].'/');
			}

			if ( !$card['card_g'] ) {
				$actions[0]['text'] = Yii::t('site', 'Delete');
				$actions[0]['link'] = $this->createURL('/personalpage/cards/deletecard/'.$card['ac_id']);
				//$act = '<a href="'.$actions[0]['link'].'">'.$actions[0]['text'].'</a> / <a href="'.$actions[1]['link'].'">'.$actions[1]['text'].'</a>';
			} else {
				$actions[0]['text'] = Yii::t('site', '-');
				$actions[0]['link'] = '#';
			}

			foreach ( $actions as $action ) {
				$act[] = '<a href="'.$action['link'].'">'.$action['text'].'</a>';
			}
			?>
			<tr>
				<td><img src="<?php echo Yii::app()->request->hostInfo.'/pic/'.$card['card_type'].'.jpg'?>" alt="" /></td>
				<td><span class="card-number-title"><?php echo substr($card['card_number'], 0, 4).'-ХХХХ-ХХХХ-'.substr($card['card_number'], -4, 4) ?></span></td>
				<td><?php echo $card['card_valid'] ?></td>
				<td>
					<p><?=( $actions[0]['text']=='-') ? '-' : implode(' / ', $act) ; ?></p>
				</td>
			</tr>
		<?php }	?>


		</tbody>
		<tfoot>
		<tr>
			<td colspan="4"><a class="btn-add-new-cart" href="<?php echo $this->createURL('/personalpage/cards/addcard') ?>"><?php echo Yii::t('site', 'Add a new card') ?></a></td>
		</tr>
		</tfoot>
	</table>
</div>