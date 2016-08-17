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
	<link rel="stylesheet" href="/css/calculator.css?v=<?= Yii::app()->params['resource_version'] ?>">
	<link type='text/css' href="/css/basic.css?v=<?= Yii::app()->params['resource_version'] ?>" rel='stylesheet' media='screen' />
	<link rel="stylesheet" href="/css/style.css?v=<?= Yii::app()->params['resource_version'] ?>">
	<link rel="stylesheet" type="text/css" href="/slick/slick.css?v=<?= Yii::app()->params['resource_version'] ?>"/>
	<link rel="stylesheet" type="text/css" href="/slick/slick-theme.css?v=<?= Yii::app()->params['resource_version'] ?>"/>
    <link rel="stylesheet" href="/css/responsive.css?v=<?= Yii::app()->params['resource_version'] ?>">
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
	<?php $this->widget('application.components.widgets.Analitycs');?>
</head>
<body class="home">
	<?php $this->widget('application.components.widgets.Metrika');?>
	<div class="overlay"></div>
    <div id="wrapper">
		<header class="header">
			<div class="container">
				<div class="language">
					<?php $this->widget('application.components.widgets.UserLogin');?>
					<?php $this->widget('application.components.widgets.LanguageSelectorSite');?>
				</div>
				<div class="navigation">
					<a href="/<?=Yii::app()->language;?>/" class="logo"><img src="/images/logo.jpg" alt=""></a>
					<?php $this->widget('application.components.widgets.MainMenu');?>
				</div>
			</div>
		</header>
		<?php $this->widget('application.components.widgets.CalculatorCred');?>
		<?php //$this->widget('application.components.widgets.HomeHelp');?>
		<div class="slider1">
			<div class="container">
				<div class="your">
					<div><a href="/<?=Yii::app()->language;?>/kredit-online/"> <img src="/images/slide-1_<?=Yii::app()->language;?>.png"></a></div>
					<div><a href="/<?=Yii::app()->language;?>/kredit-online/"> <img src="/images/slide2_<?=Yii::app()->language;?>.png"></a></div>
					<div><a href="/<?=Yii::app()->language;?>/kredit-online/"> <img src="/images/slide3_<?=Yii::app()->language;?>.png"></a></div>
					<div><a href="/<?=Yii::app()->language;?>/kredit-online/"> <img src="/images/slide4_<?=Yii::app()->language;?>.png"></a></div>
					<div><a href="/<?=Yii::app()->language;?>/kredit-online/"> <img src="/images/slide5_<?=Yii::app()->language;?>.png"></a></div>
				</div>
			</div>
		</div>

		<div class="slider2">
			<div class="container">
				<div class="container">
					<div class="your">
						<div><a href="/<?=Yii::app()->language;?>/kredit-nalichnymi-v-otdelenii/"> <img src="/images/slide6_<?=Yii::app()->language;?>.png"></a></div>
						<div><a href="/<?=Yii::app()->language;?>/kredit-nalichnymi-v-otdelenii/"> <img src="/images/slide7_<?=Yii::app()->language;?>.png"></a></div>
						<div><a href="/<?=Yii::app()->language;?>/kredit-nalichnymi-v-otdelenii/"> <img src="/images/slide8_<?=Yii::app()->language;?>.png"></a></div>
						<div><a href="/<?=Yii::app()->language;?>/kredit-nalichnymi-v-otdelenii/"> <img src="/images/slide9_<?=Yii::app()->language;?>.png"></a></div>
					</div>
				</div>
			</div>
		</div>
		<div class="main-content">
			<div class="container">
				<?=$content;?>
			</div>
		</div>
    </div>
    <!-- end wrapper -->
    <footer class="footer-main footer">
		<div class="container">
			<div class="footer-item left copyright">
				© <?=Yii::app()->name;?>, <?=date('Y');?><br>
				<a href="/upload/ugoda_<?=Yii::app()->language;?>.pdf" target="_blank"><?php echo Yii::t('site', 'User agreement');?></a>
			</div>
			<div class="footer-item left phone">
				<?php echo Yii::t('site', 'phone_1');?><br><?php echo Yii::t('site', 'phone_2');?>
			</div>
			<div class="footer-item left support">
				<a href="/<?=Yii::app()->language;?>/kak-eto-rabotaet/"><?php echo Yii::t('site', 'Support');?></a>
			</div>
			<?php $this->widget('application.components.widgets.SocialBottom');?>
		</div>
	</footer>
	<a href="#" class="totop <?=Yii::app()->language;?>"></a>
	<?php $this->widget('application.components.widgets.FeedBackPopup');?>
	<?php $this->widget('application.components.widgets.SiteHart');?>
    <script type='text/javascript' src="/js/jquery.simplemodal.js"></script>
    <script type="text/javascript" src="/slick/slick.js"></script>
    <script type="text/javascript" src="/js/jquery.bxslider.min.js"></script>
    <script type="text/javascript" src="/js/owl.carousel.min.js"></script>
    <script type='text/javascript' src="/js/basic.js?v=<?= Yii::app()->params['resource_version'] ?>"></script>
    <script type="text/javascript" src="/js/project.js?v=<?= Yii::app()->params['resource_version'] ?>"></script>
</body>
</html>