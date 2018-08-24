<?php
/*
Plugin Name: 七牛
Description: 七牛云是国内领先的以视觉智能和数据智能为核心的企业级云计算服务商，同时也是国内最有特色的智能视频云服务商，累计为 70 多万家企业提供服务，覆盖了国内80%网民。围绕富媒体场景推出了对象存储、融合 CDN 加速、容器云、大数据平台、深度学习平台等产品、并提供一站式智能视频云解决方案。为各行业及应用提供可持续发展的智能视频云生态，帮助企业快速上云，创造更广阔的商业价值。
Version:  1.0
Author: Lerte Smith
Author URI: https://lerte.com/
*/

if (!defined('ABSPATH')) exit;
require_once plugin_dir_path( __FILE__ ) . 'lib/qiniu/php-sdk-7.2.6/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Qiniu\Cdn\CdnManager;

define('QINIU_SDK_VERSION', '7.2.6');
define('QINIU_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('QINIU_PLUGIN_URL', plugin_dir_url( __FILE__ ));

class QiniuPlugin {
	public $auth;
	public $bucketMgr;
	public $uploadMgr;
	public $cdnManager;

	public function __construct(){
		register_uninstall_hook('uninstall.php', '');
	    add_action('admin_init', array($this, 'qiniu_init'));
	    add_action('admin_menu', array($this, 'qiniu_side_menu'));

	    add_filter('manage_media_columns', array($this, 'media_lib_add_upload_column') );
	    add_action('manage_media_custom_column', array($this, 'media_lib_upload_column_value'), 0, 2);
		
		add_action( 'media_upload_file', array($this, 'media_lib_upload_action'), 10, 1); 

	    add_action('media_buttons', array($this, 'media_qiniu'), 11);
		add_action('wp_ajax_qiniu_update_options', array($this, 'qiniu_update_options'));
		add_filter('wp_media_upload_handler', array($this, 'upload_to_qiniu'), 1, 2);
		add_filter('wp_get_attachment_url', array($this, 'url_to_qiniu'), 1, 2);
		add_filter('intermediate_image_sizes_advanced', array($this, 'add_image_insert_override'), 1, 2);

	    add_action('wp_ajax_qiniu_register_image', array($this, 'ajax_register_image'));
	}
	public function QiniuPlugin(){
		self::__construct();
	}
	function qiniu_include_assets() {
		$assets_dir = plugins_url('', __FILE__);

		wp_enqueue_style('qiniu-style', $assets_dir . '/css/qiniu.css?sdk='.QINIU_SDK_VERSION);
		wp_enqueue_style('vuetify-style', $assets_dir . '/css/vuetify.min.css');

		wp_enqueue_script('jquery');
		// wp_enqueue_script('vue', $assets_dir . '/js/vue.min.js');
		wp_enqueue_script('vue', 'http://cdn.bootcss.com/vue/2.5.16/vue.js');
		wp_enqueue_script('vuetify', $assets_dir . '/js/vuetify.min.js');
		wp_enqueue_script('qiniu', $assets_dir . '/js/main.js');
	}
	function qiniu_init(){
		$accessKey = get_option('qiniu_ak');
		$secretKey = get_option('qiniu_sk');
		if($accessKey && $secretKey){
			$this->auth = new Auth($accessKey, $secretKey);
			$this->uploadMgr = new UploadManager();
			$this->bucketMgr = new BucketManager($this->auth);
			$this->cdnManager = new CdnManager($this->auth);
		}
	}
	function qiniu_update_options(){
		check_ajax_referer('qiniu', 'qiniu_nonce');
		update_option('qiniu_ak', $_POST['qiniu_ak']);
		update_option('qiniu_sk', $_POST['qiniu_sk']);
		exit;
	}
	function ajax_register_image(){
	}
	function get_attachment_dir($url){
		$upload_dir = wp_upload_dir()['basedir'];
		$bridge = array_pop(explode('/', $upload_dir));
		$urlArray = explode('/', $url);
		$index = array_search($bridge, $urlArray);
		$rest = implode('/', array_slice($urlArray, $index+1));
		return $upload_dir.DIRECTORY_SEPARATOR.$rest;
	}
	function HumanReadableFilesize($size) {
		$mod = 1024; 
		$units = explode(' ','B KB MB GB TB PB');
		for ($i = 0; $size > $mod; $i++) {
			$size /= $mod;
		}
		return round($size, 2) . ' ' . $units[$i];
	}
	function media_lib_add_upload_column($cols){
		$cols['file_size'] = __('文件大小', 'qiniu');
		$cols['qiniu_url'] = __('七牛', 'qiniu');
		return $cols;
	}
	function media_lib_upload_column_value($column_name, $attachment_id){
		$url = wp_get_attachment_url($attachment_id);
		$dir = $this->get_attachment_dir($url);
		if($column_name == 'file_size'){
			echo $this->HumanReadableFilesize(filesize($dir));
		}
		if($column_name == 'qiniu_url'){
			echo $md5file = md5_file($dir);
		}
	}
	function media_lib_upload_action($wp_media_upload_handler){
		var_dump($wp_media_upload_handler);
	}
	function media_qiniu(){
	}
	function upload_to_qiniu($iframe, $post_id){
		echo $iframe;
	}
	function url_to_qiniu($url, $post_id){
		$metadata = wp_get_attachment_metadata($post_id);
		return $url.'lerte';
	}
	function add_image_insert_override($sizes){
		unset($sizes['thumbnail']);
		unset($sizes['medium']);
		unset($sizes['large']);
		return $sizes;
	}
	function configured() {
		return $this->auth;
	}
	function app_page(){
		include_once(QINIU_PLUGIN_PATH.'app.php');
	}
	function qiniu_side_menu(){
		$this->qiniu_init();
    	add_menu_page(
			__('七牛'),
			__('七牛'),
			'administrator', 
			'qiniu', 
			array($this, 'app_page'),
			QINIU_PLUGIN_URL . '/images/favicon.png'
		);
	}
}

$qiniu = new QiniuPlugin();