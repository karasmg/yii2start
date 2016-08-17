<?php
$this->pageTitle = Yii::t ( 'site', 'login' ) . '::' . Yii::app ()->name;
?>
<div class="widget-content">
<h2><?php echo Yii::t('site', 'Password changed successfully');?></h2>
<div class="inp-block">
		<a href="<?= '/' . Yii::app ()->language . '/login' ?>"><?=Yii::t('site', 'login');?></a>
</div>
</div>	