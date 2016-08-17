<?php if ( empty($_GET['ajax_request']) ) { ?>
<a href="" class="review <?=Yii::app()->language;?> btn-leave-review"></a>

<div class="popup-box reviewpop animated">
   <a class="close" href='#'></a>
<?php } ?>
	<form id="send-review" class="form-box ajax_form" method="<?php echo $this->_method;?>" action="/<?=Yii::app()->language;?>/empty/" autocomplete="off" enctype="multipart/form-data">
	<?php
		if ( !empty($result) ) {
			echo '<p class="message">'.Yii::t('site', 'ThanksMsgHead').'</p>';	
		} else {
			foreach ( $fields as $name=>$fld )
				echo $this->buildField($name, $fld);
		}
	?>	
	<div class="button">
		<a href="/<?=Yii::app()->language;?>/guestbook/" class="toreviews"><?=Yii::t('site', 'Go to reviews');?></a>
		<?php if ( empty($result) ) { ?>
		<input type="submit" value="<?=Yii::t('site', 'Send a review');?>">
		<?php } ?>
	</div>
	</form>
<?php if ( empty($_GET['ajax_request']) ) { ?>
</div>
<?php } ?>