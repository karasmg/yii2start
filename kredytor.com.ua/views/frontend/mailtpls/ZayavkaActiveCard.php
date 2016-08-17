<p><b><?= Yii::t('site', 'Dear customer!') ?></b></p>
<p><?= Yii::t('site', 'Congratulations, your loan application approved!') ?><br>
<?= Yii::t('site', 'For funds on your card, you must confirm the loan in a private office at') ?>
    <a href="<?= Yii::app()->request->hostInfo?>/ua/login?utm_source=zayavkaactiveoncard&utm_medium=email&utm_campaign=internal" target="_blank"><?= Yii::app()->request->hostInfo?></a>
</p>
<p><?= Yii::t('site', 'We remind that the conscientious use of credit you can borrow larger and at a lower rate!') ?></p>