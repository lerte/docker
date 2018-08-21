<?php
add_menu_page(
	"Baidu Tongji",
	"百度统计",
	"administrator", 
	"tongji-dashboard",
	"tongji_dashboard_page",
	plugins_url('views/icon.png' , dirname(__FILE__))
);

add_submenu_page(
	"tongji-dashboard",
	"百度统计网站概况",
	__('Dashboard'),
	"administrator",
	"tongji-dashboard",
	"tongji_dashboard_page"
);

add_submenu_page(
	"tongji-dashboard",
	"百度统计设置",
	__('Settings'),
	"administrator",
	"tongji-settings",
	"tongji_settings_page"
);