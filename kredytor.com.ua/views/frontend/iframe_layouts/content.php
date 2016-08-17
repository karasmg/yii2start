<!DOCTYPE html>
<html lang="<?=Yii::app()->language;?>">
<head>
	<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="format-detection" content="telephone=no">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php
	if (!empty($this->pageDescription))  echo '<meta name="description" content="' . $this->pageDescription . '" />';
	if (!empty($this->pageKeywords))  echo '<meta name="keywords" content="' . $this->pageKeywords . '" />';
	?>
	<link rel="stylesheet" href="<?=Yii::app()->params->parent_host?>css/calculator.css">
	<link type='text/css' href="<?=Yii::app()->params->parent_host?>css/basic.css" rel='stylesheet' media='screen' />
	<link rel="stylesheet" href="<?=Yii::app()->params->parent_host?>css/style.css">
	<link rel="stylesheet" href="<?=Yii::app()->params->parent_host?>css/popup.css">
	<link rel="stylesheet" href="<?=Yii::app()->params->parent_host?>css/subpages.css">
	<link rel="stylesheet" type="text/css" href="<?=Yii::app()->params->parent_host?>slick/slick.css"/>
	<link rel="stylesheet" type="text/css" href="<?=Yii::app()->params->parent_host?>slick/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->params->parent_host?>css/jquery-ui.css"/>
	<link rel="stylesheet" href="<?=Yii::app()->params->parent_host?>css/cabinet.css">
    <link rel="stylesheet" href="<?=Yii::app()->params->parent_host?>css/responsive.css">
    <?php
    $temp_model = TempZayavkaHelper::getTempZayavka();
    if(  !empty($temp_model) && $temp_model->external_css  ){
    ?>
    <link rel="stylesheet" href="<?= $temp_model->external_css ?>">
    <?php } ?>
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>js/frame.js"></script>
</head>
<body>

	<div class="overlay"></div>
    <div id="wrapper">
        <div class="content">
            <div class="center-box">
				<?php echo $content; ?>
            </div>
            <!-- end center-box -->
        </div>
        <!-- end content -->
    </div>
    <!-- end wrapper -->
	<a href="#" class="totop <?=Yii::app()->language;?>"></a>
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>js/jquery-ui.js"></script>
    <script type='text/javascript' src="<?=Yii::app()->params->parent_host?>js/jquery.simplemodal.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>slick/slick.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>js/jquery.bxslider.min.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>js/owl.carousel.min.js"></script>
    <script type='text/javascript' src="<?=Yii::app()->params->parent_host?>js/basic.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>js/project.js"></script>	
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>js/jquery.mask.min.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->params->parent_host?>js/cabinet.js"></script>
</body>
</html>