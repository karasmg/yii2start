<?php 
$metatitle = $article['cal_title'];
if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
$this->pageTitle		= $metatitle.' :: '.Yii::app()->name; 
$this->pageKeywords		= $article['meta_keywords']; 
$this->pageDescription	= $article['meta_discriptiion']; 
?>


<div class="page_content">
	<h1><?php echo $article['cal_title'];?></h1>
	
	<?php echo $article['cal_text'];?>

</div>