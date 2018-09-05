<?php
/**
 * Kola functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Kola
 */

if ( ! function_exists( 'kola_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function kola_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Kola, use a find and replace
		 * to change 'kola' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'kola', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'kola' ),
			'header-menu' => esc_html__( 'Header Menu', 'kola' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'kola_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'kola_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kola_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'kola_content_width', 1170 );
}
add_action( 'after_setup_theme', 'kola_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function kola_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'kola' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'kola' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    register_sidebar( array(
        'name'          => esc_html__( 'Header widget area', 'kola' ),
        'id'            => 'header-widget',
        'description'   => esc_html__( 'Add widgets here.', 'kola' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
	) );
    for ( $i = 1; $i <= intval( 4 ); $i++ ) {
        register_sidebar( array(
            'name' 				=> sprintf( __( 'Footer %d', 'kola' ), $i ),
            'id' 				=> sprintf( 'footer-%d', $i ),
            'description' 		=> sprintf( esc_html__( 'Widgetized Footer Region %d.','kola' ), $i ),
            'before_widget'     => '<section id="%1$s" class="widget %2$s">',
            'after_widget' 		=> '</section>',
            'before_title' 		=> '<h2 class="widget-title">',
            'after_title' 		=> '</h2>',
            )
        );
    }
}
add_action( 'widgets_init', 'kola_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kola_scripts() {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri(). '/css/bootstrap.min.css' );
	wp_enqueue_style( 'kola-style', get_stylesheet_uri() );

	wp_enqueue_script( 'kola-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'kola-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kola_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

remove_filter( 'the_content', 'wpautop' ); // 移除文章p自动标签
remove_filter( 'the_excerpt', 'wpautop' ); // 移除摘要p自动标签
remove_filter( 'comment_text', 'wpautop', 30 ); // 取消评论自动

// Restful -- options
function get_options() {
	header( 'Content-Type:application/json' );
	$options = array(
		'name'					=>	get_bloginfo( 'name' ),
		'description'			=>	get_bloginfo( 'description' ),
		'wpurl'					=>	get_bloginfo( 'wpurl' ),
		'url'					=>	get_bloginfo( 'url' ),
		'admin_email'			=>	get_bloginfo( 'admin_email' ),
		'charset'				=>	get_bloginfo( 'charset' ),
		'version'				=>	get_bloginfo( 'version' ),
		'html_type'				=>	get_bloginfo( 'html_type' ),
		'text_direction'		=>	get_bloginfo( 'text_direction' ),
		'language'				=>	get_bloginfo( 'language' ),
		'stylesheet_url'		=>	get_bloginfo( 'stylesheet_url' ),
		'stylesheet_directory'	=>	get_bloginfo( 'stylesheet_directory' ),
		'template_url'			=>	get_bloginfo( 'template_url' ),
		'pingback_url'			=>	get_bloginfo( 'pingback_url' ),
		'atom_url'				=>	get_bloginfo( 'atom_url' ),
		'rdf_url'				=>	get_bloginfo( 'rdf_url' ),
		'rss_url'				=>	get_bloginfo( 'rss_url' ),
		'rss2_url'				=>	get_bloginfo( 'rss2_url' ),
		'comments_atom_url'		=>	get_bloginfo( 'comments_atom_url' ),
		'comments_rss2_url'		=>	get_bloginfo( 'comments_rss2_url' ),
		'icp' => get_option( 'zh_cn_l10n_icp_num' )
	);
	return $options;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'wp/v2', '/options', array(
	  'methods' => 'GET',
	  'callback' => 'get_options'
	) );
} );

function get_post_category_name( $object ) {
	$post_id = $object['id'];
	$categories = get_the_category( $post_id );
	$category = array();
	foreach( $categories as $cat ){
		array_push( $category, $cat->name );
	}
    return $category;
}

add_action( 'rest_api_init', function () {
    register_rest_field( 'post', 'category', array(
    	'get_callback'    => 'get_post_category_name',
    	'schema'          => null
    ) );
} );

function rest_pre_dispatch_filter( $result, $server, $request ){
	$categories = $request->get_param( 'categories' );
	if( !$categories ){
		return $result;
	}
	if( is_numeric( $categories ) ){
		return $result;
	}else{
		$category = get_category_by_slug( $categories );
		if( !$category ){
			return $result;
		}
		$request -> set_param( 'categories', $category->cat_ID );
		$result = rest_do_request( $request );
	}
	return $result;
}

add_filter('rest_pre_dispatch', 'rest_pre_dispatch_filter', 10, 3);

// function to display number of posts.
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return '0';
    }
    return $count;
}

// function to count views.
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views', 5, 2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('浏览量', 'kola');
    return $defaults;
}
function posts_custom_column_views($column_name, $id){
	if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}