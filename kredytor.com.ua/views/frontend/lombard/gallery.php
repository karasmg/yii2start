<?php
$i = 0;
?>
<div class="preview-office">
<?php

foreach ( $pics as $pic ) {
	if ( !$i ) {?>
	<div class="big-image">
		<img src="<?=$pic->lp_path;?>" alt="" />
	</div>
	
	<ul class="vertical-carousel">
	<?php } ?>
		<li><a href="<?=$pic->lp_path;?>" class="item-slide"><img src="<?=$pic->lp_path;?>" alt="" /></a></li>
<?php 
	$i++;
} ?>
	</ul>
	<div class="mobile-carousel">
	<?php
	foreach ( $pics as $pic ) { ?>
		<div class="item"><a href="<?=$pic->lp_path;?>" class="item-slide"><img src="<?=$pic->lp_path;?>" alt="" /></a></div>
	<?php } ?>
	</div>
	
</div>