<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package SKT Design Agency
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
 <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(''); ?>>
<div class="header">
  <div class="header-inner">
    <div class="logo"><?php skt_design_agency_the_site_logo(); ?> <a href="<?php echo esc_url( home_url('/') ); ?>">
      <h1>
        <?php bloginfo('name'); ?>
      </h1>
      <span class="tagline">
      <?php bloginfo( 'description' ); ?>
      </span> </a> </div>
    <!-- logo -->
    <div class="header-right">
      <div class="signin_wrap">
        <?php if( '' !== get_theme_mod('contact_no')){ ?>
        <span class="phno"><i class="fa fa-mobile"></i> <?php echo esc_attr( get_theme_mod('contact_no', '+01 23 456 7890', 'skt-design-agency' )); ?></span>
        <?php } ?>
      </div>
      <!--end signin_wrap-->
      
      <div class="clear"></div>
      <div class="toggle"> <a class="toggleMenu" href="<?php echo esc_url('#');?>">
        <?php esc_attr_e('Menu','skt-design-agency'); ?>
        </a> </div>
      <!-- toggle -->
      <div class="nav">
        <?php wp_nav_menu( array( 'theme_location' => 'primary') ); ?>
      </div>
      <!-- nav -->
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
  <!-- header-inner --> 
</div>
<!-- header -->
<?php if (is_front_page() && !is_home()) { ?> 
<!-- Slider Section -->
<?php for($sld=7; $sld<10; $sld++) { ?>
<?php if( get_theme_mod('page-setting'.$sld)) { ?>
<?php $slidequery = new WP_query('page_id='.get_theme_mod('page-setting'.$sld,true)); ?>
<?php while( $slidequery->have_posts() ) : $slidequery->the_post();
$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
$img_arr[] = $image;
$id_arr[] = $post->ID;
endwhile;
}
}
?>
<?php if(!empty($id_arr)){ ?>
<section id="slider-main" <?php if( get_theme_mod( 'hide_slides' ) != '') { ?> class="none"<?php } ?>>
<div id="slider" class="nivoSlider">
	<?php 
	$i=1;
	foreach($img_arr as $url){ ?>
    <img src="<?php echo esc_url($url); ?>" title="#slidecaption<?php echo $i; ?>" />
    <?php $i++; }  ?>
</div>   
<?php 
$i=1;
foreach($id_arr as $id){ 
$title = get_the_title( $id ); 
$post = get_post($id); 
$content = apply_filters('the_content', substr(strip_tags($post->post_content), 0, 150)); 
?>                 
<div id="slidecaption<?php echo $i; ?>" class="nivo-html-caption">
<div class="slide_info">
<h2><?php echo $title; ?></h2>
<?php echo $content; ?>
<a class="sldbutton" href="<?php the_permalink(); ?>"><?php esc_attr_e('Read More', 'skt-design-agency');?></a>
</div>
</div>      
<?php $i++; } ?>       
<div class="clear"></div>        
</section>
<?php } else { ?>
<?php if ( current_user_can( 'edit_theme_options' ) ) { ?>
<section id="slider-main" <?php if( get_theme_mod( 'hide_slides' ) != '') { ?> class="none"<?php } ?>>
<div class="infomessage"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/slide-info.jpg" /></div>
</section>
<?php } ?>
<?php } ?>
<!-- Slider Section -->
<div class="clear"></div>
<?php wp_reset_postdata(); ?>
<?php if( get_theme_mod( 'hide_about' ) == '') { ?>
<div id="wrapTwo">
  <div class="container">
    <div class="wrap_two">
      <?php 
    if( get_theme_mod('aboutpage-setting')) { 
    $queryabout = new WP_query('page_id='.get_theme_mod('aboutpage-setting' ,true)); 
    while( $queryabout->have_posts() ) : $queryabout->the_post();
    ?>
      <h2 class="section_title">
        <?php the_title(); ?>
        <span></span></h2>
      <?php the_content(); ?>
      <?php endwhile; } else { ?>
      <?php if ( current_user_can( 'edit_theme_options' ) ) { ?>
	  <div class="infomessageabout"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/info-about-us.jpg" /></div>
      <?php
    }
    ?>
    <?php
    }
    ?>
    </div>
    <!-- .wrap_two-->
    <div class="clear"></div>
  </div>
  <!-- .container --> 
</div>
<?php } ?>
<?php wp_reset_postdata(); ?>
<?php if( get_theme_mod( 'hide_page_box' ) == '') { ?>
<div id="wrapOne">
  <div class="container">
    <div class="services-wrap">
       <?php
$pages = array();
for ( $count = 1; $count <= 3; $count++ ) {
	$mod = get_theme_mod( 'page-setting' . $count );
	if ( 'page-none-selected' != $mod ) {
		$pages[] = $mod;
	}
}
$filterArray = array_filter($pages);
if(count($filterArray) == 0){ ?>
<?php if ( current_user_can( 'edit_theme_options' ) ) { ?>
<div class="infomessageabout"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/info-featured-box.jpg" /></div>
<?php } ?>
<?php 	
}else{

$filled_array=array_filter($pages);
	
$args = array(
	'posts_per_page' => 3,
	'post_type' => 'page',
	'post__in' => $pages,
	'orderby' => 'post__in'
);
$query = new WP_Query( $args );
if ( $query->have_posts() ) :
	$count = 1;
	while ( $query->have_posts() ) : $query->the_post();
	?>
	<div class="one_third_page <?php if($count%3==0) { ?>last_column<?php } ?>"> <a href="<?php the_permalink(); ?>">
        <?php if(has_post_thumbnail()){ the_post_thumbnail(); } ?>
        <h4>
          <?php the_title(); ?>
        </h4>
        </a>
        <p><?php the_excerpt(); ?></p>
      </div>
        <?php if($count == 0) { ?>
        <div class="clear"></div>
        <?php } ?>
	<?php
	$count++;
	endwhile;
 endif;
}
 
?>
    </div>
    <!-- .services-wrap-->
    <div class="clear"></div>
  </div>
  <!-- .container --> 
</div>
<?php } ?>
<?php wp_reset_postdata(); ?>
<div class="clear"></div>
<?php if( get_theme_mod( 'hide_why_choose' ) == '') { ?>
<section>
  <div class="container">
       
      <div class="whychooseus">
        <?php
$choosepages = array();
for ( $mcount = 1; $mcount <= 3; $mcount++ ) {
	$imod = get_theme_mod( 'box-setting' . $mcount );
	if ( 'page-none-selected' != $imod ) {
		$choosepages[] = $imod;
	}
}
$filterArray = array_filter($choosepages);
if(count($filterArray) == 0){ ?>
<?php if ( current_user_can( 'edit_theme_options' ) ) { ?>
<div class="infomessageabout"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/info-why-choose.jpg" /></div>
<?php } ?>
<?php 	
}else{
?>
<h2 class="section_title"><?php esc_attr_e('Why Choose Us','skt-design-agency');?><span></span></h2>
<?php 
$filled_array=array_filter($choosepages);
$classNo = count($filled_array);	
	
$args = array(
	'posts_per_page' => 3,
	'post_type' => 'page',
	'post__in' => $choosepages,
	'orderby' => 'post__in'
);
$threeboxquery = new WP_Query( $args );
if ( $threeboxquery->have_posts() ) :
	$tbx = 1;
	while ( $threeboxquery->have_posts() ) : $threeboxquery->the_post();
	?>
	<div class="threebox <?php if($tbx%3==0) { ?>last<?php } ?>">
        <a href="<?php the_permalink(); ?>">
          <?php if(has_post_thumbnail()){?>
          <div class="chooseus-image">
             <?php if(has_post_thumbnail()){ the_post_thumbnail(); } ?>
          </div>
          <?php } ?>
          <h4>
            <?php the_title(); ?>
          </h4>
          </a>
          <div class="chooseus-content"><?php the_excerpt(); ?></div>
          <a class="read-more" href="<?php the_permalink(); ?>">
          <?php esc_attr_e('Read More','skt-design-agency');?>
          </a> </div>
        <?php if($tbx == 0) { ?>
        <div class="clear"></div>
        <?php } ?>
	<?php
	$tbx++;
	endwhile;
 endif;
}
 
?>
      </div>
    <!-- middle-align -->
    <div class="clear"></div>
  </div>
  <!-- container --> 
</section>
<?php } ?>
<div class="clear"></div>


<?php } ?>