<?php 

$this->pageTitle= Yii::t('main', 'News').' :: '.Yii::app()->name; 
?>


<div class="page_content">
	<h1><?php echo Yii::t('main', '«Sviga Kopiyka»`s news');?></h1>
	
	<?php foreach ( $news as $news_row) { ?>
	<div class = "news_row<?php if ( $news_row['n_anons_pic'] ) {echo ' img_in';}?>">
		<h2><a href="<?php echo $this->createUrl( 'news/'.$news_row['n_alias'] ); ?>"><?php echo $news_row['nl_title'];?></a></h2>
		<?php
			$date = strtotime($news_row['n_active_date']);
			if ( $date ) {
				echo '<p class ="news_date">'.Yii::t('main', 'Added news').': '.date('d.m.Y', $date).'</p>';
			}
			if ( $news_row['n_anons_pic'] ) {
				echo '<img src = "'.$news_row['n_anons_pic'].'" alt = "'.$news_row['nl_title'].'" />';
			}
		?>
		<p><?php echo $news_row['nl_anons'];?></p>
	</div>
	<?php } ?>	

</div>