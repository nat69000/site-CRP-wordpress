<?php $args = array('category' => $param['category_id'], 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $param['limit']); ?>
<?php $posts_array = get_posts($args); ?>
<ul class="ecslider">
    <?php foreach ($posts_array as $post_val) { ?>  
        <?php if ($post_val) { ?>
            <li><?php if (get_the_post_thumbnail($post_val->ID)) { ?>
                    <?php echo get_the_post_thumbnail($post_val->ID, 'full'); ?>
                <?php } else { ?>
                    <img src="http://placehold.it/600x400"/>
                <?php } ?>
                <span><a href="<?php echo get_permalink($post_val->ID); ?>"><?php echo $post_val->post_title ?></a></span>
            </li>
        <?php } ?>
    <?php } ?> 
</ul>
<?php 
if (!isset($param['listposition'])) {
    if (trim($param['listposition']) == 'left') {
        $listPosition = 'left';
    }
    if (trim($param['listposition']) == 'right') {
        $listPosition = 'right';
    }
} else {
    $listPosition = 'right';
}
//displaycontrols
if (!isset($param['displaycontrols'])) {
    if (trim($param['displaycontrols']) == 'true') {
        $displayControls = 'true';
    }
    if (trim($param['displaycontrols']) == 'false') {
        $displayControls = 'false';
    }
} else {
    $displayControls = 'false';
}
//adaptiveduration
if (!isset($param['adaptiveduration'])) {
    if (trim($param['adaptiveduration'])) {
        $adaptiveDuration = trim($param['adaptiveduration']);
    }     
} else {
    $adaptiveDuration = 200;
}
//transitionduration
if (!isset($param['transitionduration'])) {
    if (trim($param['transitionduration'])) {
        $transitionDuration = trim($param['transitionduration']);
    }     
} else {
    $transitionDuration = 500;
}
//intervalduration
if (!isset($param['intervalduration'])) {
    if (trim($param['intervalduration'])) {
        $intervalDuration = trim($param['intervalduration']);
    }     
} else {
    $intervalDuration = 3000;
}
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.ecslider').ecslider({
            listPosition: '<?php echo $listPosition ?>',
            intervalDuration: <?php echo $intervalDuration;?>,
            displayControls: '<?php echo $displayControls ?>',
            adaptiveDuration: '<?php echo $adaptiveDuration ?>',
            transitionDuration: '<?php echo $transitionDuration ?>',
           
        });
    });
</script>