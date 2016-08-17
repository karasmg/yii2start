<?php
if ( $result )
	$msg = 'Success operation';
else {
	$msg = 'Card verify error';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" href="/css/popup.css">
	<script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
</head>
<body>
	<div class="popup-main popup-3">
		<p><?=Yii::t('site', $msg);?></p>
	</div>
	<script type="text/javascript">
	$(window).load(function(){
		 setTimeout("parent.postMessage('reloadwindow', '*')", 1500);
	});
	</script>
</body>
</html>
