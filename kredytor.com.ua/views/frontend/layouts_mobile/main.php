<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />

	<link href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/main_mobile.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="/js/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	
	<!--[if lte IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php
	if (!empty($this->pageDescription))  echo '<meta name="description" content="' . $this->pageDescription . '" />';
	?>
	<?php
	if (!empty($this->pageKeywords))  echo '<meta name="keywords" content="' . $this->pageKeywords . '" />';
	?>

	<?php Yii::app()->clientScript->registerPackage('jquery'); ?>

	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-7389774-12']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

	</script>
</head>

<body>
<div class="container" id="page">
	<?php $this->widget('application.components.widgets.LanguageSelectorSite');?>
	
	<div id = "go_back_btn"><img src ="/images/go_back.png" alt =""></div>
	
	<div id="logo"><img src ="/images/logo.png" alt ="«Свіжа Копійка»"><h1>Ломбард «Свіжа Копійка»</h1></div>
	
	<?php echo $content; ?>
	
	<header>
	<div id="header">
		<div id="header-menu">
			<table cellpadding=15 cellspacing=0 class ="main_page_table" align ="left">
				<tbody>
					<tr>
						<td><a href="<?php echo $this->createUrl('/kredit');?>"><img src="<?php echo Yii::app()->baseUrl;?>/flash/alt/header-kredit.jpg" alt="<?php echo Yii::t('main', 'Credid Need');?>?" /><span><?php echo Yii::t('main', 'Credid Need');?>?</span></a></td>
					</tr>
					<tr>
						<td><a href="<?php echo $this->createUrl('/address');?>"><img src="<?php echo Yii::app()->baseUrl;?>/flash/alt/header-address.jpg" alt="<?php echo Yii::t('main', 'Lombards Ardesses');?>" /><span><?php echo Yii::t('main', 'Lombards Ardesses');?></span></a></td>
					</tr>
					<tr>
						<td><a href="<?php echo $this->createUrl('/contact');?>"><img src="<?php echo Yii::app()->baseUrl;?>/flash/alt/header-contact.jpg" alt="<?php echo Yii::t('main', 'Quick contact');?>" /><span><?php echo Yii::t('main', 'Quick contact');?></span></a></td>
					</tr>
					<tr>
						<td><a href="<?php echo $this->createUrl('/freshka');?>"><img src="<?php echo Yii::app()->baseUrl;?>/flash/alt/header-freshka.jpg" alt="<?php echo Yii::t('main', 'Bonus system');?>" /><span><?php echo Yii::t('main', 'Bonus system');?></span></a></td>
					</tr>
					<tr>
						<td><a href="<?php echo $this->createUrl('/news');?>"><img src="<?php echo Yii::app()->baseUrl;?>/flash/alt/header-news.jpg" alt="<?php echo Yii::t('main', 'News');?>"><span><?php echo Yii::t('main', 'News');?></span></a></td>
					</tr>
					<tr>
						<td><a href="<?php echo $this->createUrl('/career');?>"><img src="<?php echo Yii::app()->baseUrl;?>/flash/alt/header-career.jpg" alt="<?php echo Yii::t('main', 'Join the team');?>" /><span><?php echo Yii::t('main', 'Join the team');?></span></a></td>
					</tr>
					<tr>
						<td><a href="<?php echo $this->createUrl('/entertainment');?>"><img src="<?php echo Yii::app()->baseUrl;?>/flash/alt/header-entertainment.jpg" alt="<?php echo Yii::t('main', 'Entertainment');?>" /><span><?php echo Yii::t('main', 'Entertainment');?></span></a></td>
					</tr>
					<tr>
						<td><a href="<?php echo $this->createUrl('/lombard');?>"><img src="<?php echo Yii::app()->baseUrl;?>/flash/alt/header-lombard.jpg" alt="<?php echo Yii::t('main', 'Who we are');?>" /><span><?php echo Yii::t('main', 'Who we are');?></span></a></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div><!-- header -->
	</header>
	<br clear ="all">
	<div id="footer-line1"></div>
	<div id="footer-line2"></div>
	<div id="footer-line3"></div>

</div><!-- page -->

<p id="copy">
	&copy; 2011 <?php echo Yii::t('main', 'Lombard «Свіжа Копійка»');?>
</p>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="/js/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="/js/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

<script type="text/javascript" src="/js/project.js"></script>
</body>
</html>