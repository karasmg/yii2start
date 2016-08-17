<?php if ( !empty($_GET['ajax_request']) && $_GET['ajax_request'] == 1 ) { 
	$model = new CallbackForm();
	$model->run();	
} else {
	$metatitle = $article['cal_title'];
	if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
	$this->pageTitle		= $metatitle.' :: '.Yii::app()->name; 
	$this->pageKeywords		= $article['meta_keywords']; 
	$this->pageDescription	= $article['meta_discriptiion']; 
?>
<div class="widget-content contact-widget">
	<div class="contact-box">
		<?php echo $article['cal_text'];?>
	</div>
	<?php
	$model = new ContactForm();
	$model->run();
	?>
</div>

<div class="map-box">
	<script type="text/javascript" charset="utf-8" src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=7RYmaq4oqbt-7EdWLPKg7tfBvQ5kjgZr&width=958&height=358"></script>
</div>

<div class="popup-box call-me animated">
   <a class="close" href='#'></a>
	<?php
	$model = new CallbackForm();
	$model->run();
	?>
</div>
<?php } ?>