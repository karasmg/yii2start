<p><b><?= Yii::t('site', 'Dear customer!') ?></b></p>
<p><?= Yii::t('site', 'We remind you') ?> <?= $period ?> <?=$finish_date;?> <?= Yii::t('site', 'payment date for loan contract') ?> <?=$dog_number;?>.<br>
    <?//= Yii::t('site', 'Payment sum is:') ?> <?//=$summ_to_pay;?> <?//= Yii::t('site', 'uha') ?></p>
<p><?= Yii::t('site', 'Details about payment terms can be found at') ?> <a target="_blank" href="<?= Yii::app()->request->hostInfo?>/ua/login?utm_source=notifyaboutpayment&utm_medium=email&utm_campaign=internal" ><?= Yii::t('site', 'by link') ?></a> <?= Yii::t('site', 'or by calling') ?> <?php echo Yii::t('site', 'phone_1') ?>.</p>
