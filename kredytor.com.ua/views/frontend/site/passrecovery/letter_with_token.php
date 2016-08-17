<p><b><?= Yii::t('site', 'Dear customer!') ?></b></p>
<p><?= Yii::t('site', 'You used the password recovery option') ?> <a target="_blank" href="<?= Yii::app()->request->hostInfo?>" ><?= Yii::app()->request->hostInfo?></a></p>
<p><?=Yii::t('site', 'For password recovery enter this link')?> <a href="<?=Yii::app()->getRequest()->hostInfo.'/'.YII::app()->language.'/passrecovery?token='.$token?>"><?= Yii::t('site', 'by link') ?></a></p>
