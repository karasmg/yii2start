<?php 
$metatitle = $article['ccl_title'];
if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
$this->pageTitle		= $metatitle.' :: '.Yii::app()->name; 
$this->pageKeywords		= $article['meta_keywords']; 
$this->pageDescription	= $article['meta_discriptiion']; 

?>


<div class="page_content section">
	<h1><?php echo $article['ccl_title'];?></h1>
	
	<?php echo $article['ccl_text'];
	
	if ( $items ) {
		foreach ( $items as $item ) {
			echo '<p>'.$item['cal_text'].'</p>';
		}
	}
	echo $use_form;
	?>
	
</div>