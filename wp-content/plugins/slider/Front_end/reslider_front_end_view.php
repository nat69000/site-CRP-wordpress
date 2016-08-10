<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * @param $_id
 * @param $_slider
 * @param $_reslides
 *
 * @return string
 */
function reslide_front_end( $_id, $_slider, $_reslides ) {
	ob_start();
	if ( ! function_exists( 'deleteSpacesNewlines' ) ) {
		function deleteSpacesNewlines( $str ) {
			return preg_replace( array( '/\r/', '/\n/' ), '', $str );
		}
	}
	if ( ! $_slider ) {
		echo '<h3 style="color: #FF0011;">R-slider ' . $_id . ' does not exist</h3>';

		return;
	}
	$sliderID = $_slider[0]->id;
	$style = json_decode( $_slider[0]->style );
	$params = json_decode( $_slider[0]->params );
	$customs = json_decode( $_slider[0]->custom );
	$title = $params->title;
	$description = $params->description;
	$paramsJson = deleteSpacesNewlines( $_slider[0]->params );
	$styleJson = deleteSpacesNewlines( $_slider[0]->style );
	$customJson = deleteSpacesNewlines( $_slider[0]->custom );
	if ( ! $sliderID ) {
		echo '<h3 style="color: #FF0011;">R-slider ' . $_id . ' was removed</h3>';

		return;
	}
	if ( ! count( $_reslides ) ) {
		echo '<h3 style="color: #FF0011;">R-slider ' . $_id . ' has not any image </h3>';

		return;
	}
	$count = 0;
	foreach ( $_reslides as $slide ) {
		if ( $slide->published == 0 ) {
			continue;
		}
		$customSlide = json_decode( $slide->custom );
		$count ++;

	}
	?>
	<!-- Construct js Slider -->
	<script>
		var reslider<?php echo $sliderID;?>  = {
			id: '<?php echo $sliderID;?>',
			name: '<?php echo $_slider[0]->title;?>',
			params: JSON.parse('<?php echo $paramsJson;?>'),
			style: JSON.parse('<?php echo $styleJson;?>'),
			custom: JSON.parse('<?php echo $customJson;?>'),
			count: '<?php echo $count;?>',
			slides: {}
		};
		<?php
		foreach ($_reslides as $row) {
		if ( $row->published == 0 ) {
			continue;
		}
		$slideCustum = deleteSpacesNewlines( $row->custom );
		?>
		reslider<?php echo $sliderID;?>['slides']['slide' + '<?php echo $row->id;?>'] = {};
		reslider<?php echo $sliderID;?>['slides']['slide' + '<?php echo $row->id;?>']['id'] = '<?php echo $row->id;?>';
		reslider<?php echo $sliderID;?>.slides['slide' + '<?php echo $row->id;?>']['title'] = '<?php echo $row->title;?>';
		reslider<?php echo $sliderID;?>.slides['slide' + '<?php echo $row->id;?>']['description'] = '<?php echo str_replace( "\n", '<br>', $row->description );?>';
		reslider<?php echo $sliderID;?>.slides['slide' + '<?php echo $row->id;?>']['url'] = '<?php echo $row->thumbnail;?>';
		reslider<?php echo $sliderID;?>.slides['slide' + '<?php echo $row->id;?>']['type'] = '<?php echo $row->type;?>';
		reslider<?php echo $sliderID;?>.slides['slide' + '<?php echo $row->id;?>']['custom'] = JSON.parse('<?php echo $slideCustum; ?>');

		<?php
		}
		?>
	</script>
	<div id="slider<?php echo $sliderID; ?>_container"
	     style="width: <?php echo $style->width; ?>px; height: <?php echo $style->height; ?>px;">


		<div u="loading" class="reslide_loading">
			<!-- your loading screen content here -->
			<div></div>
		</div>

		<!-- Slides Container -->
		<div u="slides" class="reslide_slides">
			<?php foreach ( $_reslides as $slide ) {
				if ( $slide->published == 0 ) {
					continue;
				}

				$customSlide = json_decode( $slide->custom );
				//	var_dump($customSlide);
				?>
				<div id="slide<?php echo $sliderID; ?>_<?php echo $slide->id; ?>">
					<img u="image" src="<?php echo $slide->thumbnail; ?>" alt="<?php echo $slide->thumbnail; ?>"/>
					<img u="thumb" src="<?php echo $slide->thumbnail; ?>" alt="<?php echo $slide->thumbnail; ?>"/>
					<?php if ( $slide->title AND $params->title->show ) {
						?>
						<div class="reslidetitle">
							<div></div>
							<span><?php echo reslide_TextSanitize( $slide->title ); ?></span>
						</div>
					<?php } ?>
					<?php if ( $slide->description AND $params->description->show ) {
						?>

						<div class="reslidedescription">
							<div></div>
							<span><?php echo reslide_TextSanitize( $slide->description ); ?></span>
						</div>
					<?php } ?>

					<?php

					foreach ( $customSlide as $customSlide ) {
						switch ( $customSlide->type ) {
							case 'h3':
								?>
								<h3 class="slide<?php echo $slide->id; ?>h3<?php echo $customSlide->id; ?>  reslideh3">
						<span>
						</span>
						<span class="gg"><?php echo $customSlide->text; ?>
						</span>
								</h3>
								<?php
								break;
							case 'button':
								?>
								<button
									class="slide<?php echo $slide->id; ?>button<?php echo $customSlide->id; ?> reslidebutton reslide_any">
									<div>
									</div>
									<a class="gg"
									   href="<?php echo $customSlide->link; ?>"><span><?php echo $customSlide->text; ?></span>
									</a>
								</button>
								<?php
								break;
							case 'img':
								?>
								<div
									class="slide<?php echo $slide->id; ?>img<?php echo $customSlide->id; ?> reslideimg reslide_any">
									<img src="<?php echo $customSlide->src; ?>" alt="<?php echo $customSlide->alt; ?>">
								</div>

								<?php break;
							default: ?>
							<?php }
					} ?>
				</div>
			<?php } ?>
		</div>
		<?php
		//var_dump($customs);
		foreach ( $customs as $custom ) { ?>
			<?php switch ( $custom->type ) {
				case 'h3':
					?>
					<h3 u="any" class="reslideh3<?php echo $custom->id; ?> reslideh3 reslide_any">
						<span></span>
						<span class="gg"><?php echo $custom->text; ?></span>
					</h3>
					<?php
					break;
				case 'button':
					?>
					<button u="any" class="reslidebutton<?php echo $custom->id; ?> reslidebutton reslide_any">
						<div></div>
						<a class="gg" href="<?php echo $custom->link; ?>"><span><?php echo $custom->text; ?></span></a>
					</button>
					<?php
					break;
				case 'img':
					?>
					<div u="any" class="reslideimg<?php echo $custom->id; ?> reslideimg reslide_any">
						<img src="<?php echo $custom->src; ?>" alt="<?php echo $custom->alt; ?>">
					</div>

					<?php break;
				default: ?>
				<?php } ?>
		<?php } ?>
		<!--#region Bullet Navigator Skin Begin -->


		<!-- bullet navigator container -->
		<div u="navigator" class=" reslide_navigator" style="bottom: 16px; right: 10px;">
			<!-- bullet navigator item prototype -->
			<div u="prototype" class="reslide_dot"></div>
		</div>
		<!--#endregion Bullet Navigator Skin End -->
		<!-- Arrow Left -->
        <span u="arrowleft" class=" reslide_arrow_left" style="top: 123px; left: 8px;">
        </span>
		<!-- Arrow Right -->
        <span u="arrowright" class=" reslide_arrow_right" style="top: 123px; right: 8px;">
        </span>
		<!-- Trigger -->
		<div u="thumbnavigator" class="reslide-thumbnail<?php echo $sliderID; ?>" style="right: 0px; bottom: 0px;">
			<!-- Thumbnail Item Skin Begin -->
			<div u="slides" style=" bottom: 25px; right: 30px;cursor: default;">
				<div u="prototype" class="p">
					<div class=w>
						<div u="thumbnailtemplate" class="t"></div>
					</div>
					<div class=c></div>
				</div>
			</div>
			<!-- Thumbnail Item Skin End -->
		</div>
	</div>
	<style>
		#slider<?php echo $sliderID;?>_container {
			margin: 0 auto;
			margin-bottom: 10px;
			position: relative;
			top: 0px;
			left: 0px;
			display: none;
			overflow: hidden;
		}

		#slider<?php echo $sliderID;?>_container .reslide_slides img {
			display: none;
		}

		#slider<?php echo $sliderID;?>_container .reslide_loading {
			position: absolute;
			top: 0px;
			left: 0px;
		}

		#slider<?php echo $sliderID;?>_container .reslide_loading > div {
			width: 100%;
			height: 100%;
			position: absolute;
			background: #ccc;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_slides {
			width: <?php echo  $style->width;?>px;
			height: <?php echo  $style->height;?>px;
			position: absolute;
			left: 0px;
			top: 0px;
			overflow: hidden;
			cursor: move;
		}

		/*Title and description defaults ***/

		#slider<?php echo $sliderID ;?>_container .reslidetitle, #slider<?php echo $sliderID ;?>_container .reslidedescription {
			position: absolute;
			overflow: hidden;
			z-index: 2;
		}

		#slider<?php echo $sliderID ;?>_container .reslidetitle > div, #slider<?php echo $sliderID ;?>_container .reslidedescription > div {
			width: 100%;
			height: 100%;
			position: absolute;
			left: 0px;
			top: 0px;
			font-size: 14px;
		}

		#slider<?php echo $sliderID ;?>_container .reslidetitle > span, #slider<?php echo $sliderID ;?>_container .reslidedescription > span {
			width: 100%;
			height: 100%;
			position: absolute;
			left: 0px;
			top: 0px;
			z-index: 1;
			padding: 10px;

		}

		/*Title styles ***/

		#slider<?php echo $sliderID ;?>_container .reslidetitle {
			width: <?php echo $title->style->width?>px;
			height: <?php echo $title->style->height?>px;
			top: <?php echo $title->style->top?>;
			left: <?php echo $title->style->left?>;
			border: <?php echo $title->style->border->width;?> px solid #<?php echo $title->style->border->color;?>;
			border-radius: <?php echo $title->style->border->radius;?>px;
		}

		#slider<?php echo $sliderID ;?>_container .reslidetitle > div {
			background: #<?php echo $title->style->background->color;?>;
			opacity: <?php echo $title->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $title->style->opacity;?>);
		}

		#slider<?php echo $sliderID ;?>_container .reslidetitle > span {
			padding: 10px;
			text-align: center;
			text-align: center;
			font-size: <?php echo $title->style->font->size;?>px;
			color: #<?php echo $title->style->color;?>;
		}

		/*Description styles ***/

		#slider<?php echo $sliderID ;?>_container .reslidedescription {
			width: <?php echo $description->style->width?>px;
			height: <?php echo $description->style->height?>px;
			top: <?php echo $description->style->top?>;
			left: <?php echo $description->style->left?>;
			border: <?php echo $description->style->border->width;?> px solid #<?php echo $description->style->border->color;?>;
			border-radius: <?php echo $description->style->border->radius;?>px;
		}

		#slider<?php echo $sliderID ;?>_container .reslidedescription > div {
			background: #<?php echo $description->style->background->color;?>;
			opacity: <?php echo $description->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $description->style->opacity;?>);
		}

		#slider<?php echo $sliderID ;?>_container .reslidedescription > span {
			font-size: <?php echo $description->style->font->size;?>px;
			color: #<?php echo $description->style->color;?>;
		}

		/* slide static elements ***/

		<?php	
		foreach($_reslides as $slide){ 
			if($slide->published == 0) {continue;}
			$customSlide = json_decode($slide->custom);
			foreach($customSlide as $customSlide) {		
				switch($customSlide->type) {
							case 'h3':
						?>
		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>h3<?php echo $customSlide->id;?> {
			margin: 0px;
			padding: 0px;
			z-index: 2;
			position: absolute;
			background: none;
			width: <?php echo $customSlide->style->width;?>px;
			height: <?php echo $customSlide->style->height;?>px;
			border: <?php echo $customSlide->style->border->width;?> px solid #<?php echo $customSlide->style->border->color;?>;
			top: <?php echo $customSlide->style->top;?>;
			left: <?php echo $customSlide->style->left;?>;
			border-radius: <?php echo $customSlide->style->border->radius;?>px;
			overflow: hidden;
			cursor: text;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>h3<?php echo $customSlide->id;?> > span {
			width: 100%;
			height: 100%;
			z-index: 2;
			position: absolute;
			top: 0px;
			left: 0px;
			opacity: <?php echo $customSlide->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $customSlide->style->opacity;?>);
			background: #<?php echo $customSlide->style->background->color;?>;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>h3<?php echo $customSlide->id;?>:hover > span:first-child {
			opacity: <?php echo $customSlide->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $customSlide->style->opacity;?>);
			background: #<?php echo $customSlide->style->background->hover;?>;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>h3<?php echo $customSlide->id;?> > span:first-child {
			background: #<?php echo $customSlide->style->background->color;?>;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>h3<?php echo $customSlide->id;?> .gg {
			width: 100%;
			height: 100%;
			display: block;
			position: absolute;
			text-align: center;
			background: none;
			opacity: 1;
			top: 0px;
			left: 0px;
			font-size: <?php echo $customSlide->style->font->size;?>px;
			z-index: 2;
			color: #<?php echo $customSlide->style->color;?>;
			line-height: 1.5;
		}

		<?php 
			break; 
			case 'button':
		?>
		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>button<?php echo $customSlide->id;?>.reslidebutton {
			padding: 0px;
			z-index: 2;
			position: absolute;
			border: 2px solid rgb(0, 0, 36);
			top: 0px;
			left: 0px;
			border-radius: 0px;
			background: none;
			width: <?php echo $customSlide->style->width;?>px;
			height: <?php echo $customSlide->style->height;?>px;
			border: <?php echo $customSlide->style->border->width;?> px solid #<?php echo $customSlide->style->border->color;?>;
			top: <?php echo $customSlide->style->top;?>;
			left: <?php echo $customSlide->style->left;?>;
			border-radius: <?php echo $customSlide->style->border->radius;?>px;
			overflow: hidden;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>button<?php echo $customSlide->id;?> div {
			width: 100%;
			height: 100%;
			z-index: 2;
			position: absolute;
			top: 0px;
			left: 0px;
			opacity: <?php echo $customSlide->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $customSlide->style->opacity;?>);
			background: #<?php echo $customSlide->style->background->color;?>;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>button<?php echo $customSlide->id;?>:hover div {
			opacity: <?php echo $customSlide->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $customSlide->style->opacity;?>);
			background: #<?php echo $customSlide->style->background->hover;?>;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>button<?php echo $customSlide->id;?> .gg {
			font-size: <?php echo $customSlide->style->font->size;?>px;
			width: 100%;
			height: 100%;
			z-index: 2;
			color: #<?php echo $customSlide->style->color;?>;
			display: block;
			position: absolute;
			text-align: center;
			top: 0px;
			left: 0px;
			text-decoration: none;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>button<?php echo $customSlide->id;?> .gg span {
			width: 100%;
			display: block;
			position: absolute;
			height: auto;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			text-align: center;
			word-wrap: break-word;
			font-size: <?php echo $customSlide->style->font->size;?>px;
			color: #<?php echo $customSlide->style->color;?>;
		}

		<?php 
			break; 
			case 'img':
		?>
		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>img<?php echo $customSlide->id;?>.reslideimg {
			position: absolute;
			z-index: 1;
			overflow: hidden;
			width: <?php echo $customSlide->style->width;?>px;
			height: <?php echo $customSlide->style->height;?>px;
			border: <?php echo $customSlide->style->border->width;?> px solid #<?php echo $customSlide->style->border->color;?>;
			top: <?php echo $customSlide->style->top;?>;
			left: <?php echo $customSlide->style->left;?>;
			border-radius: <?php echo $customSlide->style->border->radius;?>px;
		}

		#slider<?php echo $sliderID ;?>_container #slide<?php echo $sliderID ;?>_<?php echo $slide->id;?> .slide<?php echo $slide->id;?>img<?php echo $customSlide->id;?>.reslideimg img {
			width: 100%;
			height: 100%;
			z-index: 0;
			opacity: <?php echo $customSlide->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo  $customSlide->style->opacity;?>);
		}

		<?php break;default: ?>
		<?php 
		}
	}
}
	?>

		#slider<?php echo $sliderID ;?>_container #slider<?php echo $sliderID ;?>_container .reslide_any {
			width: 100px;
			height: 26px;
			position: absolute;
			top: 10px;
			left: 400px;
		}

		/* Sliders Customs Statics***/
		<?php 
				foreach($customs as $custom){ ?>
		<?php switch($custom->type) {
					case 'h3':
				?>
		#slider<?php echo $sliderID ;?>_container .reslideh3<?php echo $custom->id;?> {
			margin: 0px;
			padding: 0px;
			z-index: 2;
			position: absolute;
			background: none;
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			border: <?php echo $custom->style->border->width;?> px solid #<?php echo $custom->style->border->color;?>;
			top: <?php echo $custom->style->top;?>;
			left: <?php echo $custom->style->left;?>;
			border-radius: <?php echo $custom->style->border->radius;?>px;
			overflow: hidden;
		}

		#slider<?php echo $sliderID ;?>_container .reslideh3<?php echo $custom->id;?> span:first-child {
			width: 100%;
			height: 100%;
			z-index: 2;
			position: absolute;
			top: 0px;
			left: 0px;
			opacity: <?php echo $custom->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $custom->style->opacity;?>);
			background: #<?php echo $custom->style->background->color;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslideh3<?php echo $custom->id;?>:hover span:first-child {
			background: #<?php echo $custom->style->background->hover;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslideh3<?php echo $custom->id;?> .gg {
			width: 100%;
			height: 100%;
			display: block;
			position: absolute;
			text-align: center;
			top: 0px;
			left: 0px;
			font-size: <?php echo $custom->style->font->size;?>px;
			z-index: 2;
			color: #<?php echo $custom->style->color;?>;
			line-height: 1.5;
		}

		<?php 
			break; 
			case 'button':
		?>
		#slider<?php echo $sliderID ;?>_container .reslidebutton<?php echo $custom->id;?> {
			padding: 0px;
			z-index: 2;
			position: absolute;
			border: 2px solid rgb(0, 0, 36);
			top: 0px;
			left: 0px;
			border-radius: 0px;
			background: none;
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			border: <?php echo $custom->style->border->width;?> px solid #<?php echo $custom->style->border->color;?>;
			top: <?php echo $custom->style->top;?>;
			left: <?php echo $custom->style->left;?>;
			border-radius: <?php echo $custom->style->border->radius;?>px;
			overflow: hidden;
		}

		#slider<?php echo $sliderID ;?>_container .reslidebutton<?php echo $custom->id;?> > div {
			width: 100%;
			height: 100%;
			z-index: 2;
			position: absolute;
			top: 0px;
			left: 0px;
			opacity: <?php echo $custom->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $custom->style->opacity;?>);
			background: #<?php echo $custom->style->background->color;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslidebutton<?php echo $custom->id;?>:hover > div {
			opacity: <?php echo $custom->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $custom->style->opacity;?>);
			background: #<?php echo $custom->style->background->hover;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslidebutton<?php echo $custom->id;?> .gg {
			font-size: <?php echo $custom->style->font->size;?>px;
			width: 100%;
			height: 100%;
			z-index: 2;
			color: #<?php echo $custom->style->color;?>;
			display: block;
			position: absolute;
			text-align: center;
			top: 0px;
			left: 0px;
			text-decoration: none;
		}

		#slider<?php echo $sliderID ;?>_container .reslidebutton<?php echo $custom->id;?> .gg span {
			width: 100%;
			display: block;
			position: absolute;
			height: auto;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			text-align: center;
			word-wrap: break-word;
			font-size: <?php echo $customSlide->style->font->size;?>px;
			color: #<?php echo $customSlide->style->color;?>;

		}

		<?php 
			break; 
			case 'img':
		?>
		#slider<?php echo $sliderID ;?>_container .reslideimg<?php echo $custom->id;?> {
			position: absolute;
			z-index: 1;
			overflow: hidden;
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			border: <?php echo $custom->style->border->width;?> px solid #<?php echo $custom->style->border->color;?>;
			top: <?php echo $custom->style->top;?>;
			left: <?php echo $custom->style->left;?>;
			border-radius: <?php echo $custom->style->border->radius;?>px;
		}

		#slider<?php echo $sliderID ;?>_container .reslideimg<?php echo $custom->id;?> img {
			width: 100%;
			height: 100%;
			z-index: 0;
			opacity: <?php echo $custom->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $custom->style->opacity;?>);
		}

		<?php break;default: ?>
		<?php }?>
		<?php 
		}?>

		/*** navigator ***/

		#slider<?php echo $sliderID ;?>_container .reslide_navigator {
			position: absolute;

		}

		#slider<?php echo $sliderID ;?>_container .reslide_navigator div, #slider<?php echo $sliderID ;?>_container .reslide_navigator div:hover, #slider<?php echo $sliderID ;?>_container .reslide_navigator .av {
			position: absolute;
			/* size of bullet elment */
			width: 12px;
			height: 12px;
			border-radius: 10px;
			filter: alpha(opacity=70);
			opacity: .7;
			overflow: hidden;
			cursor: pointer;
			border: #4B4B4B 1px solid;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_navigator div {
			background-color: #<?php echo $params->bullets->style->background->color->link;?>;
		}

		body #slider<?php echo $sliderID ;?>_container .reslide_navigator div:hover {
			background-color: #<?php echo $params->bullets->style->background->color->hover;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_navigator .reslide_dotav {
			background-color: #74B8CF !important;
			border: #fff 1px solid;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_navigator .dn, #slider<?php echo $sliderID ;?>_container .reslide_navigator .dn:hover {
			background-color: #555555;
		}

		/* arrows */

		#slider<?php echo $sliderID ;?>_container .reslide_arrow_left, #slider<?php echo $sliderID ;?>_container .reslide_arrow_right {
			display: block;
			position: absolute;
			/* size of arrow element */
			width: <?php echo $params->arrows->style->background->width;?>px;
			height: <?php echo $params->arrows->style->background->height;?>px;
			cursor: pointer;
			background-image: url(<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES;?>/arrows/arrows-<?php echo $params->arrows->type;?>.png);
			overflow: hidden;
			z-index: 9999;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_arrow_left {
			background-position: <?php echo $params->arrows->style->background->left;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_arrow_left:hover {
			background-position: <?php echo $params->arrows->style->background->hover->left;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_arrow_right {
			background-position: <?php echo $params->arrows->style->background->right;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_arrow_right:hover {
			background-position: <?php echo $params->arrows->style->background->hover->right;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_arrow_left.reslide_arrow_leftdn {
			background-position: <?php echo $params->arrows->style->background->left;?>;
		}

		#slider<?php echo $sliderID ;?>_container .reslide_arrow_right.reslide_arrow_leftdn {
			background-position: <?php echo $params->arrows->style->background->right;?>;
		}

		/* thumbnail  */

		.reslide-thumbnail<?php echo $sliderID;?> {
			position: absolute;
			/* size of thumbnail navigator container */
			height: 60px;
			width: <?php echo $style->width;?>px;
			z-index: 1;
		}

		#slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> > div {
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			max-width: <?php echo $style->width-10;?>px;
		}

		#slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .p {
			position: absolute;
			top: 0;
			left: 0;
			/* max-width: 62px;*/
			height: 32px;
		}

		#slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .t {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			border: none;
		}

		#slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .w, #slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .pav:hover .w {
			position: absolute;
			/*  max-width: 60px;*/
			height: 30px;
			box-sizing: border-box;
			border: #0099FF 1px solid;
		}

		#slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .pdn .w, #slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .pav .w {
			border-style: dashed;
		}

		#slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .c {
			position: absolute;
			top: 0;
			left: 0;
			/* max-width: 62px;*/
			height: 30px;
			background-color: #000;
			filter: alpha(opacity=45);
			opacity: .45;
			transition: opacity .6s;
			-moz-transition: opacity .6s;
			-webkit-transition: opacity .6s;
			-o-transition: opacity .6s;
		}

		#slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .p:hover .c, #slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .pav .c {
			filter: alpha(opacity=0);
			opacity: 0;
		}

		#slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .p:hover .c {
			transition: none;
			-moz-transition: none;
			-webkit-transition: none;
			-o-transition: none;
		}

		* html #slider<?php echo $sliderID ;?>_container .reslide-thumbnail<?php echo $sliderID;?> .w {
			width /**/: 62px;
			height /**/: 32px;
		}
	</style>
	<script>
		var c_slider<?php echo $sliderID;?>;

		init_c_slider<?php echo $sliderID;?> = function (containerId) {

			switch (reslider<?php echo $sliderID;?>["params"]["effect"]["type"]) {
				case 0:
					var reslide_effect = {
						$Duration: reslider<?php echo $sliderID;?>["params"]["effect"]["duration"],
						$Opacity: 2,
						$Brother: {$Duration: 1000, $Opacity: 2}
					};
					break;
				case 1:
					reslide_effect = {
						$Duration: reslider<?php echo $sliderID;?>["params"]["effect"]["duration"],
						x: -0.3,
						y: 0.5,
						$Zoom: 1,
						$Rotate: 0.1,
						$During: {$Left: [0.6, 0.4], $Top: [0.6, 0.4], $Rotate: [0.6, 0.4], $Zoom: [0.6, 0.4]},
						$Easing: {
							$Left: $JssorEasing$.$EaseInQuad,
							$Top: $JssorEasing$.$EaseInQuad,
							$Opacity: $JssorEasing$.$EaseLinear,
							$Rotate: $JssorEasing$.$EaseInQuad
						},
						$Opacity: 2,
						$Brother: {
							$Duration: 1000,
							$Zoom: 11,
							$Rotate: -0.5,
							$Easing: {$Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInQuad},
							$Opacity: 2,
							$Shift: 200
						}
					};
					break;
				case 2:
					reslide_effect = {
						$Duration: reslider<?php echo $sliderID;?>["params"]["effect"]["duration"],
						x: 0.25,
						$Zoom: 1.5,
						$Easing: {$Left: $JssorEasing$.$EaseInWave, $Zoom: $JssorEasing$.$EaseInSine},
						$Opacity: 2,
						$ZIndex: -10,
						$Brother: {
							$Duration: 1400,
							x: -0.25,
							$Zoom: 1.5,
							$Easing: {$Left: $JssorEasing$.$EaseInWave, $Zoom: $JssorEasing$.$EaseInSine},
							$Opacity: 2,
							$ZIndex: -10
						}
					}
					break;
				case 3:
					reslide_effect = {
						$Duration: reslider<?php echo $sliderID;?>["params"]["effect"]["duration"],
						x: 0.5,
						$Cols: 2,
						$ChessMode: {$Column: 3},
						$Easing: {$Left: $JssorEasing$.$EaseInOutCubic},
						$Opacity: 2,
						$Brother: {$Duration: 1500, $Opacity: 2}
					}
					break;
				case 4:
					reslide_effect = {
						$Duration: reslider<?php echo $sliderID;?>["params"]["effect"]["duration"],
						x: -0.1,
						y: -0.7,
						$Rotate: 0.1,
						$During: {$Left: [0.6, 0.4], $Top: [0.6, 0.4], $Rotate: [0.6, 0.4]},
						$Easing: {
							$Left: $JssorEasing$.$EaseInQuad,
							$Top: $JssorEasing$.$EaseInQuad,
							$Opacity: $JssorEasing$.$EaseLinear,
							$Rotate: $JssorEasing$.$EaseInQuad
						},
						$Opacity: 2,
						$Brother: {
							$Duration: 1000,
							x: 0.2,
							y: 0.5,
							$Rotate: -0.1,
							$Easing: {
								$Left: $JssorEasing$.$EaseInQuad,
								$Top: $JssorEasing$.$EaseInQuad,
								$Opacity: $JssorEasing$.$EaseLinear,
								$Rotate: $JssorEasing$.$EaseInQuad
							},
							$Opacity: 2
						}
					}
					break;
				case 5:
					reslide_effect = {
						$Duration: reslider<?php echo $sliderID;?>["params"]["effect"]["duration"],
						x: -1,
						y: -0.5,
						$Delay: 50,
						$Cols: 8,
						$Rows: 4,
						$Formation: $JssorSlideshowFormations$.$FormationSquare,
						$Easing: {$Left: $JssorEasing$.$EaseSwing, $Top: $JssorEasing$.$EaseInJump},
						$Assembly: 260,
						$Round: {$Top: 1.5}
					}
					break;
				case 6:
					reslide_effect = {
						$Duration: reslider<?php echo $sliderID;?>["params"]["effect"]["duration"],
						$Delay: 30,
						$Cols: 8,
						$Rows: 4,
						$Clip: 15,
						$SlideOut: true,
						$Formation: $JssorSlideshowFormations$.$FormationStraightStairs,
						$Easing: $JssorEasing$.$EaseOutQuad,
						$Assembly: 2049
					}
					break;
			}
			;


			//	reslide_effect ={$Duration:1000,$Delay:30,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:$JssorEasing$.$EaseOutQuad,$Assembly:2049}				


			var _SlideshowTransitions = [
				reslide_effect
			];
			if (!reslider<?php echo $sliderID;?>['params']['thumbnails']['positioning']) {
				var thumbnailsCount = Math.ceil(reslider<?php echo $sliderID;?>.count - 1);
			}
			else {
				var thumbnailsCount = Math.ceil(reslider<?php echo $sliderID;?>.count);
			}
			var options = {
				$AutoPlay: (reslider<?php echo $sliderID;?>["params"]["autoplay"] == 1) ? true : false,                                   //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
				$SlideDuration: 500,
				$AutoPlayInterval: reslider<?php echo $sliderID;?>["params"]["effect"]["interval"],                                 //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500

				$BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
					$Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
					$ChanceToShow: reslider<?php echo $sliderID;?>["params"]["bullets"]["show"],                               //[Required] 0 Never, 1 Mouse Over, 2 Always
					$AutoCenter: reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"],                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
					$Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
					$Rows: reslider<?php echo $sliderID;?>["params"]["bullets"]["rows"],                                      //[Optional] Specify lanes to arrange items, default value is 1
					$SpacingX: reslider<?php echo $sliderID;?>["params"]["bullets"]["s_x"],                                  //[Optional] Horizontal space between each item in pixel, default value is 0
					$SpacingY: reslider<?php echo $sliderID;?>["params"]["bullets"]["s_y"],                                  //[Optional] Vertical space between each item in pixel, default value is 0
					$Orientation: reslider<?php echo $sliderID;?>["params"]["bullets"]["orientation"]                                //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
				},
				$ArrowNavigatorOptions: {                       //[Optional] Options to specify and enable arrow navigator or not
					$Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
					$ChanceToShow: reslider<?php echo $sliderID;?>["params"]["arrows"]["show"],                               //[Required] 0 Never, 1 Mouse Over, 2 Always
					$AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
					$Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
				},
				$SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
					$Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
					$Transitions: _SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
					$TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
					$ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
				},
				$ThumbnailNavigatorOptions: {
					$Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
					$ChanceToShow: reslider<?php echo $sliderID;?>["params"]["thumbnails"]["show"],                               //[Required] 0 Never, 1 Mouse Over, 2 Always
					$ActionMode: 1,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
					$AutoCenter: 0,                                 //[Optional] Auto center thumbnail items in the thumbnail navigator container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 3
					$Rows: 1,                                      //[Optional] Specify lanes to arrange thumbnails, default value is 1
					$SpacingX: 3,                                   //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
					$SpacingY: 3,                                   //[Optional] Vertical space between each thumbnail in pixel, default value is 0
					$Cols: thumbnailsCount,                              //[Optional] Number of pieces to display, default value is 1
					$ParkingPosition: 0,                          //[Optional] The offset position to park thumbnail
					$Orientation: 1,                                //[Optional] Orientation to arrange thumbnails, 1 horizental, 2 vertical, default value is 1
					$NoDrag: false                                //[Optional] Disable drag or not, default value is false
				}

			};

			c_slider<?php echo $sliderID;?> = new $JssorSlider$(containerId, options);
		}

		jQuery(function ($) {

			(function initReslideSlider() {

				/*** ####bullets#### ***/

				switch (+reslider<?php echo $sliderID;?>["params"]["bullets"]["position"]) {
					case 0:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 0;
						var css_bullets_obj = {
							"left": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["left"],
							"top": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["top"]
						};
						var css_bullets = "left:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["left"] + ";top:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["top"];
						break;
					case 1:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 1;
						var css_bullets_obj = {
							"right": "",
							"top": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["top"],
							"left": "",
							"bottom": ""
						};
						var css_bullets = "top:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["top"];
						break;
					case 2:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 0;
						var css_bullets_obj = {
							"right": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["right"],
							"top": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["top"]
						};
						var css_bullets = "right:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["right"] + ";top:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["top"];
						break;
					case 3:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 2;
						var css_bullets_obj = {
							"right": "",
							"top": "",
							"left": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["left"],
							"bottom": ""
						};
						var css_bullets = "left:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["left"];
						break;
					case 4:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 3;
						var css_bullets_obj = {"right": "", "top": "", "left": "", "bottom": ""};
						var css_bullets = "";
						break;
					case 5:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 2;
						var css_bullets_obj = {
							"right": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["right"],
							"top": "",
							"left": "",
							"bottom": ""
						};
						var css_bullets = "right:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["right"];
						break;
					case 6:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 0;
						var css_bullets_obj = {
							"left": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["left"],
							"bottom": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["bottom"]
						};
						var css_bullets = "left:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["left"] + ";bottom:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["bottom"];
						break;
					case 7:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 1;
						var css_bullets_obj = {
							"left": "",
							"bottom": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["bottom"],
							"right": ""
						};
						var css_bullets = "bottom:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["bottom"];
						break;
					case 8:
						reslider<?php echo $sliderID;?>["params"]["bullets"]["autocenter"] = 0;
						var css_bullets_obj = {
							"left": "",
							"bottom": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["bottom"],
							"right": reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["right"]
						};
						var css_bullets = "bottom:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["bottom"] + ";right:" + reslider<?php echo $sliderID;?>["params"]["bullets"]["style"]["position"]["right"];
						break;
				}
				_reslide.find('#slider<?php echo $_slider[0]->id;?>_container', '.reslide_navigator')[0].addStyle(css_bullets);
				if (reslider<?php echo $sliderID;?>.count) {
					var thubmnailCWidth = jQuery('.reslide-thumbnail<?php echo $sliderID;?>').width();
					var thumbWidth = thubmnailCWidth / reslider<?php echo $sliderID;?>.count;
					if (reslider<?php echo $sliderID;?>['params']['thumbnails']['positioning'])
						jQuery('.reslide-thumbnail<?php echo $sliderID;?> .c,.reslide-thumbnail<?php echo $sliderID;?> .p,.reslide-thumbnail<?php echo $sliderID;?> .w').width(thumbWidth - 4);
					else {
						jQuery('.reslide-thumbnail<?php echo $sliderID;?> .c,.reslide-thumbnail<?php echo $sliderID;?> .p,.reslide-thumbnail<?php echo $sliderID;?> .w').width(58);
						jQuery('.reslide-thumbnail<?php echo $sliderID;?> .w').width(56);
						jQuery('.reslide-thumbnail<?php echo $sliderID;?> > div').css('max-width', (jQuery('.reslide-thumbnail<?php echo $sliderID;?>').width() - 20) + 'px');//width(58);
						var walk = jQuery('.reslide-thumbnail<?php echo $sliderID;?>').width() - 20;
						var walkcount = Math.floor(walk / 61) - 1;
						walk = walkcount * 61 - 3;
						jQuery('.reslide-thumbnail<?php echo $sliderID;?> > div').css('max-width', walk + 'px');
					}
				}
				jQuery('#slider<?php echo $_slider[0]->id;?>_container .reslide_slides img').css('display', 'block');

				init_c_slider<?php echo $sliderID;?>("slider<?php echo $_slider[0]->id;?>_container");

			})();
			function ScaleSlider() {
				var parentWidth = $('#slider<?php echo $_slider[0]->id;?>_container').parent().width();
				jQuery('#slider<?php echo $_slider[0]->id;?>_container').css('display', 'block');


				if (parentWidth) {

					if (parentWidth < reslider<?php echo $sliderID;?>['style']['width']) {
						c_slider<?php echo $sliderID;?>.$ScaleWidth(parentWidth);
						jQuery('#slider<?php echo $_slider[0]->id;?>_container > div').css('overflow', 'hidden');

					} else {
						c_slider<?php echo $sliderID;?>.$ScaleWidth(reslider<?php echo $sliderID;?>['style']['width']);
					}
				}
				else
					window.setTimeout(ScaleSlider, 10);
			}

			$(window).bind("load", ScaleSlider);
			$(window).bind("resize", ScaleSlider);
			$(window).bind("orientationchange", ScaleSlider);

			//responsive code end

		})
	</script>
	<?php
	return ob_get_clean();
}
