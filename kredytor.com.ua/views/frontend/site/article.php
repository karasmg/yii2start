<?php 
$metatitle = $article['cal_title'];
if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
$this->pageTitle		= $metatitle.' :: '.Yii::app()->name; 
$this->pageKeywords		= $article['meta_keywords']; 
$this->pageDescription	= $article['meta_discriptiion']; 

if ( strpos($article['cal_text'], 'class="') === false )
	$article['cal_text'] = '<div class="widget-content">'.$article['cal_text'].'</div>';	
?>
<?php echo $article['cal_text'];?>

			