<?php
/*
Plugin Name: WP Baidu Tongji
Plugin URI: https://wordpress.org/plugins/wp-baidu-tongji
Description: 此插件利用百度统计API制作，不需要登录百度统计网站即可查看网站统计信息。
Version: 0.1
Author: Lerte Smith
Author URI: https://lerte.com
License: GPLv2 or later
*/

if (!defined('ABSPATH')) exit;
define('WP_BAIDU_TONGJI_URL', plugin_dir_url(__FILE__));
define('WP_BAIDU_TONGJI_PATH', plugin_dir_path(__FILE__));

if(!class_exists('WP_Baidu_Tongji')):
class WP_Baidu_Tongji {

    public function __construct(){
        add_action('admin_menu', array($this, 'init_side_menu'));
        add_action('wp_ajax_get_options', array($this, 'get_options'));
        add_action('wp_ajax_set_options', array($this, 'set_options'));
        add_action('wp_ajax_get_tongji', array($this, 'get_tongji'));
    }

    function init_assets() {
        $assets_dir = plugins_url('/assets', __FILE__);
        wp_enqueue_script('jquery');
		if(preg_match('/localhost|127.0.0.1/i', get_bloginfo('url'))){
			wp_enqueue_script('vue', $assets_dir . '/js/vue.js');
		}else{
			wp_enqueue_script('vue', $assets_dir . '/js/vue.min.js');
		}
        wp_enqueue_script('wp-baidu-tongji', $assets_dir . '/js/main.js');
        wp_enqueue_style('wp-baidu-tongji', $assets_dir . '/css/style.css');
    }

    function init_side_menu(){
        add_menu_page(
            'Baidu Tongji',
            __('百度统计'),
            'administrator',
            'tongji-dashboard',
            array($this, 'dashboard_page'),
            WP_BAIDU_TONGJI_URL . 'assets/images/icon.png'
        );
        
        add_submenu_page(
            'tongji-dashboard',
            __('百度统计网站概况'),
            __('Dashboard'),
            'administrator',
            'tongji-dashboard',
            array($this, 'dashboard_page')
        );
        
        add_submenu_page(
            'tongji-dashboard',
            __('百度统计设置'),
            __('Settings'),
            'administrator',
            'tongji-settings',
            array($this, 'settings_page')
        );
    }

    function settings_page(){
        include_once(WP_BAIDU_TONGJI_PATH.'views/header.php');
        include_once(WP_BAIDU_TONGJI_PATH.'views/settings.php');
    }

    function dashboard_page(){
        include_once(WP_BAIDU_TONGJI_PATH.'views/header.php');
        include_once(WP_BAIDU_TONGJI_PATH.'views/dashboard.php');
    }
    
    function set_options(){
        check_ajax_referer('baidu-tongji', 'ajax_nonce');
        $options = array();
        $options['login_url'] = sanitize_text_field($_POST['login_url']);
        $options['api_url'] = sanitize_text_field($_POST['api_url']);
        $options['uuid'] = sanitize_text_field($_POST['uuid']);
        $options['account_type'] = sanitize_text_field($_POST['account_type']);
        $options['username'] = sanitize_text_field($_POST['username']);
        $options['password'] = sanitize_text_field($_POST['password']);
        $options['token'] = sanitize_text_field($_POST['token']);
        update_option('baidu_tongji_options', $options);
        echo __('Changes saved.');
        exit;
    }

    function get_options(){
        $options = get_option('baidu_tongji_options');
        $options['ajax_nonce'] = wp_create_nonce('baidu-tongji');
        echo json_encode($options);
        exit;
    }

    function get_tongji(){
        header('Content-Type: application/json');
        require_once(WP_BAIDU_TONGJI_PATH.'includes/BaiduTongjiLoginService.inc.php');
        require_once(WP_BAIDU_TONGJI_PATH.'includes/BaiduTongjiReportService.inc.php');
        $options = get_option('baidu_tongji_options');
        $login_url = $options['login_url'];
        $api_url = $options['api_url'];
        $uuid = $options['uuid'];
        $account_type = $options['account_type'];
      
        $loginService = new BaiduTongjiLoginService($login_url, $uuid, $account_type);
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
        $reportService = new BaiduTongjiReportService($api_url, $username, $account_type, $uuid, $token, $ucid, $st);
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

}

$wp_baidu_tongji = new WP_Baidu_Tongji();
endif;