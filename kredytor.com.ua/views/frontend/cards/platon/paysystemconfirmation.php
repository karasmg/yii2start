<div class="popup popup-3 donothide">
	<p class="title-popup"><?=Yii::t('site', 'Сard activation');?></p>
	<div class="popup-main">
		<ul>
			<li><span>Номер карты:</span><?=$model->maskedCardnumber;?></li>
		</ul>
		<iframe id="payiframe" style="width:100%; height:300px; border:0px; display:none;" name="payiframe" src="/<?=Yii::app()->language;?>/payments/wait/"></iframe>
	</div>
	<form enctype="multipart/form-data" target="payiframe" method="post" action="https://secure.platononline.com/payment/auth" name="payform" id="payform">
		<input type="hidden" name="payment" value="<?=$data['payment']?>" />
		<input type="hidden" name="key" value="<?=$data['key']?>" />
		<input type="hidden" name="url" value="<?=$data['url']?>" />
		<input type="hidden" name="data" value="<?=$data['data']?>" />
		<input type="hidden" name="formid" value="verify" />
		<input type="hidden" name="sign" value="<?=$sign?>" />
		<input type="hidden" name="ext1" value="<?=$model->ac_id;?>" />
		<input type="hidden" name="ext2" value="paysystemconfirmation" />
	</form>
</div>

<script type="text/javascript">
$(window).load(function(){
	$('#payform').submit();
	$('.popup.popup-3, .overlay, #payiframe').show();
});
</script>