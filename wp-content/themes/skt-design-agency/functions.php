<?php
/**
 * SKT Design Agency functions and definitions
 *
 * @package SKT Design Agency
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */

if ( ! function_exists( 'skt_design_agency_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function skt_design_agency_setup() {
	if ( ! isset( $content_width ) )
		$content_width = 640; /* pixels */

	load_theme_textdomain( 'skt-design-agency', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('woocommerce');
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo', array(
		'height'      => 50,
		'width'       => 210,
		'flex-height' => true,
	) );
	add_theme_support( 'custom-header', array( 'header-text' => false ) );
	register_nav_menu( 'primary', esc_attr__( 'Primary Menu', 'skt-design-agency' ) );
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff'
	) );
	
	add_editor_style( 'editor-style.css' );
}
endif; // skt_design_agency_setup
add_action( 'after_setup_theme', 'skt_design_agency_setup' );


function skt_design_agency_widgets_init() {	
	
	register_sidebar( array(
		'name'          => esc_attr__( 'Blog Sidebar', 'skt-design-agency' ),
		'description'   => esc_attr__( 'Appears on blog page sidebar', 'skt-design-agency' ),
		'id'            => 'sidebar-1',
		'before_widget' => '',		
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
	) );	
	
}
add_action( 'widgets_init', 'skt_design_agency_widgets_init' );


function skt_design_agency_font_url(){
		$font_url = '';		
		
		/* Translators: If there are any character that are not
		* supported by Oswald, trsnalate this to off, do not
		* translate into your own language.
		*/
		$pacifico = _x('on','Pacifico:on or off','skt-design-agency');
 		$ptsans = _x('on','PT Sans:on or off','skt-design-agency');
		$oswald = _x('on','Oswald:on or off','skt-design-agency');
		$lobster = _x('on','Lobster:on or off','skt-design-agency');
		
		/* Translators: If there has any character that are not supported 
		*  by Scada, translate this to off, do not translate
		*  into your own language.
		*/		
		
		if('off' !== $pacifico){
			$font_family = array();
			
			if('off' !== $pacifico){
				$font_family[] = 'Pacifico:400';
			}

			if('off' !== $ptsans){
				$font_family[] = 'PT+Sans:400,400italic,700,700italic';
			}	
			
			if('off' !== $oswald){
				$font_family[] = 'Oswald:400,700,300';
			}
			
			if('off' !== $lobster){
				$font_family[] = 'Lobster:400';
			}																	
				
			$query_args = array(
				'family'	=> urlencode(implode('|',$font_family)),
			);
			
			$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
		}
		
	return $font_url;
	}


function skt_design_agency_scripts() {
	wp_enqueue_style('sktdesignagency-font', skt_design_agency_font_url(), array());
	wp_enqueue_style('sktdesignagency-basic-style', get_stylesheet_uri() );
	wp_enqueue_style('sktdesignagency-editor-style', get_template_directory_uri().'/editor-style.css');
	wp_enqueue_style('nivo-slider', get_template_directory_uri().'/css/nivo-slider.css');
	wp_enqueue_style('sktdesignagency-main-style', get_template_directory_uri().'/css/responsive.css');		
	wp_enqueue_style('sktdesignagency-base-style', get_template_directory_uri().'/css/style_base.css');
	wp_enqueue_script('jquery-nivo-slider', get_template_directory_uri() . '/js/jquery.nivo.slider.js', array('jquery') );
	wp_enqueue_script('sktdesignagency-custom_js', get_template_directory_uri() . '/js/custom.js');
	wp_enqueue_style('font-awesome', get_template_directory_uri().'/css/font-awesome.css');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'skt_design_agency_scripts' );

define('SKT_URL','http://www.sktthemes.net', 'skt-design-agency');
define('SKT_THEME_URL','http://www.sktthemes.net/themes', 'skt-design-agency');
define('SKT_THEME_DOC','http://sktthemesdemo.net/documentation/skt-designagency-doc/#playing-with-customizer/', 'skt-design-agency');
define('SKT_PRO_THEME_URL','http://www.sktthemes.net/shop/design-agency/', 'skt-design-agency');
define('SKT_LIVE_DEMO','http://sktthemesdemo.net/design-agency/', 'skt-design-agency');
define('SKT_THEME_FEATURED_SET_VIDEO_URL','https://www.youtube.com/watch?v=310YGYtGLIM', 'skt-design-agency');
define('SKT_FREE_THEME_URL','http://www.sktthemes.net/product-category/free-wordpress-themes/', 'skt-design-agency');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template for about theme.
 */
require get_template_directory() . '/inc/about-themes.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

if ( ! function_exists( 'skt_design_agency_the_site_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 */
function skt_design_agency_the_site_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;

// get slug by id
function skt_design_agency_get_slug_by_id($id) {
	$post_data = get_post($id, ARRAY_A);
	$slug = $post_data['post_name'];
	return $slug; 
}

// Add a Custom CSS file to WP Admin Area
function skt_design_agency_admin_theme_style() {
    wp_enqueue_style('custom-admin-style', get_template_directory_uri() . '/css/admin.css');
}
add_action('admin_enqueue_scripts', 'skt_design_agency_admin_theme_style');