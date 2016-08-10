<?php
/**
 * SKT Design Agency About Theme
 *
 * @package SKT Design Agency
 */
 
//about theme info
add_action( 'admin_menu', 'skt_design_agency_abouttheme' );
function skt_design_agency_abouttheme() {    	
	add_theme_page( __('About Theme', 'skt-design-agency'), __('About Theme', 'skt-design-agency'), 'edit_theme_options', 'skt_design_agency_guide', 'skt_design_agency_mostrar_guide');   
} 

//guidline for about theme
function skt_design_agency_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
?>

<style type="text/css">
@media screen and (min-width: 800px) {
.col-left {float:left; width: 65%; padding: 1%;}
.col-right {float:right; width: 30%; padding:1%; border-left:1px solid #DDDDDD; }
}
</style>

<div class="wrapper-info">
	<div class="col-left">
   		   <div style="font-size:16px; font-weight:bold; padding-bottom:5px; border-bottom:1px solid #ccc;">
			  <?php esc_attr_e('About Theme Info', 'skt-design-agency'); ?>
		   </div>
          <p><?php esc_attr_e('SKT Design Agency is a responsive WordPress theme which can be used for web design firms or any other corporate, business, agencies, consulting, firms, shops and any other kind of website purpose. It can also be used for portfolio, photography, personal and blogging as well. Translation ready theme it is compatible with qTranslate X for multilingual and WooCommerce for E-Commerce.','skt-design-agency'); ?></p>
		  <a href="<?php echo SKT_PRO_THEME_URL; ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/free-vs-pro.png" alt="" /></a>
	</div><!-- .col-left -->
	
	<div class="col-right">			
			<div style="text-align:center; font-weight:bold;">
				<hr />
				<a href="<?php echo SKT_LIVE_DEMO; ?>" target="_blank"><?php esc_attr_e('Live Demo', 'skt-design-agency'); ?></a> | 
				<a href="<?php echo SKT_PRO_THEME_URL; ?>"><?php esc_attr_e('Buy Pro', 'skt-design-agency'); ?></a> | 
				<a href="<?php echo SKT_THEME_DOC; ?>" target="_blank"><?php esc_attr_e('Documentation', 'skt-design-agency'); ?></a>
                <div style="height:5px"></div>
				<hr />                
                <a href="<?php echo SKT_THEME_URL; ?>" target="_blank"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/sktskill.jpg" alt="" /></a>
			</div>		
	</div><!-- .col-right -->
</div><!-- .wrapper-info -->
<?php } ?>