<?php if ( Yii::app()->user->isGuest ) {?>
	<a href="/<?php echo Yii::app()->language;?>/login/" class="kabinet login"><?php echo Yii::t('site', 'login');?></a> / 	
<?php } else { ?>
	<a href="/<?php echo Yii::app()->language;?>/personalpage/" class="kabinet goinside-mobile"><?php echo Yii::t('site', 'Office');?></a>
	<a href="/<?php echo Yii::app()->language;?>/personalpage/" class="kabinet goinside"><?php echo Yii::t('site', 'Personal office');?></a> / 
	<a href="/<?php echo Yii::app()->language;?>/logout/" class="kabinet logout"><?php echo Yii::t('site', 'logout');?></a>	
<?php } ?>
<br/>
