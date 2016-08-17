<?php 
$metatitle = $article['cal_title'];
if ( $article['meta_title'] ) $metatitle = $article['meta_title'];
$this->pageTitle		= $metatitle.' :: '.Yii::app()->name; 
$this->pageKeywords		= $article['meta_keywords']; 
$this->pageDescription	= $article['meta_discriptiion']; 

$news_image = '/images/block1.jpg';
if ( !empty($news->n_text_pic) )
	$news_image = $news->n_text_pic;
if ( !empty($news->n_anons_pic) )
	$news_image = $news->n_anons_pic;
$news_date = $news->n_active_date;
if ( empty($news_date) )
	$news_date = $news->last_change_date;
?>

<div class="blocks">
	<div class="block left">
		<h4 class="block-name"><?= Yii::t('site', 'News'); ?></h4>
		<a href="/<?=Yii::app()->language.'/news/'.$news->n_alias;?>/" class="block-content">
			<div class="pic_container"><img src="<?=$news_image;?>" alt=""></div>
			<div class="block-text">
				<h5 class="name"><?=$news->lang_data[0]->nl_title;?></h5>
				<p class="gray"><?= date('d.m.Y', strtotime($news_date)); ?></p>
				<p><?=$this->fixShortAnons($news->lang_data[0]->nl_anons);?></p>
			</div>
		</a>
	</div>
	<div class="block left">
		<h4 class="block-name"><?= Yii::t('site', 'About us'); ?></h4>
		<a href="/<?=Yii::app()->language.'/'.$about_us->ca_alias;?>/" class="block-content">
			<div class="pic_container"><img src="/images/block2.jpg" alt=""></div>	
			<div class="block-text">
				<p><?=$this->fixShortAnons($about_us->lang_data[0]->cal_text);?></p>
			</div>
		</a>
	</div>
	<div class="block right">
		<h4 class="block-name"><?= Yii::t('site', 'Guestbook'); ?></h4>
		<a href="/<?=Yii::app()->language.'/guestbook';?>/" class="block-content">
			<div class="pic_container"><img src="<?=$question->g_foto;?>" alt=""></div>	
			<div class="block-text">
				<h5 class="name"><?= $question->g_name; ?></h5>
				<p class="gray"><?= date('d.m.Y', strtotime($question->g_date)); ?></p>
				<p><?=$this->fixShortAnons($question->g_text);?></p>
			</div>
		</a>
	</div>	
	<div class="clear"></div>
</div>
<?php
$total_clients = file_get_contents(Yii::app()->basePath . '/data/total_counter.txt');
?>
<h2 class="green"><?= Yii::t('site', 'Total users count'); ?>  <?= str_pad($total_clients, 4, '0', STR_PAD_LEFT).' '.$this->countSklonenie($total_clients, array( Yii::t('site', 'Human_alot'), Yii::t('site', 'Human_few'), Yii::t('site', 'Human_one') )); ?></h2>

<div class="how">
	<table>
		<?/*
		<tr>
			<th class="left-col"><h3>Как получить деньги</h3></th>
			<th class="empty"></th>
			<th class="right-col"><h3>Как получить деньги</h3></th>
		</tr>
		*/?>		
		<tr>
			<td class="left-col">
				<div class="how-item">
					<p class="how-item-name"><?= Yii::t('site', 'Main_page_benefits_1'); ?></p>
				</div>
			</td>
			<td class="empty"></td>
			<td class="right-col">
				<div class="how-item">
					<p class="how-item-name"><?= Yii::t('site', 'Main_page_benefits_2'); ?></p>
				</div>
			</td>
		</tr>
		<tr>
			<td class="left-col">
				<div class="how-item">
					<p class="how-item-name"><?= Yii::t('site', 'Main_page_benefits_3'); ?></p>
				</div>
			</td>
			<td class="empty"></td>
			<td class="right-col">
				<div class="how-item">
					<p class="how-item-name"><?= Yii::t('site', 'Main_page_benefits_4'); ?></p>
				</div>
			</td>
		</tr>
		<tr>
			<td class="left-col">
				<div class="how-item">
					<p class="how-item-name"><?= Yii::t('site', 'Main_page_benefits_5'); ?></p>
				</div>
			</td>
			<td class="empty"></td>
			<td class="right-col">
				<div class="how-item">
					<p class="how-item-name"><?= Yii::t('site', 'Main_page_benefits_6'); ?></p>
				</div>
			</td>
		</tr>
	</table>
</div>