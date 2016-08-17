<div id="SiteSearch">
	<form action="/<?php echo Yii::app()->language;?>/sitesearch/">
		<input type="text" name="query" class="input_text" value="<?php if ( !empty($_REQUEST['query']) ) echo htmlspecialchars($_REQUEST['query']); ?>" placeholder="<?php echo Yii::t('site', 'search');?>" />
		<input type="submit" class="input_submit" value="<?php echo Yii::t('site', 'Send');?>" />
	</form>
</div>
<br clear="all"/>