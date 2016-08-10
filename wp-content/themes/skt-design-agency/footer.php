<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SKT Design Agency
 */
?>

<div id="footer-wrapper">
  <div class="container">
    <div class="footer">
      <div class="cols-3">
        <div class="widget-column-1">
          <?php $hideabout = get_theme_mod('hide_about_footer', '1');?>	
          <?php if($hideabout == ''){ ?>
          <?php if ( '' !== get_theme_mod( 'about_title' ) ){  ?>
          <h5><?php echo esc_attr( get_theme_mod( 'about_title', __('About Design Agency','skt-design-agency'))); ?></h5>
          <?php } ?>
          <?php if ( '' !== get_theme_mod( 'about_description' ) ){  ?>
          <p><?php echo esc_attr( get_theme_mod( 'about_description', __('Sed suscipit mauris nec mauris vulputate, a posuere libero congue. Nam laoreet elit eu erat pulvinar, et efficitur nibh euismod.Nam metus lorem, hendrerit quis ante eget, lobortis elementum neque. Aliquam in ullamcorper quam. Integer euismod ligula in mauris vehic.','skt-design-agency')));
			  ?></p>
          <?php } ?><?php } elseif ( current_user_can( 'edit_theme_options' ) ) { ?>
      	  <?php esc_attr_e('Check Appearance >> Customize >> About Design Agency for controlling this section.','skt-design-agency'); ?>	
          <?php } ?>
          
          <div class="clear"></div>
        </div>
        <div class="widget-column-2">
          <h5>
            <?php esc_attr_e('Latest Posts','skt-design-agency');?>
          </h5>
          <?php $args = array( 'posts_per_page' => 2, 'post__not_in' => get_option('sticky_posts'), 'orderby' => 'date', 'order' => 'desc' );
            $the_query = new WP_Query( $args );
          ?>
          <?php while ( $the_query->have_posts() ) :  $the_query->the_post(); ?>
          <div class="recent-post"><a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('thumbnail'); ?>
            </a> <a href="<?php the_permalink(); ?>">
            <h6><?php the_title();?></h6>
            </a> <?php the_excerpt(); ?>
            <div class="clear"></div>
          </div>
          <?php endwhile; ?>
          <div class="clear"></div>
        </div>
        <div class="widget-column-3">
		  <?php $hidecontact = get_theme_mod('hide_contact', '1');?>	
          <?php if($hidecontact == ''){ ?>
          <h5>
            <?php esc_attr_e('Where to find us','skt-design-agency');?>
          </h5>
          <?php if( '' !== get_theme_mod('contact_add')){ ?>
          <p class="parastyle"><?php echo esc_attr( get_theme_mod('contact_add', 'First Floor, Rogger John Building 69 Market Street Hampshire, London UK 7925', 'skt-design-agency' )); ?></p>
          <?php } ?>
          <div class="phone-no">
            <?php if( '' !== get_theme_mod('contact_no')){ ?>
            <p>
              <?php esc_attr_e('Phone:','skt-design-agency');?>
              <?php echo esc_attr( get_theme_mod('contact_no', '+01 23 456 7890', 'skt-design-agency' )); ?><br>
              <?php } ?>
              <?php if( '' !== get_theme_mod('contact_mail')){ ?>
              <?php esc_attr_e('E-mail:','skt-design-agency');?>
              <a href="mailto:<?php echo sanitize_email(get_theme_mod('contact_mail','support@sitename.com')); ?>"><?php echo esc_attr(get_theme_mod('contact_mail','support@sitename.com')); ?></a> <br>
              <?php } ?>
              <?php if( '' !== get_theme_mod('contact_website')){ ?>
              <?php esc_attr_e('Website:','skt-design-agency');?>
              <a target="_blank" href="<?php echo esc_attr( get_theme_mod('contact_website', 'http://www.sitename.com', 'skt-design-agency' )); ?>"><?php echo esc_attr( get_theme_mod('contact_website', 'http://www.sitename.com', 'skt-design-agency' )); ?></a>
              <?php } ?>
            </p>
          </div>
          <?php } elseif ( current_user_can( 'edit_theme_options' ) ) { ?>
      	  <?php esc_attr_e('Check Appearance >> Customize >> Contact Details for controlling this section.','skt-design-agency'); ?>	
          <?php } ?>
          <div class="clear"></div>
          <div class="social-icons">
		    <?php $hidesocial = get_theme_mod('hide_social', '1');?>	
          	<?php if($hidesocial == ''){ ?>          
            <?php if ( get_theme_mod( 'fb_link' ) !='') { ?>
            <a title="facebook" class="fa fa-facebook fa-1x" target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link','')); ?>"></a>
            <?php } ?>
            <?php if ( get_theme_mod( 'twitt_link' ) !='') { ?>
            <a title="twitter" class="fa fa-twitter fa-1x" target="_blank" href="<?php echo esc_url(get_theme_mod('twitt_link','')); ?>"></a>
            <?php } ?>
            <?php if (get_theme_mod('gplus_link') !='') { ?>
            <a title="google-plus" class="fa fa-google-plus fa-1x" target="_blank" href="<?php echo esc_url(get_theme_mod('gplus_link','')); ?>"></a>
            <?php }?>
            <?php if (get_theme_mod('linked_link') !='') { ?>
            <a title="linkedin" class="fa fa-linkedin fa-1x" target="_blank" href="<?php echo esc_url(get_theme_mod('linked_link','')); ?>"></a>
            <?php } ?>
            <?php } elseif ( current_user_can( 'edit_theme_options' ) ) { ?>
      	  <?php esc_attr_e('Check Appearance >> Customize >> Social Settings for controlling this section.','skt-design-agency'); ?>	
          <?php } ?>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
  <div class="copyright-wrapper">
    <div class="container">
      <div class="copyright-txt"><?php esc_attr_e('&copy; 2016','skt-design-agency');?> <?php bloginfo('name'); ?>. <?php esc_attr_e('All Rights Reserved', 'skt-design-agency');?></div>
      <div class="design-by"><?php printf('<a href="'.esc_url(SKT_FREE_THEME_URL).'" rel="nofollow" target="_blank">SKT Design Agency Theme</a>' ); ?></div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php wp_footer(); ?>
</body></html>