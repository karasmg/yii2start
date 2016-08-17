<?php
$this->pageTitle = Yii::t ( 'site', 'Personal page' ) . ' :: ' . Yii::app ()->name;
?>

<?= $this->buildPersonalMenu($isMenuDisabled, $step); 

?>

<div class="content-page">
	<?php 
	$this->widget('application.components.widgets.CalculatorCred');
	?>
</div>
<?php
/* 
<div class="widget-content widget-other prolongation">
	<h2 class="h2-title"><?php echo Yii::t ( 'site', 'Prolongation' ) ?></h2>
	<p>
		<?php echo Yii::t ( 'site', 'Prolongation text' ) ?>
	</p>
</div>
*/ 
?>