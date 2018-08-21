<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <a class="nav-tab" href="admin.php?page=tongji-dashboard"><?php echo __('Dashboard'); ?></a>
        <a class="nav-tab" href="admin.php?page=tongji-settings"><?php echo __('Settings'); ?></a>
    </h2>
</div>
<script type="text/javascript">
    (function($){
        $(function(){
            $('.nav-tab-wrapper > a#<?php echo $_GET["page"]; ?>').addClass("nav-tab-active")
        })
    })(jQuery)
</script>
