<script>
	$(function() {
		$('.donothide').tooltip({
			tooltipClass: "info-activation",
			position: {
				my: "center bottom-20",
				at: "right top",
				using: function( position, feedback ) {
					$( this ).css( position );
					$( "<div>" )
							.addClass( "arrow" )
							.addClass( feedback.vertical )
							.addClass( feedback.horizontal )
							.appendTo( this );
				}
			}
		});
	});
</script>
<div class="popup popup-3 donothide">
	<a class="close" href="#"></a>
	<p class="title-popup"><?=Yii::t('site', 'Сard activation');?><br><a class="tooltip" title="<?= Yii::t('site', 'Card activation. What is?') ?>" href="#"><?= Yii::t('site', 'Card activation. Why?') ?></a></p>
	<div class="popup-main">
		<ul>
			<li><span><?= Yii::t('site','Card number')?>:</span><?=$model->maskedCardnumber;?></li>
		</ul>
		<iframe id="payiframe" style="width:100%; height:300px; border:0px; display:none;" name="payiframe" src="/<?=Yii::app()->language;?>/payments/wait/"></iframe>
		<span><?= Yii::t('site', 'Do not recieve SMS?') ?></span>
	</div>
</div>

<script type="text/javascript">
$(window).load(function(){
	$('#payiframe').attr('src', '<?=$url;?>');
	$('.popup.popup-3, .overlay, #payiframe').show();
});
</script>