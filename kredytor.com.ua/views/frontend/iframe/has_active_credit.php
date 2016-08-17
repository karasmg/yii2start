<div class="widget-content">
	<h2 class="h2-title"><?= Yii::t('site', 'You already have credit and can see it by link')?></h2>
    <div class="inp-block">
        <ul>
            <li> - <?= Yii::t('site', 'You already have credit in our company')?>;</li>
            <li> - <?= Yii::t('site', 'Probably your order in process')?>.</li>
        </ul>
        <br>
        <p><?= Yii::t('site', 'For getting detail information follow')?> <a href="<?=Yii::app()->params->parent_host.Yii::app ()->language . '/login'?>" target = "_blank" ><?=Yii::t('site', 'by link');?></a>:</p>
    </div>
</div>
