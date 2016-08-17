<script>
    /* Keep session open */
    $(function(){
        var reopenSession = function() {
            $.get("<?= Yii::app()->getRequest()->getHostInfo() ?>/reopen-session.php");
        };

        window.setInterval(reopenSession, 10 * 60 * 1000);
    });
</script>