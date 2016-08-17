<div id="article_gallery">
	<div class="slides_container">
		<?php 
		echo '<a href="'.$pic1.'" target="_blank"><img src="'.$pic1.'" alt=""/></a>';
		foreach ( $pics as $pic ) {
			echo '<a href="'.$pic->ap_path.'" target="_blank"><img src="'.$pic->ap_path.'" alt=""/></a>';
		}			
		?>
	</div>
	<ul class="pagination">
		<?php 
		$numb = 0;
		$class='';
		echo '<li><a href="#"><img src="'.$pic1.'" alt="" /></a></li>';
		foreach ( $pics as $pic ) {
			$numb++;
			if ( $numb>=3 ) {
				$class=' class="numb'.$numb.'"';
			}
			echo '<li'.$class.'><a href="#"><img src="'.$pic->ap_path.'" alt="" /></a></li>';
		}			
		?>
	</ul>
</div>

<script>
	$(function(){
		$('#article_gallery').slides({
			preload: true,
			preloadImage: '/img/wait.gif',
			effect: 'slide, fade',
			crossfade: true,
			slideSpeed: 200,
			fadeSpeed: 500,
			generateNextPrev: true,
			generatePagination: false
		});
	});
</script>
