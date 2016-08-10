<?php
/*
Plugin Name: Huge IT Responsive Slider
Plugin URI: http://huge-it.com/wordpress-responsive-slider
Description: Create the most stunning sliders for your mobile friendly website with Huge-IT Responsive Slider.
Version: 2.2.9
Author: http://huge-it.com/
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: reslide
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * define global variables
 */
$reslide_admin_menu_pages;
$reslide_admin_submenu_pages;

/**
 * Define constants
 */
define( "reslide_PLUGIN_PATH_FRONT_IMAGES", plugins_url( 'Front_images', __FILE__ ), true );
define( "reslide_PLUGIN_PATH_IMAGES", plugins_url( 'images', __FILE__ ), true );
define( "reslide_PLUGIN_PATH_MEDIA", plugin_dir_path( __FILE__ ) . 'media-control', true );
define( "reslide_PLUGIN_PATH_JS", plugins_url( 'js', __FILE__ ), true );
define( "reslide_PLUGIN_PATH_CSS", plugins_url( 'css', __FILE__ ), true );
define( "reslide_PLUGIN_PATH_FRONTEND", plugin_dir_path( __FILE__ ) . 'front-end', true );

/**
 * Define table names
 */
global $wpdb;
define( "reslide_TABLE_SLIDERS", $wpdb->prefix . 'huge_it_reslider_sliders', true );
define( "reslide_TABLE_SLIDES", $wpdb->prefix . 'huge_it_reslider_slides', true );


/**
 * hooks
 */
add_action( 'plugins_loaded', 'reslide_load_plugin_textdomain' );
add_action( 'media_buttons_context', 'reslide_add_media_button' );
add_action( 'admin_footer', 'reslide_media_button_popup' );
add_action( "wp_loaded", "reslide_loaded_slider_callback" );
add_action( 'admin_menu', 'reslide_slider_options_panels' );
add_action( 'admin_enqueue_scripts', 'reslide_admin_scripts' );
add_action( 'wp_enqueue_scripts', 'reslide_frontend_scripts' );
add_action( 'wp_ajax_reslide_actions', 'reslide_ajax_action_callback' );
add_action( 'wp_ajax_nopriv_reslide_actions', 'reslide_ajax_action_callback' );

/**
 * shortcode hooks
 */
add_shortcode( 'R-slider', 'reslide_resliders_shortcode' );

/**
 * activation hook
 */
register_activation_hook( __FILE__, 'reslide_Slider_activate' );

/**
 * @param $_str
 *
 * @return mixed|string
 */
function reslide_TextSanitize( $_str ) {
	$d = html_entity_decode( $_str );
	$d = wp_kses_stripslashes( $d );
	$d = str_replace( "\n", "<br>", $d );
	$d = stripslashes( $d );

	return $d;
}

/**
 * load textdomain
 */
function reslide_load_plugin_textdomain() {
	load_plugin_textdomain( 'reslide', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

/**
 * media button for editor
 */
function reslide_add_media_button($context) {
	$container_id = 'reslide_slider_insert_popup';

  	$context .=  '<a href="#TB_inline?width=600&inlineId='.$container_id.'" id="insert-reslider-media" class="thickbox button"><img src="' . plugins_url( 'images/edit-icon1.png', __FILE__ ) . '">Add Slider</a>';
	return $context;
}

/**
 * popup for media button in editor
 */
 
function reslide_media_button_popup() {
	global $wpdb,$reslide_admin_menu_pages;
	$screen = get_current_screen();
	$screen_id = $screen->id;
	if( $screen_id != 'post' ){
		return;
	}
	$s     = 1;
	$table = reslide_TABLE_SLIDERS;
	$row = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE %d", $s ) );
	?>
	<!--  add in post popup-->
	<div id="reslide_slider_insert_popup" style="display:none;">
		<select id="R-slider" name="ss" class="button-primary" style="width: 80%;display:block;margin:0 auto;">
			<option value="0">Responsive Sliders</option>
			<?php
			if ( $row ) {
				foreach ( $row as $rows ) { ?>
					<option value="<?php echo $rows->id; ?>"><?php echo $rows->title; ?></option>
				<?php }
			}; ?>
		</select>
	</div>
<?php 
} 
/**
 * @param $atts
 * @param $content
 * @param $tag
 *
 * @return string
 */
function reslide_resliders_shortcode( $atts, $content, $tag ) {

	$values = shortcode_atts( array(
		'id' => 'other'
	), $atts );

	return reslide_resliders_list( $atts['id'] );

}

/**
 * @param $id
 *
 * @return string
 */
function reslide_resliders_list( $id ) {
	require_once( "Front_end/reslider_front_end_view.php" );
	require_once( "Front_end/reslider_front_end_func.php" );

	return reslide_showPublished_sliders( $id );
}

/**
 * admin pages callback for plugin
 */
function reslide_sliders() {
	require_once( "admin/reslider_view.php" );
	require_once( "admin/reslider_func.php" );
	require_once( "admin/reslide_view.php" );
	require_once( "admin/reslide_func.php" );
	require_once( "media-control/add_slide_popups.php" );

	if ( isset( $_GET["page"] ) ) {
		if ( isset( $_GET["task"] ) ) {
			$task = esc_html( $_GET["task"] );
		} else {
			$task = '';
		}

		if ( isset( $_GET["id"] ) ) {
			$id = intval( ( $_GET["id"] ) );
		} else {
			$id = 0;
		}
		if ( isset( $_GET["slideid"] ) ) {
			$slideid = intval( ( $_GET["slideid"] ) );
		} else {
			$slideid = 0;
		}
		switch ( $task ) {
			case 'editslider':
				reslide_edit_slider( $id );
				break;
			case 'removeslider':
				if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'reslider_removeslider' ) ) {
					reslide_remove_slider( $id );
				}
				break;
			case 'editslide':
				reslide_edit_slide( $slideid, $id );
				break;
			default:
				reslide_sliders_list_func();
				break;
		}
	}
}

