<?php
/*
    Plugin Name: Baidu Tongji
    Description: 此插件利用百度统计API制作，不需要登录百度统计网站即可查看网站统计信息。
    Version: 0.1
    Author: Lerte Smith
    Author URI: lerte.com
    License: GPLv3 or later
*/

if (!defined('ABSPATH')) exit; 
define('BAIDU_TONGJI_PATH', plugin_dir_path(__FILE__));

/* add manage menu to aside */
function tongji_create_menu(){
	include BAIDU_TONGJI_PATH.'views/menus.php';
}
add_action('admin_menu','tongji_create_menu');

/* settings page */
function tongji_settings_page(){
	include(BAIDU_TONGJI_PATH.'views/header.php');
	include(BAIDU_TONGJI_PATH.'views/settings.php');
}

/* dashboard page */
function tongji_dashboard_page(){
    include(BAIDU_TONGJI_PATH.'views/header.php');
    include(BAIDU_TONGJI_PATH.'views/dashboard.php');
}

function baidu_tongji_get_option(){
    $options = get_option('baidu_tongji_options');
    return $options;
}

function baidu_tongji_update_option($options){
    update_option('baidu_tongji_options', $options);
}

/* AJax Handler */
function save_options(){
    check_ajax_referer('baidu-tongji', 'security');
    $options = array();
    $options['login_url'] = $_POST['login_url'];
    $options['api_url'] = $_POST['api_url'];
    $options['uuid'] = $_POST['uuid'];
    $options['account_type'] = $_POST['account_type'];
    $options['username'] = $_POST['username'];
    $options['password'] = $_POST['password'];
    $options['token'] = $_POST['token'];
    baidu_tongji_update_option($options);
    echo __('Changes saved.');
  	exit;
}

function get_tongji(){
    header('Content-Type: application/json');
    require_once(BAIDU_TONGJI_PATH.'tongji/LoginService.inc.php');
    require_once(BAIDU_TONGJI_PATH.'tongji/ReportService.inc.php');
    $options = baidu_tongji_get_option();
    $login_url = $options['login_url'];
    $api_url = $options['api_url'];
    $uuid = $options['uuid'];
    $account_type = $options['account_type'];
  
    $loginService = new LoginService($login_url, $uuid, $account_type);
    // doLogin
    $username = $options['username'];
    $password = $options['password'];
    $token = $options['token'];
    $ret = $loginService->doLogin($username, $password, $token);
    if($ret){
        $ucid = $ret['ucid'];
        $st = $ret['st'];
    }else{
        exit;
    }
    $reportService = new ReportService($api_url, $username, $account_type, $uuid, $token, $ucid, $st);
    // get site id
    $siteList = $reportService->getSiteList();
    $siteId = $siteList['body']['data'][0]['list'][0]['site_id'];
    // get report data of the first site
    $rep = $reportService->getData(array(
        'site_id'       => $siteId,                                                                                //站点ID
        'method'        => isset($_GET['method'])?$_GET['method']:'overview/getCommonTrackRpt',                                   //趋势分析报告
        'start_date'    => isset($_GET['start_date'])?$_GET['start_date']:date('Ymd'),                              //所查询数据的起始日期
        'end_date'      => isset($_GET['end_date'])?$_GET['end_date']:date('Ymd'),                                  //所查询数据的结束日期
        'metrics'       => isset($_GET['metrics'])?$_GET['metrics']:'pv_count,visitor_count,ip_count',              //所查询指标为PV和UV
        'gran'          => isset($_GET['gran'])?$_GET['gran']:'day',                                                //时间粒度(只支持有该参数的报告): day/hour/week/month
        'max_results'   => 0                                                                                        //单次获取数据条数，用于分页；默认是20; 0表示获取所有数据
    ));
    echo $rep['raw'];
    exit;
}
add_action('wp_ajax_save_options', 'save_options');
add_action('wp_ajax_get_tongji', 'get_tongji');
