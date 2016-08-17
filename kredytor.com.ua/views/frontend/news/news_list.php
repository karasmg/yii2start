<?php 
$this->pageTitle= Yii::t('main', 'News').' :: '.Yii::app()->name; 
?>

<?php 
echo $pagination;
foreach ( $news as $news_row) { 
	$date = strtotime($news_row['n_active_date']);
	if ( empty($date) )
		$date = strtotime($news_row['last_change_date']);
?>
<div class="widget-content news-widget">
	<figure class="pix-box">
		<a href="/<?php echo Yii::app()->language.'/'.$this->newscateg_alias.'/'.$news_row['n_alias'];?>/"><img src="<?=$news_row['n_anons_pic'];?>" alt="<?=$news_row['nl_title'];?>" /></a>
	</figure>
	<div class="desc-box">
		<h2 class="h2-title"><a href="/<?php echo Yii::app()->language.'/'.$this->newscateg_alias.'/'.$news_row['n_alias'];?>/"><?=$news_row['nl_title'];?></a></h2>
		<p class="date"><?=date('d.m.Y', $date);?></p>
		<p><?php echo $news_row['nl_anons'];?></p>
		<div class="align-right">
			<a class="view-more" href="/<?php echo Yii::app()->language.'/'.$this->newscateg_alias.'/'.$news_row['n_alias'];?>/"><?=Yii::t('site', 'Read more');?> >>></a>
		</div>
	</div>
</div>
<?php 
} 
echo $pagination;
?>