/**
 * Handle adding new slider
 */
function reslide_loaded_slider_callback() {
	if ( ! is_admin() ) {
		return;
	}
	if ( isset( $_GET['page'] ) && $_GET['page'] == "reslider" ) {
		if ( isset( $_GET['task'] ) ) {
			$task = $_GET['task'];
		} else {
			return;
		}
		if ( isset( $_GET["id"] ) ) {
			$id = intval( ( $_GET["id"] ) );
		} else {
			$id = 0;
		}
		require( "admin/reslider_view.php" );
		require_once( "admin/reslider_func.php" );
		require( "admin/reslide_view.php" );
		require_once( "admin/reslide_func.php" );
		switch ( $task ) {
			case "addslider":
				if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'reslide_addslider' ) ) {
					reslide_add_slider( $id );
				}
				break;
		}
	} else {
		return;
	}
}

/**
 * Print out usage notice
 */
function reslide_slider_usage() {
	?>
	<h3 style='line-height: 1.5;color: #496A7D;font-style: italic;'><?php _e( 'This plugin is the LITE version of the Responsive Slider. If you want to customize to the styles and colors of your website,than you need to buy <a href="http://huge-it.com/wordpress-responsive-slider"> Full License</a>. Purchasing <a href="http://huge-it.com/wordpress-responsive-slider">Full License</a> will add possibility to customize the general options of the Responsive Slider.', 'reslide' ); ?></h3>
	<p><a class="button-primary"
	      href="http://huge-it.com/wordpress-responsive-slider"><?php _e( 'Purchase Full Version', 'reslide' ); ?></a>
	</p>
	<?php
}

/**
 * Print out banner notice for free version
 */
function reslide_free_version_banner() {
	?>
	<div class="free_version_banner">
		<img class="manual_icon" src="<?php echo reslide_PLUGIN_PATH_IMAGES; ?>/icon-user-manual.png"
		     alt="user manual"/>
		<p class="usermanual_text">If you have any difficulties in using the options, Follow the link to <a
				href="http://huge-it.com/wordpress-responsive-slider-user-manual/" target="_blank">User Manual</a></p>
		<a class="get_full_version" href="http://huge-it.com/wordpress-responsive-slider/" target="_blank">GET THE FULL
			VERSION</a>
		<a href="http://huge-it.com" class="gotohuge" target="_blank"><img class="huge_it_logo"
		                                                                   src="<?php echo reslide_PLUGIN_PATH_IMAGES; ?>/Huge-It-logo.png"/></a>
		<div style="clear: both;"></div>
		<div class="description_text"><p>This is the LITE version of the plugin. Click "GET THE FULL VERSION" for more
				advanced options. We appreciate every customer.</p></div>
	</div>
	<?php
}
/**
 * Print out featured plugins page
 */
function reslide_slider_FP() {
	include_once("admin/huge_it_featured_plugins.php");	
}
/**
 * Add admin menu/sub-menu pages
 */
