<?php $this->beginContent('//'.$this->layout_path.'/main'); ?>
<div class="container">
	<div class="bodycontent">
		<div id="content">
			<?php
			if(isset($this->breadcrumbs))
			{
				$this->widget('zii.widgets.CBreadcrumbs', array(
				'homeLink'=>CHtml::link(Yii::t('main', 'Home'), '/m/'),
				'separator'=>' / ',
				'links'=>$this->breadcrumbs,
				));
			}
			?>
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
</div>
<?php $this->endContent(); ?>