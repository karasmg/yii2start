<?php 
$metatitle = $article['nl_title'];
if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
$this->pageTitle		= $metatitle.' :: '.Yii::app()->name; 
$this->pageKeywords		= $article['meta_keywords']; 
$this->pageDescription	= $article['meta_discriptiion']; 

$src = '/img/no_pic_big.jpg';
if ( $article['nl_pic'] ) {
	$src = $article['nl_pic'];
} elseif ( $article['n_text_pic'] ) {
	$src = $article['n_text_pic'];
}



$date = strtotime($article['n_active_date']);
if ( empty($date) )
	$date = strtotime($article['last_change_date']);
?>

<div class="widget-content news-widget news-single">
	<figure class="pix-box">
		<img src="<?=$src;?>" alt="<?=$article['nl_title'];?>" />
	</figure>
	<div class="desc-box">
		<h2 class="h2-title"><?=$article['nl_title'];?></h2>
		<p class="date"><?=date('d.m.Y', $date);?></p>
		<p><?php echo $article['nl_text'];?></p>
	</div>
</div>