function reslide_slider_options_panels() {
	global $reslide_admin_menu_pages;
	add_menu_page( 'Responsive Slider', 'Responsive Slider', 'manage_options', 'reslider', 'reslide_sliders', plugins_url( 'images/edit-icon1.png', __FILE__ ) );
	$reslide_admin_menu_pages['main_page']       = add_submenu_page( 'reslider', 'Sliders', 'Sliders', 'manage_options', 'reslider', 'reslide_sliders' );
	$reslide_admin_menu_pages['usage'] = add_submenu_page( 'reslider', 'Slider Usage', 'Slider Usage', 'manage_options', 'reslide-Menu-first', 'reslide_slider_usage' );
	$reslide_admin_menu_pages['featured_plugins'] = add_submenu_page( 'reslider', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'reslide-Menu-second', 'reslide_slider_FP' );
}

/**
 * enqueue admin scripts for our plugin pages
 *
 * @param $hook string
 */
function reslide_admin_scripts( $hook ) {
	global $reslide_admin_menu_pages;
	wp_enqueue_script( 'reslide_helper_script', reslide_PLUGIN_PATH_JS . '/helper.js' );
	wp_enqueue_script( 'add_slide_popups', reslide_PLUGIN_PATH_JS . '/add_slide_popups.js' );

	wp_localize_script( 'add_slide_popups', 'i18n_obj', array(
		'editslider_link' => admin_url( 'admin.php?page=reslider&task=editslider&id=1' ),
	) );
	
	if(!isset($reslide_admin_menu_pages['main_page'])){
		return;
	}
	
	if (  $hook ==  $reslide_admin_menu_pages['main_page'] ) {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_media();
		wp_enqueue_style( 'reslide_admin_css', reslide_PLUGIN_PATH_CSS . '/admin.css' );
		wp_enqueue_style( 'reslide_popups_css', reslide_PLUGIN_PATH_CSS . '/popups.css' );
		wp_enqueue_style( 'reslide_fa_css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome' . $suffix . '.css' );

		if ( ! wp_script_is( "thickbox" ) ) {
			add_thickbox();
		}
		if ( ! wp_script_is( 'jquery' ) ) {
			wp_enqueue_script( 'jquery' );
		}
		if ( wp_script_is( 'jquery-ui-sortable' ) ) {
			wp_enqueue_script( 'jquery-ui-sortable', false, array( 'jquery' ) );
		}
		$taskString = explode( '&', $_SERVER["QUERY_STRING"] );
		if ( in_array( 'task=editslide', $taskString ) or in_array( 'task=editslider', $taskString ) ) {
			wp_enqueue_script( 'reslide_jssordebug_js', reslide_PLUGIN_PATH_JS . '/jssor.js' );
			wp_enqueue_script( 'reslide_jscolor_js', reslide_PLUGIN_PATH_JS . '/resliderjscolor' . $suffix . '.js' );
			wp_enqueue_script( 'reslide_ajax', reslide_PLUGIN_PATH_JS . '/ajax.js' );

			wp_enqueue_script( 'reslide_admin_js', reslide_PLUGIN_PATH_JS . '/admin.js' );
			wp_localize_script( 'reslide_ajax', 'reslide_ajax_object',
				array(
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'plugin_name' => 'reslider',
					'nonce'       => wp_create_nonce( 'reslide_nonce' ),
					'images_url'  => untrailingslashit( reslide_PLUGIN_PATH_IMAGES ),
				)
			);
		}

	}

}

/**
 * front-end scripts
 */
function reslide_frontend_scripts() {
	if ( ! wp_script_is( 'jquery' ) ) {
		wp_enqueue_script( 'jquery' );
	}
	wp_enqueue_script( 'reslide_jssor_front', reslide_PLUGIN_PATH_JS . '/jssor.js' );
	wp_enqueue_script( 'reslide_helper_script_front_end', reslide_PLUGIN_PATH_JS . '/helper.js' );
}

/**
 * ajax callback
 */
