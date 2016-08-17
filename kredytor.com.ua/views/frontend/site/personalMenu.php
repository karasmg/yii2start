<?php
if ( $isMenuDisabled ) { ?>
<ul class="tabs-page">
	<li><a class="<?=($step==1 ? 'active' : '')?><?=($step!=1 ? ' disabled' : '')?>" href="#" data-for="tab-1"><?=Yii::t('site', 'Account data');?></a></li>
	<li><a class="<?=($step==2 ? 'active' : '')?><?=($step!=2 ? ' disabled' : '')?>" href="#" data-for="tab-2"><?=Yii::t('site', 'Personal data');?></a></li>
	<li><a class="<?=($step==3 ? 'active' : '')?><?=($step!=3 ? ' disabled' : '')?>" href="#" data-for="tab-3"><?=Yii::t('site', 'adress');?></a></li>
	<li><a class="<?=($step==4 ? 'active' : '')?><?=($step!=4 ? ' disabled' : '')?>" href="#" data-for="tab-4"><?=Yii::t('site', 'Work data');?></a></li>
	<li><a class="<?=($step==5 ? 'active' : '')?><?=($step!=5 ? ' disabled' : '')?>" href= "<?= '/'.Yii::app()->language.'/personalpage/cards'; ?>"><?=Yii::t('site', 'Bank cards');?></a></li>
	<li><a class="<?=($step==6 ? 'active' : '')?><?=($step!=6 ? ' disabled' : '')?>" href="<?= '/'.Yii::app()->language.'/personalpage/loan/getcredit'; ?>" ><?=Yii::t('site', 'Credit');?></a></li>
</ul>
<?php } else { ?>
<ul class="tabs-page">
	<li><a class="<?=($step==1 ? ' active' : '')?>" href="<?= '/'.Yii::app()->language.'/isregistered/changepassword'; ?>" data-for="tab-1"><?=Yii::t('site', 'Changing password');?></a></li>
	<li><a class="<?=($step==2 ? ' active' : '')?>" href="<?= '/'.Yii::app()->language.'/anketa/2'; ?>" data-for="tab-2"><?=Yii::t('site', 'Personal data');?></a></li>
	<li><a class="<?=($step==3 ? ' active' : '')?>" href="<?= '/'.Yii::app()->language.'/anketa/3'; ?>" data-for="tab-3"><?=Yii::t('site', 'adress');?></a></li>
	<li><a class="<?=($step==4 ? ' active' : '')?>" href="<?= '/'.Yii::app()->language.'/anketa/4'; ?>" data-for="tab-4"><?=Yii::t('site', 'Work data');?></a></li>
	<li><a class="<?=($step==5 ? ' active' : '')?>" href= "<?= '/'.Yii::app()->language.'/personalpage/cards'; ?>" ><?=Yii::t('site', 'Bank cards');?></a></li>
	<li><a class="<?=($step==6 ? ' active' : '')?>" href="<?= '/'.Yii::app()->language.'/personalpage/loan/creditstatus'; ?>" ><?=Yii::t('site', 'Credit');?></a></li>
</ul>
<?php }?>