<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<?/*<p>Header</p>*/?>
<div class="content">
	<?php echo $content; ?>
</div>
<br>
<p><?= Yii::t('site', '* If this was sent to you in error - please do not respond to it and remove it.') ?></p>
<hr align="left" style="border-top: 1px solid #8c8b8b; width:200px">
<div>
    <?= Yii::t('site', 'Thank you.') ?><br>
    <?= Yii::t('site', 'Your lender.') ?><br>
    <a target="_blank" href="<?= Yii::app()->request->hostInfo?>" ><?= Yii::app()->request->hostInfo?></a>
</div>
</body>
</html>