function reslide_ajax_action_callback() {
	if ( isset( $_REQUEST['nonce'] ) && ! empty( $_REQUEST['nonce'] ) ) {
		$nonce = $_REQUEST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'reslide_nonce' ) ) {
			echo json_encode( array( "error" => __( 'Wrong wp_nonce parameter', 'reslide' ) ) );
			die();
		}
	} else {
		echo json_encode( array( "error" => __( 'Wrong wp_nonce parameter', 'reslide' ) ) );
		die();
	}

	global $wpdb;

	if ( isset( $_POST['reslide_do'] ) ) {
		$reslide_do = esc_html( $_POST['reslide_do'] );

		if ( $reslide_do == 'reslide_save_all' ) {
			$arrayForupdate           = array();
			$arrayForupdateFormatting = array();
			if ( isset( $_POST['custom'] ) ) {
				$custom = wp_kses_stripslashes( $_POST['custom'] );

				$arrayForupdate = array_merge( $arrayForupdate, array( 'custom' => $custom ) );
				array_push( $arrayForupdateFormatting, '%s' );
			}
			if ( isset( $_POST['style'] ) ) {
				$style = wp_kses_stripslashes( $_POST['style'] );

				$arrayForupdate = array_merge( $arrayForupdate, array( 'style' => $style ) );
				array_push( $arrayForupdateFormatting, '%s' );
			}
			if ( isset( $_POST['params'] ) ) {
				$params = wp_kses_stripslashes( $_POST['params'] );

				$arrayForupdate = array_merge( $arrayForupdate, array( 'params' => $params ) );
				array_push( $arrayForupdateFormatting, '%s' );
			}
			if ( isset( $_POST['name'] ) ) {
				$name = $_POST['name'];
				$name = wp_kses_stripslashes( $name );
				$name = trim( $name, '"' );
				$name = esc_html( $name );
			} else {
				$name = "New Slider";
			}
			if ( isset( $_POST['id'] ) ) {
				$id = wp_kses_stripslashes( $_POST['id'] );
				$id = trim( $id, '"' );
				$id = intval( $id );
				if ( $id <= 0 ) {
					$id = 1;
				}
			} else {
				$id = 1;
			}
			$arrayForupdate = array_merge( $arrayForupdate, array( 'title' => $name ) );
			array_push( $arrayForupdateFormatting, '%s' );
			$wpdb->update(
				reslide_TABLE_SLIDERS,
				$arrayForupdate,
				array( 'id' => $id ),
				$arrayForupdateFormatting,
				array( '%d' )
			);

			wp_die();
		} elseif ( $reslide_do == 'reslide_save_images' ) {

			if ( isset( $_POST['images'] ) && ! empty( $_POST['images'] ) ) {
				$images = $_POST['images'];
			}
			if ( isset( $_POST['slides'] ) && ! empty( $_POST['slides'] ) && is_array( $_POST['slides'] ) ) {
				$slides = $_POST['slides'];
			}
			if ( isset( $_POST['id'] ) ) {
				$id = wp_kses_stripslashes( $_POST['id'] );
				$id = trim( $id, '"' );
				$id = intval( $id );
				if ( $id <= 0 ) {
					$id = 1;
				}
			} else {
				$id = 1;
			}
			if ( isset( $images ) && $images != "none" ) {
				$images = array_reverse( $images );
				foreach ( $images as $image ) {
					$title    = $image['title'];
					$title    = esc_html( $title );
					$ordering = $image['ordering'];
					$ordering = intval( $ordering );
					$wpdb->insert(
						reslide_TABLE_SLIDES,
						array(
							'title'     => $title,
							'thumbnail' => $image['url'],
							'sliderid'  => $id,
							'custom'    => '{}',
							'ordering'  => $ordering
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d'

						)
					);
				};
			}

			if ( isset( $slides ) ) {
				foreach ( $slides as $slide ) {
					$description = $slide['description'];
					$description = esc_html( $description );
					$title       = $slide['title'];
					$title       = esc_html( $title );
					$ordering    = $slide['ordering'];
					$ordering    = intval( $ordering );
					$wpdb->update(
						reslide_TABLE_SLIDES,

						array(
							'title'       => $title,
							'description' => $description,
							'thumbnail'   => $slide['url'],
							'ordering'    => $ordering

						),
						array( 'sliderid' => $id, 'id' => $slide['id'] ),
						array(
							'%s',
							'%s',
							'%s',
							'%d'

						),
						array( '%d', '%d' )
					);
				}
			}
			$myrows = $wpdb->get_results( "SELECT * FROM " . reslide_TABLE_SLIDES . " WHERE sliderid = " . $id . " order by ordering desc" );
			$str    = array();
			foreach ( $myrows as $row ) {
				$st                        = '{"description":"' . wp_unslash( esc_js( $row->description ) ) . '","id":"' . $row->id . '","title":"' . wp_unslash( esc_js( $row->title ) ) . '","type":"' . $row->type . '","url":"' . $row->thumbnail . '","ordering":' . $row->ordering . ',"published":' . $row->published . '}';
				$str[ 'slide' . $row->id ] = $st;
			};
			echo json_encode( $str );

			wp_die();

		} elseif ( $reslide_do == 'reslide_save_image' ) {
			if ( isset( $_POST['id'] ) ) {
				$id = wp_kses_stripslashes( $_POST['id'] );
				$id = trim( $id, '"' );
				$id = intval( $id );
				if ( $id <= 0 ) {
					$id = 1;
				}
			} else {
				$id = 1;
			}
			if ( isset( $_POST['slide'] ) ) {
				$slide = wp_kses_stripslashes( $_POST['slide'] );
				$slide = trim( $slide, '"' );
				$slide = intval( $slide );
				if ( $slide <= 0 ) {
					$slide = 1;
				}
			} else {
				$slide = 1;
			}
			if ( isset( $_POST['custom'] ) ) {
				$custom = wp_kses_stripslashes( $_POST['custom'] );
			} else {
				$custom = '{}';
			}
			if ( isset( $_POST['title'] ) ) {
				$title = esc_html( $_POST['title'] );
			} else {
				$title = "";
			}
			if ( isset( $_POST['description'] ) ) {
				$description = esc_html( $_POST['description'] );
			} else {
				$description = "";
			}
			$wpdb->update(
				reslide_TABLE_SLIDES,

				array(
					'custom'      => $custom,
					'title'       => $title,
					'description' => $description
				),
				array( 'sliderid' => $id, 'id' => $slide ),
				array(
					'%s',
					'%s',
					'%s'
				),
				array( '%d', '%d' )
			);
			wp_die();

		} elseif ( $reslide_do == 'reslide_remove_image' ) {
			if ( isset( $_POST['id'] ) ) {
				$id = wp_kses_stripslashes( $_POST['id'] );
				$id = trim( $id, '"' );
				$id = intval( $id );
				if ( $id <= 0 ) {
					$id = 1;
				}
			} else {
				$id = 1;
			}
			if ( isset( $_POST['slide'] ) ) {
				$slide = wp_kses_stripslashes( $_POST['slide'] );
				$slide = trim( $slide, '"' );
				$slide = intval( $slide );
				if ( $slide <= 0 ) {
					$slide = 1;
				}
			} else {
				$slide = 1;
			}

			$wpdb->delete( reslide_TABLE_SLIDES, array( 'id' => $slide ), array( '%d' ) );
			//}
			echo $slide;
			wp_die();

		} elseif ( $reslide_do == 'reslide_on_image' ) {
			if ( isset( $_POST['id'] ) ) {
				$id = wp_kses_stripslashes( $_POST['id'] );
				$id = trim( $id, '"' );
				$id = intval( $id );
				if ( $id <= 0 ) {
					$id = 1;
				}
			} else {
				$id = 1;
			}
			if ( isset( $_POST['slide'] ) ) {
				$slide = wp_kses_stripslashes( $_POST['slide'] );
				$slide = trim( $slide, '"' );
				$slide = intval( $slide );
				if ( $slide <= 0 ) {
					$slide = 1;
				}
			} else {
				$slide = 1;
			}
			if ( isset( $_POST['published'] ) ) {
				$published = $_POST['published'];
				$published = trim( $published, '"' );
				$published = intval( $published );
			} else {
				$published = 0;
			}
			$wpdb->update(
				reslide_TABLE_SLIDES,

				array(
					'published' => $published
				),
				array( 'id' => $slide ),
				array( '%d' )
			);            //}
			echo $slide;
			wp_die();

		}
	}
}

