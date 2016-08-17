<div class="popup popup-5">
	<a class="close" href="#"></a>
	<div class="popup-main">
		<iframe id="payiframe" style="width:100%; height:600px; border:0px; display:none;" name="payiframe" src="/<?=Yii::app()->language;?>/payments/wait/"></iframe>
	</div>
	<form enctype="multipart/form-data" target="payiframe" method="post" action="https://secure.platononline.com/payment/auth" name="payform" id="payform">
		<input type="hidden" name="payment" value="<?=$data['payment']?>" />
		<input type="hidden" name="key" value="<?=$data['key']?>" />
		<input type="hidden" name="url" value="<?=$data['url']?>" />
		<input type="hidden" name="data" value="<?=$data['data']?>" />
		<input type="hidden" name="sign" value="<?=$sign?>" />
		<input type="hidden" name="ext1" value="<?=$invoiceNumb;?>" />
		<input type="hidden" name="ext2" value="kreditpayment" />
	</form>
</div>