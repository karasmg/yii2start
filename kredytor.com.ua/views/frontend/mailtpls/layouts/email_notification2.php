<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<table align="center" cellpadding="4" cellspacing="0" width="580" style="background: url(<?= Yii::app()->request->hostInfo?>/pic/mail_bg.png);">
    <tbody>
    <tr>
        <td colspan="2"><img src="<?= Yii::app()->request->hostInfo?>/pic/mail_header.png" alt="<?= Yii::t('site', 'Kredytor XXI') ?>" title="<?= Yii::t('site', 'Kredytor XXI') ?>" width="580" border="0"></td>
    </tr>
    <tr>
        <td colspan="2">
	<?php echo $content; ?>
        </td>
    </tr>
    <tr>
        <td colspan="2"><?= Yii::t('site', '* If this was sent to you in error - please do not respond to it and remove it.') ?></td>
    </tr>
    <tr>
        <td colspan="2"><hr align="left" style="border-top: 1px solid #8c8b8b; width:200px">
            <?= Yii::t('site', 'Thank you.') ?>
            <?= Yii::t('site', 'Your lender.') ?>
            <a target="_blank" href="<?= Yii::app()->request->hostInfo?>" ><?= Yii::app()->request->hostInfo?></a>
        </td>
    </tr>
    <tr style="background: #339933;">
        <td width="50%" align="center" style="font-size: 0;"><img src="<?= Yii::app()->request->hostInfo?>/pic/mail_footer1.png" alt="<?= Yii::app()->request->hostInfo?>" title="<?= Yii::app()->request->hostInfo?>" width="207" border="0"></td>
        <td width="50%" align="center" style="font-size: 0;"><img src="<?= Yii::app()->request->hostInfo?>/pic/mail_footer2.png" alt="<?= Yii::t('site', 'phone: (044) 394-94-94') ?>" title="<?= Yii::t('site', 'phone: (044) 394-94-94') ?>" width="207" border="0"></td>
    </tr>

    </tbody>
</table>





</div>
</body>
</html>