/**
 * Plugin activation function
 */
function reslide_Slider_activate() {
	global $wpdb;
	$collate = '';

	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}
	$table             = reslide_TABLE_SLIDERS;
	$sql_sliders_Table = "
CREATE TABLE IF NOT EXISTS `$table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  `params` mediumtext NOT NULL,
  `time` datetime NOT NULL,
  `slide` longtext,
  `style` text NOT NULL,
  `custom` text NOT NULL,
  PRIMARY KEY (`id`)
)  $collate AUTO_INCREMENT=1 ";
	$table             = reslide_TABLE_SLIDES;
	$sql_slides_Table  = "
CREATE TABLE IF NOT EXISTS  `$table`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `sliderid` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `slide` longtext,
  `description` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `custom` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)   $collate AUTO_INCREMENT = 1";
	$table             = reslide_TABLE_SLIDERS;

	$sql_sliders_Table_init = <<<query1
INSERT INTO `$table` (`title`, `type`, `params`, `time`, `slide`, `style`, `custom`) VALUES
( 'First Slider', 'simple', '{"autoplay":1,"effect":{"type":3,"duration":1500,"interval":1000},"thumbnails":{"show":0,"positioning":0},"custom":{"type":"text"},"title":{"show":1,"position":"1","style":{"width":213,"height":61,"left":"571.375px","top":"14.7031px","color":"FFFFFF","opacity":0,"font":{"size":18},"border":{"color":"FFFFFF","width":1,"radius":2},"background":{"color":"FFFFFF","hover":"30FF4F"}}},"description":{"show":1,"position":"1","style":{"width":768,"height":116,"left":"16.375px","top":"345.703px","color":"FFFFFF","opacity":80,"font":{"size":14},"border":{"color":"3478FF","width":0,"radius":2},"background":{"color":"000000","hover":"000000"}}},"arrows":{"show":2,"type":1,"style":{"background":{"width":"49","height":"49","left":"91px 46px","right":"-44px 1px","hover":{"left":"91px 46px","right":"-44px 1px"}}}},"bullets":{"show":0,"type":"0","position":0,"autocenter":"0","rows":1,"s_x":10,"s_y":10,"orientation":1,"style":{"background":{"width":"60","height":"60","color":{"hover":"646464","active":"30FF4F","link":"CCCCCC"}},"position":{"top":"16px","left":"10px"}}}}', '2016-05-02 10:58:58', NULL, '{"background":"blue;","border":"1px solid red;","color":"yellow","width":"800","height":"480","marginLeft":"0","marginRight":"0","marginTop":"0","marginBottom":"0"}', '{}');
query1;
	$table                  = reslide_TABLE_SLIDES;

	$sql_slides_Table_init = "
INSERT INTO `$table` (`title`, `sliderid`, `published`, `slide`, `description`, `thumbnail`, `custom`, `ordering`, `type`) VALUES
( 'CABS', 1, 1, NULL, 'Lorem ipsum dolor sit amet, ne verear elaboraret mel. Ea sed quaestio pericula. Vel ludus pericula ex, euripidis conceptam abhorreant an sed. Vis ad apeirian antiopam molestiae..', '" . reslide_PLUGIN_PATH_FRONT_IMAGES . "/Default/1.jpg', '{}', 5, ''),
( 'MESSY EVENING', 1, 1, NULL, 'Lorem ipsum dolor sit amet, ne verear elaboraret mel. Ea sed quaestio pericula. Vel ludus pericula ex, euripidis conceptam abhorreant an sed. Vis ad apeirian antiopam molestiae.. ', '" . reslide_PLUGIN_PATH_FRONT_IMAGES . "/Default/2.jpg', '{}', 4, ''),
( 'UMBRELLA', 1, 1, NULL, 'Lorem ipsum dolor sit amet, ne verear elaboraret mel. Ea sed quaestio pericula. Vel ludus pericula ex, euripidis conceptam abhorreant an sed. Vis ad apeirian antiopam molestiae.. ', '" . reslide_PLUGIN_PATH_FRONT_IMAGES . "/Default/3.jpg', '{}', 3, ''),
( 'OLD TRAM', 1, 1, NULL, 'Lorem ipsum dolor sit amet, ne verear elaboraret mel. Ea sed quaestio pericula. Vel ludus pericula ex, euripidis conceptam abhorreant an sed. Vis ad apeirian antiopam molestiae.. ', '" . reslide_PLUGIN_PATH_FRONT_IMAGES . "/Default/4.jpg', '{}', 2, ''),
( 'THE MIXTURE ', 1, 1, NULL, 'Lorem ipsum dolor sit amet, ne verear elaboraret mel. Ea sed quaestio pericula. Vel ludus pericula ex, euripidis conceptam abhorreant an sed. Vis ad apeirian antiopam molestiae..', '" . reslide_PLUGIN_PATH_FRONT_IMAGES . "/Default/5.jpg', '{}', 1, '');
";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql_sliders_Table );
	dbDelta( $sql_slides_Table );
	if ( ! $wpdb->get_var( "select count(*) from " . reslide_TABLE_SLIDERS ) ) {
		$wpdb->query( $sql_sliders_Table_init );
		if ( ! $wpdb->get_var( "select count(*) from " . reslide_TABLE_SLIDES ) ) {
			$wpdb->query( $sql_slides_Table_init );
		}
	}
}