<?php

/* THEME SETUP
------------------------------------------------ */

if ( ! function_exists( 'kola_setup' ) ) {
	function kola_setup() {
		// Automatic feed
		add_theme_support( 'automatic-feed-links' );
		// Set content-width
		global $content_width;
		if ( ! isset( $content_width ) ) $content_width = 620;
		// Post thumbnails
		add_theme_support( 'post-thumbnails' );
		// Title tag
		add_theme_support( 'title-tag' );
		// Post formats
		add_theme_support( 'post-formats', array( 'aside' ) );
		// Add nav menu
		register_nav_menu( 'primary-menu', __( 'Primary Menu', 'kola' ) );
		// Make the theme translation ready
		load_theme_textdomain( 'kola', get_template_directory() . '/languages' );
		$locale_file = get_template_directory() . "/languages/" . get_locale();
		if ( is_readable( $locale_file ) ) {
			require_once( $locale_file );
		}
	}
	add_action( 'after_setup_theme', 'kola_setup' );
}


/* ENQUEUE STYLES
------------------------------------------------ */

if ( ! function_exists( 'kola_load_style' ) ) {
	function kola_load_style() {
		if ( ! is_admin() ) {
			wp_register_style( 'kola_fonts', '//fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic' );
			wp_enqueue_style( 'kola_style', get_stylesheet_uri(), array( 'kola_fonts' ) );
		} 
	}
	add_action( 'wp_enqueue_scripts', 'kola_load_style' );

}


/* ENQUEUE COMMENT-REPLY.JS
------------------------------------------------ */

if ( ! function_exists( 'kola_load_scripts' ) ) {
	function kola_load_scripts() {
		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'kola_load_scripts' );
}


/* BODY CLASSES
------------------------------------------------ */

if ( ! function_exists( 'kola_body_classes' ) ) {
	function kola_body_classes( $classes ) {
		// Check whether we want it darker
		if ( get_theme_mod( 'kola_dark_mode' ) ) {
			$classes[] = 'dark-mode';
		}
		return $classes;
	}
	add_action( 'body_class', 'kola_body_classes' );
}


/* CUSTOMIZER SETTINGS
------------------------------------------------ */

class kola_customize {
	public static function kola_register ( $wp_customize ) {
		// Dark Mode
		$wp_customize->add_setting( 'kola_dark_mode', array(
			'capability' 		=> 'edit_theme_options',
			'sanitize_callback' => 'kola_sanitize_checkbox',
			'transport'			=> 'postMessage'
		) );
		$wp_customize->add_control( 'kola_dark_mode', array(
			'type' 			=> 'checkbox',
			'section' 		=> 'colors', // Default WP section added by background_color
			'label' 		=> __( 'Dark Mode', 'kola' ),
			'description' 	=> __( 'Displays the site with white text and black background. If Background Color is set, only the text color will change.', 'kola' ),
		) );
		// Make built-in controls use live JS preview
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
		// SANITATION
		// Sanitize boolean for checkbox
		function kola_sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true == $checked ) ? true : false );
		}	
	}
	// Initiate the live preview JS
	public static function kola_live_preview() {
		wp_enqueue_script( 'kola-themecustomizer', get_template_directory_uri() . '/assets/js/theme-customizer.js', array(  'jquery', 'customize-preview' ), '', true );
	}

}

// Restful
function get_options() {
	header('Content-Type:application/json');
	$options = array(
		'name'					=>	get_bloginfo('name'),
		'description'			=>	get_bloginfo('description'),
		'wpurl'					=>	get_bloginfo('wpurl'),
		'url'					=>	get_bloginfo('url'),
		'admin_email'			=>	get_bloginfo('admin_email'),
		'charset'				=>	get_bloginfo('charset'),
		'version'				=>	get_bloginfo('version'),
		'html_type'				=>	get_bloginfo('html_type'),
		'text_direction'		=>	get_bloginfo('text_direction'),
		'language'				=>	get_bloginfo('language'),
		'stylesheet_url'		=>	get_bloginfo('stylesheet_url'),
		'stylesheet_directory'	=>	get_bloginfo('stylesheet_directory'),
		'template_url'			=>	get_bloginfo('template_url'),
		'pingback_url'			=>	get_bloginfo('pingback_url'),
		'atom_url'				=>	get_bloginfo('atom_url'),
		'rdf_url'				=>	get_bloginfo('rdf_url'),
		'rss_url'				=>	get_bloginfo('rss_url'),
		'rss2_url'				=>	get_bloginfo('rss2_url'),
		'comments_atom_url'		=>	get_bloginfo('comments_atom_url'),
		'comments_rss2_url'		=>	get_bloginfo('comments_rss2_url'),
		'icp' => get_option('zh_cn_l10n_icp_num')
	);
	return $options;
}
add_action( 'rest_api_init', function () {
	register_rest_route( 'wp/v2', '/options', array(
	  'methods' => 'GET',
	  'callback' => 'get_options',
	));
});

// Setup the Theme Customizer settings and controls
add_action( 'customize_register', array( 'kola_customize', 'kola_register' ) );
// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init', array( 'kola_customize' , 'kola_live_preview' ) );

remove_filter( 'the_content', 'wpautop' ); //移除文章p自动标签
remove_filter( 'the_excerpt', 'wpautop' ); //移除摘要p自动标签
remove_filter( 'comment_text', 'wpautop', 30 ); //取消评论自动