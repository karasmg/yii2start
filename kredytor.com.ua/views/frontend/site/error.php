<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - 404';
$this->breadcrumbs=array(
	'404',
);
?>

<?php
echo Yii::t('main', '404 error');
echo '<h2>Error '.$code.'</h2>';
echo CHtml::encode($message);
?>
