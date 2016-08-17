<!-- Start SiteHeart code -->
<script>
    (function(){
    // your widget ID
    var widget_id = 267660;
    _shcp = [{
		widget_id : widget_id,
		side : 'right',
		position : 'left',
		autostart : false,
		callback : function(chat) {
			//console.log( chat );
			$("#sh_button").appendTo("#SiteHeart").show();
			 chat.on("eventJSWidgetExit", function(){
				$("#sh_button").appendTo("#SiteHeart").show();
			 });
		 }
	}];
    // set default language
    var lang = ("<?php echo $this->_lang;?>").substr(0, 2).toLowerCase();
    // script url
    var url = "widget.siteheart.com/widget/sh/" + widget_id + "/"
    + lang + "/widget.js";
    var hcc = document.createElement("script");
    hcc.type = "text/javascript";
    hcc.async = true;
    hcc.src = ("https:" == document.location.protocol ? "https" : "http")
    + "://" + url;
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hcc, s.nextSibling);
	$('#SiteHeart').click(function(){return false;});
    })();
</script>
<style>
#SiteHeart #sh_button img, #SiteHeart #sh_button .shc.sh_title_text {display:none;}
#SiteHeart #sh_button {height:140px; width:40px; background:none; border:none;box-shadow:none;}
</style>
<a href="#" class="helper <?=Yii::app()->language;?>" id="SiteHeart"></a>
<!-- End SiteHeart code -->

