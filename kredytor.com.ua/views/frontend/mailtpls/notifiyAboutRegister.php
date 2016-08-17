<p><b><?= Yii::t('site', 'Good day.') ?></b></p>
<p><?= Yii::t('site', 'You register on the site') ?> <a href="<?= Yii::app()->request->hostInfo?>/ua/login?utm_source=notifyaboutregister&utm_medium=email&utm_campaign=internal" target="_blank"><?= Yii::app()->request->hostInfo?></a>
</p>
<p><?= Yii::t('site', 'To enter the private office use:') ?><br>
<?= Yii::t('site', 'Login:') ?> "<?=$login;?>"<br>
<?= Yii::t('site', 'Password:') ?> "<?=$password;?>"
</p>