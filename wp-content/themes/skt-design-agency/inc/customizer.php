<?php
/**
 * SKT Design Agency Theme Customizer
 *
 * @package SKT Design Agency
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function skt_design_agency_customize_register( $wp_customize ) {
	
	//Add a class for titles
    class skt_design_agency_Info extends WP_Customize_Control {
        public $type = 'info';
        public $label = '';
        public function render_content() {
        ?>
			<h3 style="text-decoration: underline; color: #DA4141; text-transform: uppercase;"><?php echo esc_html( $this->label ); ?></h3>
        <?php
        }
    }
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	$wp_customize->add_setting('color_scheme',array(
			'default'	=> '#01c6d9',
			'sanitize_callback'	=> 'sanitize_hex_color'
	));
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'color_scheme',array(
			'label' => __('Color Scheme','skt-design-agency'),
 			'description' => __( 'More color options in PRO Version', 'skt-design-agency' ),			
			'section' => 'colors',
			'settings' => 'color_scheme'
		))
	);
// Home About Us Section
	$wp_customize->add_section('about_box',array(
		'title'	=> __('About Us Section','skt-design-agency'),
		'description'	=> __('Select Page from the dropdown','skt-design-agency'),
		'priority'	=> null
	));
	
	$wp_customize->add_setting('aboutpage-setting',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('aboutpage-setting',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for about us section','skt-design-agency'),
			'section'	=> 'about_box'	
	));
		
	$wp_customize->add_setting('hide_about',array(
			'sanitize_callback' => 'sanitize_text_field',
	));	 

	$wp_customize->add_control( 'hide_about', array(
    	   'section'   => 'about_box',
    	   'label'     => __('Hide This Section','skt-design-agency'),
    	   'type'      => 'checkbox'
     ));
// Home About Us Section	
	
	

// Home Boxes 	
	$wp_customize->add_section('page_boxes',array(
		'title'	=> __('Home Featured Boxes','skt-design-agency'),
 		'description' => sprintf( __( 'Featured Image Dimensions : ( 231 X 148 )<br/> Select Featured Image for these pages <br /> How to set featured image %s', 'skt-design-agency' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_THEME_FEATURED_SET_VIDEO_URL.'"' ), __( 'Click Here ?', 'skt-design-agency' )
						)
					),
		'priority'	=> null
	));
	
	$wp_customize->add_setting('page-setting1',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting1',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for box one:','skt-design-agency'),
			'section'	=> 'page_boxes'	
	));
	
	$wp_customize->add_setting('page-setting2',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting2',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for box two:','skt-design-agency'),
			'section'	=> 'page_boxes'
	));
	
	$wp_customize->add_setting('page-setting3',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting3',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for box three:','skt-design-agency'),
			'section'	=> 'page_boxes'
	));	
	
	$wp_customize->add_setting('hide_page_box',array(
			'sanitize_callback' => 'sanitize_text_field',
	));	 

	$wp_customize->add_control( 'hide_page_box', array(
    	   'section'   => 'page_boxes',
    	   'label'     => __('Hide This Section','skt-design-agency'),
    	   'type'      => 'checkbox'
     ));
	 
// Home Boxes


// Home What We Do Three Box 	
	$wp_customize->add_section('three_boxes',array(
		'title'	=> __('Why Choose Us','skt-design-agency'),
 		'description' => sprintf( __( 'Featured Image Dimensions : ( 135 X 135 )<br/> Select Featured Image for these pages <br /> How to set featured image %s', 'skt-design-agency' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_THEME_FEATURED_SET_VIDEO_URL.'"' ), __( 'Click Here ?', 'skt-design-agency' )
						)
					),
		'priority'	=> null
	));
	
	$wp_customize->add_setting('box-setting1',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('box-setting1',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for box one:','skt-design-agency'),
			'section'	=> 'three_boxes'	
	));
	
	$wp_customize->add_setting('box-setting2',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('box-setting2',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for box two:','skt-design-agency'),
			'section'	=> 'three_boxes'
	));
	
	$wp_customize->add_setting('box-setting3',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('box-setting3',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for box three:','skt-design-agency'),
			'section'	=> 'three_boxes'
	));	
	
	$wp_customize->add_setting('hide_why_choose',array(
			'sanitize_callback' => 'sanitize_text_field',
	));	 

	$wp_customize->add_control( 'hide_why_choose', array(
    	   'section'   => 'three_boxes',
    	   'label'     => __('Hide This Section','skt-design-agency'),
    	   'type'      => 'checkbox'
     ));	
	 
// Home What We Do Three Box
 
	// Slider Section		
	$wp_customize->add_section(
        'slider_section',
        array(
            'title' => __('Slider Settings', 'skt-design-agency'),
            'priority' => null,
            'description' => __( 'Featured Image Size Should be ( 1420x551 ) More slider settings available in PRO Version.', 'skt-design-agency' ),			
        )
    );
	
	
	$wp_customize->add_setting('page-setting7',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting7',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide one:','skt-design-agency'),
			'section'	=> 'slider_section'
	));	
	
	$wp_customize->add_setting('page-setting8',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting8',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide two:','skt-design-agency'),
			'section'	=> 'slider_section'
	));	
	
	$wp_customize->add_setting('page-setting9',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'skt_design_agency_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting9',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide three:','skt-design-agency'),
			'section'	=> 'slider_section'
	));	// Slider Section
	
	$wp_customize->add_setting('hide_slides',array(
			'sanitize_callback' => 'sanitize_text_field',
	));	 

	$wp_customize->add_control( 'hide_slides', array(
    	   'section'   => 'slider_section',
    	   'label'     => __('Hide Slider','skt-design-agency'),
    	   'type'      => 'checkbox'
     ));	
 
	$wp_customize->add_section('social_sec',array(
			'title'	=> __('Social Settings','skt-design-agency'),
 			'description' => __( 'Add social icons link here.<br />More icon available in PRO Version', 'skt-design-agency'),			
			'priority'		=> null
	));
	
	$wp_customize->add_setting('fb_link',array(
			'default'	=> '',
			'sanitize_callback'	=> 'esc_url_raw'	
	));
	
	$wp_customize->add_control('fb_link',array(
			'label'	=> __('Add facebook link here','skt-design-agency'),
			'section'	=> 'social_sec',
			'setting'	=> 'fb_link'
	));	
	$wp_customize->add_setting('twitt_link',array(
			'default'	=> '',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	
	$wp_customize->add_control('twitt_link',array(
			'label'	=> __('Add twitter link here','skt-design-agency'),
			'section'	=> 'social_sec',
			'setting'	=> 'twitt_link'
	));
	$wp_customize->add_setting('gplus_link',array(
			'default'	=> '',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('gplus_link',array(
			'label'	=> __('Add google plus link here','skt-design-agency'),
			'section'	=> 'social_sec',
			'setting'	=> 'gplus_link'
	));
	$wp_customize->add_setting('linked_link',array(
			'default'	=> '',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('linked_link',array(
			'label'	=> __('Add linkedin link here','skt-design-agency'),
			'section'	=> 'social_sec',
			'setting'	=> 'linked_link'
	));
	$wp_customize->add_setting('hide_social',array(
			'default' => true,
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
	));	 
	$wp_customize->add_control( 'hide_social', array(
    	   'section'   => 'social_sec',
    	   'label'     => __('Uncheck this box to show this section','skt-design-agency'),
    	   'type'      => 'checkbox'
     ));	
	$wp_customize->add_section('footer_text',array(
			'title'	=> __('Footer About Design Agency','skt-design-agency'),
			'priority'	=> null,
			'description'	=> __('Manage Footer About Section','skt-design-agency')
	));
	$wp_customize->add_setting('skt_design_agency_options[credit-info]', array(
            'type' => 'info_control',
            'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new skt_design_agency_Info( $wp_customize, 'cred_section', array(
		'label'	=> '',
        'section' => 'footer_text',
        'settings' => 'skt_design_agency_options[credit-info]'
        ) )
    );
	$wp_customize->add_setting('about_title',array(
			'default'	=> __('About Design Agency','skt-design-agency'),
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('about_title',array(
			'label'	=> __('Add title here','skt-design-agency'),
			'section'	=> 'footer_text',
			'setting'	=> 'about_title'
	));
	
	$wp_customize->add_setting('about_description', array(
			'default'	=> __('Sed suscipit mauris nec mauris vulputate, a posuere libero congue. Nam laoreet elit eu erat pulvinar, et efficitur nibh euismod.Nam metus lorem, hendrerit quis ante eget, lobortis elementum neque. Aliquam in ullamcorper quam. Integer euismod ligula in mauris vehic.','skt-design-agency'),
			'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('about_description', array(
				'label'	=> __('Add about description for footer','skt-design-agency'),
				'section'	=> 'footer_text',
				'setting'	=> 'about_description',
				'type' => 'textarea'
			)
	);
	
	$wp_customize->add_setting('hide_about_footer',array(
			'default' => true,
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
	));	 

	$wp_customize->add_control( 'hide_about_footer', array(
    	   'section'   => 'footer_text',
    	   'label'     => __('Uncheck this box to show this section','skt-design-agency'),
    	   'type'      => 'checkbox'
     ));	
	
	
	$wp_customize->add_section('contact_sec',array(
			'title'	=> __('Contact Details','skt-design-agency'),
			'description'	=> __('Add you contact details here','skt-design-agency'),
			'priority'	=> null
	));
	
	$wp_customize->add_setting('contact_add',array(
			'default'	=> __('First Floor, Rogger John Building 69 Market Street Hampshire, London UK 7925','skt-design-agency'),
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('contact_add', array(
				'label'	=> __('Add contact address here','skt-design-agency'),
				'section'	=> 'contact_sec',
				'setting'	=> 'contact_add',
				'type' => 'textarea',
			)
	);
	$wp_customize->add_setting('contact_no',array(
			'default'	=> __('+01 23 456 7890','skt-design-agency'),
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('contact_no',array(
			'label'	=> __('Add contact number here.','skt-design-agency'),
			'section'	=> 'contact_sec',
			'setting'	=> 'contact_no'
	));
	$wp_customize->add_setting('contact_mail',array(
			'default'	=> 'support@sitename.com',
			'sanitize_callback'	=> 'sanitize_email'
	));
	
	$wp_customize->add_control('contact_mail',array(
			'label'	=> __('Add your email here','skt-design-agency'),
			'section'	=> 'contact_sec',
			'setting'	=> 'contact_mail'
	));
	
	$wp_customize->add_setting('contact_website',array(
			'default'	=> 'http://www.sitename.com',
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('contact_website',array(
			'label'	=> __('Add your website url here','skt-design-agency'),
			'section'	=> 'contact_sec',
			'setting'	=> 'contact_website'
	));	
	
	$wp_customize->add_setting('hide_contact',array(
			'default' => true,
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
	));	 

	$wp_customize->add_control( 'hide_contact', array(
    	   'section'   => 'contact_sec',
    	   'label'     => __('Uncheck this box to show this section','skt-design-agency'),
    	   'type'      => 'checkbox'
     ));	
 
}
add_action( 'customize_register', 'skt_design_agency_customize_register' );

//Integer
function skt_design_agency_sanitize_integer( $input ) {
    if( is_numeric( $input ) ) {
        return intval( $input );
    }
}

function skt_design_agency_custom_css(){
		?>
        	<style type="text/css">
					a,
					.logo a, .header .header-inner .nav ul li a:hover, 
					.signin_wrap a:hover,				
					.services-wrap .one_fourth:hover h3,
					.blog_lists h2 a:hover,
					#sidebar ul li a:hover,
					.recent-post h6:hover,
					.MoreLink:hover,
					.cols-3 ul li a:hover,.wedobox:hover .btn-small,.wedobox:hover .boxtitle,.slide_more, 
					.threebox a.read-more:hover,.news a.read-more:hover, .news a, .footer a:hover, .signin_wrap .phno .fa, 						.slide_info a.sldbutton
					{ color:<?php echo esc_attr(get_theme_mod('color_scheme','#01c6d9') ); ?>;}
					
					.pagination ul li .current, .pagination ul li a:hover, 
					#commentform input#submit:hover, .social-icons a:hover,.nivo-controlNav a.active
					{ background-color:<?php echo esc_attr(get_theme_mod('color_scheme','#7c7c7c')); ?>;}
					
					.MoreLink:hover, .threebox:hover .chooseus-image, .slide_info a.sldbutton
					{ border-color:<?php echo esc_attr(get_theme_mod('color_scheme','#7c7c7c')); ?>;}
					
			</style>
<?php      
}
         
add_action('wp_head','skt_design_agency_custom_css');	

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function skt_design_agency_customize_preview_js() {
	wp_enqueue_script( 'skt_design_agency_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'skt_design_agency_customize_preview_js' );

function skt_design_agency_custom_customize_enqueue() {
	wp_enqueue_script( 'sktdesignagency-custom-customize', get_template_directory_uri() . '/js/custom.customize.js', array( 'jquery', 'customize-controls' ), false, true );

wp_localize_script( 'custom-customize', 'custom_customize_vars', array('accordion-section-title' => __('Upgrade to PRO Version', 'skt-design-agency')));	
}

add_action( 'customize_controls_enqueue_scripts', 'skt_design_agency_custom_customize_enqueue' );