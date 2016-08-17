<?php 
$metatitle = $article['nl_title'];
if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
$this->pageTitle		= $metatitle.' :: '.Yii::app()->name; 
$this->pageKeywords		= $article['meta_keywords']; 
$this->pageDescription	= $article['meta_discriptiion']; 
?>


<div class="page_content">
	<h1><?php echo $article['nl_title'];?></h1>
	<?php
		$date = strtotime($article['n_active_date']);
		if ( $date ) {
			echo '<p class ="news_date">'.Yii::t('main', 'Added news').': '.date('d.m.Y', $date).'</p>';
		}
		if ( $article['n_anons_pic'] ) {
			echo '<img src = "'.$article['n_text_pic'].'" alt = "'.$article['nl_title'].'" class = "n_text_pic"/>';
		}
	?>
	<p><?php echo $article['nl_text'];?></p>
	<br clear ="all"><p><a href="<?php echo $this->createUrl( '/news' ); ?>" alt ="<?php echo Yii::t('main', '« Back to news list');?>"><?php echo Yii::t('main', '« Back to news list');?></a></p>
	
	

</div>