<?php
$this->pageTitle = Yii::t('main', 'guestbook_meta_title').' :: '.Yii::app()->name; 
$this->pageKeywords		= Yii::t('main', 'guestbook_meta_keywords'); 
$this->pageDescription	= Yii::t('main', 'guestbook_meta_discriptiion'); 
?>
<div class="reviews-section">
<?php
foreach ( $questions as $question ) {?>
	<div class="widget-content reviews-widget clearfix">
		<figure class="pix-box">
			<?= ( !empty($question->g_foto) ? '<img src="'.$question->g_foto.'" alt="" />' : '' );?>			
		</figure>
		<div class="desc-box">
			<h2 class="h2-title"><?=$question->g_name;?><?= ( !empty($question->g_city) ? ' ('.$question->g_city.')' : '' ) ;?></h2>
			<p><?=$question->g_text;?></p>
		</div>
	</div>
<?php }?>
</div>
<?=$pagination;?>

