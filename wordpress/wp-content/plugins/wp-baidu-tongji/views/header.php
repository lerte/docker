<?php $this->init_assets(); ?>
<div id='wp-baidu-tongji-app'>
    <div class='wrap'>
        <h2 class='nav-tab-wrapper'>
            <a class='nav-tab' :class="{'nav-tab-active': 'tongji-dashboard' == currentPage}" href='admin.php?page=tongji-dashboard'>
                <?php _e('Dashboard'); ?>
            </a>
            <a class='nav-tab' :class="{'nav-tab-active': 'tongji-settings' == currentPage}" href='admin.php?page=tongji-settings'>
                <?php _e('Settings'); ?>
            </a>
        </h2>
    </div>
