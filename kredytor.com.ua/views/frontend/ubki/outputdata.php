<style>
	table { 
		border-collapse: collapse;
		border:1px solid black;
	}
	table td, table th {
		padding: 4px 8px;
		border:1px solid black;
	}
	table th {
		text-align: right;
	}
	table th.header {
		text-align: center;
	}
	.hide {
		display:none;
	}
	.tabs ul.control {
		position: relative;
		z-index:2;
	}
	.tabs ul.control li {
		list-style: none;
		display:block;
		float:left;
		padding:10px 20px;
		border: 1px solid black;
		margin-left:-1px;
		background: #ffffff;
		margin-bottom:-1px;
	}
	.tabs ul.control li.active {
		border-bottom: none;
		padding-bottom: 11px;
	}
	.tabs .contents {
		padding: 10px 20px;
		border: 1px solid black;
	}
	.tabs .contents .content-tab {
		display:none;
	}
	.tabs .contents .content-tab.active {
		display: block;
	}
</style>
<h1>Вывод данных убки полученных <?php echo $reportDate;?></h1><br/>

<div class="tabs">
	<ul class="control">
	<?php
	$active = ' class="active"';
	for ( $i=0, $end=count($output); $i<$end; $i++ ) {
		echo '	<li'.$active.'><a href="#" data-tab="tab-'.$i.'">Закладка '.($i+1).'</a></li>';
		$active = '';
	}
	?>
	</ul>
	<br clear="all"/>
	<div class="contents">
	<?php
	$active = ' active';
	for ( $i=0, $end=count($output); $i<$end; $i++ ) {
		echo '	<div class="content-tab tab-'.$i.$active.'">'.$output[$i].'</div>';
		$active = '';
	}
	?>
	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
$(window).load(function(){
	$('.rollower a.control').click(function(){
		$(this).parent().find('.content').toggleClass('hide');
		return false;
	});
	$('.tabs ul.control li a').click(function(){
		$(this).parents('ul').find('li').removeClass('active');
		$(this).parent().addClass('active');
		var blockClass = $(this).attr('data-tab');
		$(this).parents('.tabs').find('.contents .content-tab').removeClass('active');
		$(this).parents('.tabs').find('.contents .'+blockClass).addClass('active');
		return false;
	});
});
</script>