<div id="language-select">
<?php
    // Render options as dropDownList
	echo CHtml::form('/admin');
	foreach($languages as $key=>$lang) {
		echo CHtml::hiddenField(
			$key,
			$this->getOwner()->createMultilanguageReturnUrl($key)
		);
	}
	echo CHtml::dropDownList('_lang', $currentLang, $languages, array('submit'=>'',));
	echo CHtml::endForm();
?>
</div>