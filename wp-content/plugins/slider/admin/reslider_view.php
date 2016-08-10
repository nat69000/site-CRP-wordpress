<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * @param $_row
 */
function reslide_sliders_view_list( $_row ) { ?>
	<?php reslide_free_version_banner(); ?>
	<div class="reslide_sliders_list_wrapper">
		<div class="reslide_sliders_list_header">
			<div class="id"><h3>&nbsp;&nbsp;ID</h3></div>
			<div class="name"><h3>Name</h3></div>
			<div class="count"><h3>Slides Count</h3></div>
			<div class="attr"><h3>Remove</h3></div>
		</div>
		<ul id="reslide_sliders_list">
			<li>&nbsp;<a class="add-slider" title="Add new slider"
			             href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=reslider&task=addslider' ), 'reslide_addslider' ); ?>"><i
						class="fa fa-plus-circle"></i></a><i>&nbsp;&nbsp;The new slider will appear here</i></li>

			<?php
			foreach ( $_row as $rows ) { ?>

				<li>
					<div class="id">&nbsp;&nbsp;#<?php echo $rows->id; ?></div>
					<div class="name"><a
							href="<?php echo admin_url( 'admin.php?page=reslider&task=editslider&id=' . $rows->id ); ?>"><?php echo stripslashes_deep( $rows->title ); ?></a>&nbsp;
					</div>
					<div class="count"><?php echo $rows->count; ?></div>
					<div class="properties">
						<!--<a title="preview" class="preview" href="#"><i class="fa fa-caret-square-o-right"></i></a>&nbsp;
						<a  title="edit" class="edit" href="#"><i class="fa fa-pencil-square-o"></i></a>&nbsp;-->
						<a title="delete" class="delete"
						   href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=reslider&task=removeslider&id=' . $rows->id ), 'reslider_removeslider' ) ?>"><i
								class="fa fa-times"></i></a>
					</div>
				</li>

			<?php }; ?>
		</ul>
	</div>
	<?php
}

function reslide_edit_slider_view( $_row, $_id, $_slider ) {
	function deleteSpacesNewlines( $str ) {
		return preg_replace( array( '/\r/', '/\n/' ), '', $str );
	}

	$style   = json_decode( $_slider[0]->style );
	$params  = json_decode( $_slider[0]->params );
	$customs = json_decode( ( $_slider[0]->custom ) );

	$paramsJson = deleteSpacesNewlines( $_slider[0]->params );
	$styleJson  = deleteSpacesNewlines( $_slider[0]->style );
	$customJson = deleteSpacesNewlines( $_slider[0]->custom );

	$count = 0;
	foreach ( $_row as $slide ) {
		if ( $slide->published == 0 ) {
			continue;
		}
		//$customSlide = json_decode($slide->custom);
		$count ++;

	}; ?>
	<script>
		const FRONT_IMAGES = '<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES;?>';
		const _IMAGES = '<?php echo reslide_PLUGIN_PATH_IMAGES;?>';


		var reslider = {
			id: '<?php echo $_id;?>',
			name: '<?php echo $_slider[0]->title;?>',
			params: JSON.parse('<?php echo $paramsJson;?>'),
			style: JSON.parse('<?php echo $styleJson;?>'),
			custom: JSON.parse('<?php echo $customJson;?>'),
			count: parseInt('<?php echo $count;?>'),
			length: 0,

			slides: {}
		};
		<?php

		$Slidecount = 0;
		foreach ($_row as $row) {
		$Slidecount ++;
		$customSlideJson = deleteSpacesNewlines( $row->custom );
		$description = esc_js( html_entity_decode( $row->description, ENT_COMPAT, 'UTF-8' ) );
		$title = esc_js( html_entity_decode( $row->title, ENT_COMPAT, 'UTF-8' ) );
		?>
		reslider['slides']['slide' + '<?php echo $row->id;?>'] = {};
		reslider['slides']['slide' + '<?php echo $row->id;?>']['id'] = '<?php echo $row->id;?>';
		reslider.slides['slide' + '<?php echo $row->id;?>']['title'] = '<?php echo $title;?>';
		reslider.slides['slide' + '<?php echo $row->id;?>']['description'] = '<?php echo $description;?>';
		reslider.slides['slide' + '<?php echo $row->id;?>']['url'] = '<?php echo $row->thumbnail;?>';
		reslider.slides['slide' + '<?php echo $row->id;?>']['type'] = '<?php echo $row->type;?>';
		reslider.slides['slide' + '<?php echo $row->id;?>']['published'] = +'<?php echo $row->published;?>';
		reslider.slides['slide' + '<?php echo $row->id;?>']['ordering'] = +'<?php echo $row->ordering;?>';
		reslider.slides['slide' + '<?php echo $row->id;?>']['custom'] = JSON.parse('<?php echo $customSlideJson;?>');


		<?php    }?>
		reslider.length = +'<?php echo $Slidecount;?>';
	</script>
	<?php reslide_free_version_banner(); ?>
	<div class="reslide_slider_view_wrapper">
		
		<div id="reslide_slider_view"  style="position:relative;">		
			<div class="add_slide_container" style="position: relative;">
				<a id="add_image"><span>Add Image</span>
				<!--  add slider with custom type(post slider) or post or video popup 
					<span class="other">				
						<select id="slider-specialized">
							<option value="0"></option>
							<option value="1">Add video</option>
							<option value="2">Add post</option>
							<option value="3">Create post slide</option>
						</select>
					</span>				
				<!--  add slider with custom type(post slider) or post or video popup -->				
					<span class="image"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>					
				</a>
		
			</div>
			<div class="reslide_slider_images_list_wrapper">
				<ul id="reslide_slider_images_list">
					<?php if ( ! count( $_row ) ) {
						; ?>
						<li class="noimage">
					<span class="noimage-add" href="#">
						<img src="<?php echo reslide_PLUGIN_PATH_IMAGES; ?>/noimage.png">
					</span>
						</li>
						<?php
					}
					//$_row = array_reverse($_row);
					foreach ( $_row as $rows ) {
						switch ( $rows->type ) {

							case 'video': ?>

								<li id="reslideitem_<?php echo $rows->id; ?>" class="reslideitem">
									<a class="edit video"
									   href="<?php admin_url( 'admin.php?page=reslider&task=editslider&id=' . $_id ); ?>">
										<?php echo $rows->title; ?>
										<iframe src="<?php echo $rows->thumbnail; ?>" frameborder="0"
										        allowfullscreen=""></iframe>
									</a>
									&nbsp;<b>
										<a href="#" class="quick_edit" data-slide-id="<?php echo $rows->id; ?>">Quick
											Edit</a></b>
								</li>

								<?php
								break;
							default: ?>
								<li id="reslideitem_<?php echo $rows->id; ?>" class="reslideitem">
									<div class="reslideitem-img-container">
										<a class="edit"
										   href="<?php echo admin_url( 'admin.php?page=reslider&task=editslide&slideid=' . $rows->id . '&id=' . $_id ); ?>">
											<img width="200" src="<?php echo $rows->thumbnail; ?>"/>
											<span class="edit-image"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>											
											<span
												class="title"><?php echo reslide_TextSanitize( $rows->title ); ?></span>
										</a>
										<div class="reslideitem-properties">
											<b><a href="#" class="quick_edit"
											      data-slide-id="<?php echo $rows->id; ?>"><i
														class="fa fa-pencil-square-o" aria-hidden="true"></i><span>Quick Edit</span></a></b>
											</a>
											<b><a href="#" class="reslide_remove_image"
											      data-slide-id="<?php echo $rows->id; ?>"><i class="fa fa-remove"
											                                                  aria-hidden="true"></i><span>Remove</span></a></b>
											<b><label href="#" class="reslide_on_off_image"><input
														data-slide-id="<?php echo $rows->id; ?>"
														class="slide-checkbox" <?php if ( $rows->published == 1 ) {
														echo 'checked  value="1"';
													} else echo 'value="0"' ?>
														type="checkbox"/><span>Public</span></label></b>
										</div>
										<form class="reslide-nodisplay">
											<input type="text" class="reslideitem-edit-title"
											       value="<?php echo wp_unslash( $rows->title ); ?>">
											<textarea
												class="reslideitem-edit-description"><?php echo reslide_TextSanitize( $rows->description ); ?></textarea>
											<input type="hidden" class="reslideitem-edit-type"
											       value="<?php echo $rows->type; ?>">
											<input type="hidden" class="reslideitem-edit-url"
											       value="<?php echo $rows->thumbnail; ?>">
											<input type="hidden" class="reslideitem-ordering"
											       value="<?php echo $rows->ordering; ?>">
										</form>
								</li>


								<?php
								;
						}
					}; ?>
				</ul>
				<button id="save_slider">Save Slide Changes</button>
			</div>
		</div>
		<div id="reslide_slider_edit">
			<div class="header">
				<div><h3><?php echo wp_unslash( $_slider[0]->title ); ?></h3></div>
				<div class="slider-preview-options">
					<a id="reslide_preview" href="#">Preview</a>
					<a class="reslide_save_all" href="#">Save</a>
				</div>
			</div>
			<div class="settings">
				<div class="menu">
					<ul>
						<li rel="general"><a href="#" class="active">General</a></li>
						<li rel="arrows"><a href="#">Arrows</a></li>
						<li rel="thumbnails"><a href="#">Thumbnails</a></li>
						<li rel="bullets"><a href="#">Bullets</a></li>
						<li rel="shortcodes"><a href="#">Shortcode</a></li>
					</ul>
				</div>
				<div class="menu-content">
					<ul class="main-content">
						<li class="general active">


							<ul id="general-settings">
								<li class="style"><label for="reslide-name">Name:</label><input id="reslider-name"
								                                                                name="cs[name]"
								                                                                type="text"
								                                                                value="<?php echo stripslashes_deep( $_slider[0]->title ); ?>"/>
								</li>
								<li class="style"><label for="reslide-width">Width(px):</label><input id="reslide-width"
								                                                                      name="style[width]"
								                                                                      type="number"
								                                                                      value="<?php echo $style->width; ?>"/>
								</li>
								<li class="style"><label for="reslide-height">Height(px):</label><input
										id="reslide-height"
										name="style[height]"
										type="number"
										value="<?php echo $style->height; ?>"/>
								</li>
								<li style="display:none;" class="margin style"><label>Margin(px):</label>
									<div>
										<input id="reslide-margin-left" type="number" name="style[marginLeft]"
										       value="<?php echo $style->marginLeft; ?>"/>
										<input id="reslide-margin-top" type="number" name="style[marginTop]"
										       value="<?php echo $style->marginTop; ?>"/>
										<input id="reslide-margin-right" type="number" name="style[marginRight]"
										       value="<?php echo $style->marginRight; ?>"/>
										<input id="reslide-margin-bottom" type="number" name="style[marginBottom]"
										       value="<?php echo $style->marginBottom; ?>"/>
									</div>
								</li>
								<li class="params">
									<label for="reslide-autoplay">Slider Autoplay:</label><input id="reslide-autoplay"
									                                                             type="checkbox"
									                                                             name="params[autoplay]"
									                                                             value="<?php echo $params->autoplay; ?>" <?php if ( $params->autoplay ) {
										echo "checked";
									} ?> />
								</li>
								<li class="params">
									<label for="reslide-effect-type">Slider Effect:</label>
									<select id="reslide-effect-type">
										<option
											value="0" <?php echo ( $params->effect->type == 0 ) ? "selected" : ""; ?>>
											Fade
										</option>
										<option
											value="1" <?php echo ( $params->effect->type == 1 ) ? "selected" : ""; ?>>
											Rotate
										</option>
										<option
											value="2" <?php echo ( $params->effect->type == 2 ) ? "selected" : ""; ?>>
											Switch
										</option>
										<option
											value="3" <?php echo ( $params->effect->type == 3 ) ? "selected" : ""; ?>>
											Doors
										</option>
										<option
											value="4" <?php echo ( $params->effect->type == 4 ) ? "selected" : ""; ?>>
											Rotate Axis down
										</option>
										<option
											value="5" <?php echo ( $params->effect->type == 5 ) ? "selected" : ""; ?>>
											Jump in square
										</option>
										<option
											value="6" <?php echo ( $params->effect->type == 6 ) ? "selected" : ""; ?>>
											Collapse stairs
										</option>
									</select>
									<input type="hidden" name="params[effect][type]"
									       value="<?php echo $params->effect->type; ?>">
								</li>
								<li class="params">
									<label for="reslide-effect-duration">Effect Duration:</label>
									<input type="number" name="params[effect][duration]"
									       value="<?php echo $params->effect->duration; ?>">
								</li>
								<li class="params">
									<label for="reslide-effect-interval">Slide's Interval:</label>
									<input type="number" name="params[effect][interval]"
									       value="<?php echo $params->effect->interval; ?>">
								</li>
								<li class="params title">
									<label for="reslide-title-show">Title:</label><input id="reslide-title-show"
									                                                     type="checkbox"
									                                                     name="params[title][show]"
									                                                     value="<?php echo $params->title->show; ?>" <?php if ( $params->title->show ) {
										echo "checked";
									} ?> />
									<div id="reslide-title-stylings-free" class="reslide_styling"
									     style="display:inline-block;">Style <i class="fa fa-pencil-square-o"
									                                            aria-hidden="true"></i>&nbsp;<span
											class="reslide-free" style="color:red;">(PRO)&nbsp;</span></div>

									<input type="hidden" name="params[title][style][width]"
									       value="<?php echo $params->title->style->width; ?>">
									<input type="hidden" name="params[title][style][height]"
									       value="<?php echo $params->title->style->height; ?>">
									<input type="hidden" name="params[title][style][top]"
									       value="<?php echo $params->title->style->top; ?>">
									<input type="hidden" name="params[title][style][left]"
									       value="<?php echo $params->title->style->left; ?>">
								</li>
								<li class="params description">
									<label for="reslide-description-show">Description:</label><input
										id="reslide-description-show"
										type="checkbox"
										name="params[description][show]"
										value="<?php echo $params->description->show; ?>" <?php if ( $params->description->show ) {
										echo "checked";
									} ?> />
									<div id="reslide-description-stylings-free" class="reslide_styling"
									     style="display:inline-block;">Style <i class="fa fa-pencil-square-o"
									                                            aria-hidden="true"></i>&nbsp;<span
											class="reslide-free" style="color:red;">(PRO)&nbsp;</span></div>

								</li>
								<li class="params custom">
									<label for="reslide-custom">Slider Custom Element:</label>
									<select id="reslide-custom">
										<option
											value="text" <?php echo ( $params->custom->type == 'text' ) ? "selected" : ""; ?>>
											Text
										</option>
										<option
											value="button" <?php echo ( $params->custom->type == 'button' ) ? "selected" : ""; ?>>
											Button
										</option>
										<!--<option value="vimeo" <?php echo ( $params->custom->type == 'vimeo' ) ? "selected" : ""; ?>>VIMEO</option>
									<option value="youtube" <?php echo ( $params->custom->type == 'youtube' ) ? "selected" : ""; ?>>YOUTUBE</option>-->
										<option
											value="image" <?php echo ( $params->custom->type == 'image' ) ? "selected" : ""; ?>>
											Image
										</option>
									</select>
									<input type="hidden" id="reslide-custom-type" class="reslide_styling"
									       name="params[custom][type]" value="<?php echo $params->custom->type; ?>">
									<div id="reslide-custom-stylings" class="reslide_styling">Style <i
											class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
									<div id="reslide-custom-add" class="reslide_drawelement  free"
									     rel="reslide_<?php echo $params->custom->type; ?>"
									     data="<?php echo $params->custom->type; ?>" style="display:inline-block;">Add&nbsp;<span
											class="reslide-free" style="color:red;">(PRO)&nbsp;</span></div>

								</li>
							</ul>
							<div id="general-view">
								<div id="reslide-slider-construct">
									<div id="reslide-construct-vertical"></div>
									<div id="reslide-construct-horizontal"></div>
									<!--<div id="reslide-drag-element" class="reslide_drag_element">-->
									<div id="reslide-title-construct" data="title" class="reslide_construct">
										<div style="margin-left:5px;color:#565855">Title</div>
										<!--<div class="properties">
											<span class="w"><?php echo $params->title->style->width; ?></span>
											<span class="h"><?php echo $params->title->style->height; ?></span>
											</div>-->
									</div>
									<div id="reslide-description-construct" data="description"
									     class="reslide_construct">
										<div style="margin-left:5px;color:#565855">Description</div>
										<!--<div class="properties">
											<span class="w"><?php echo $params->description->style->width; ?></span>
											<span class="h"><?php echo $params->description->style->height; ?></span>
											</div>-->
									</div>
									<?php
									$button                   = - 1;
									foreach ( $customs as $custom ) {
										; ?>
										<?php
										switch ( $custom->type ) {
											case 'img':
												?>
												<img id="reslide_img<?php echo $custom->id; ?>"
												     class="reslide_img reslide_construct"
												     data="img<?php echo $custom->id; ?>"
												     src="<?php echo $custom->src; ?>"
												     alt="<?php echo $custom->alt; ?>">
												<?php
												break;
											case 'h3':
												$custom->text = str_replace( '&#39;', "'", $custom->text );
												$custom->text = str_replace( '&#34;', '"', $custom->text );
												?>
												<h3 id="reslide_h3<?php echo $custom->id; ?>"
												    class="reslide_h3 reslide_construct"
												    data="h3<?php echo $custom->id; ?>">
													<span class="reslide_construct_texter reslide_inputh3"
													      style="width: 100%; height: 100%; display: block;"><?php echo $custom->text; ?></span>
												</h3>
												<?php
												break;
											case 'button':
												$button ++;
												$custom->text = str_replace( '&#39;', "'", $custom->text );
												$custom->text = str_replace( '&#34;', '"', $custom->text );
												?>
												<button id="reslide_button<?php echo $custom->id; ?>"
												        class="reslide_button reslide_construct"
												        data="button<?php echo $custom->id; ?>">
													<span class="reslide_construct_texter reslide_inputbutton"
													      style="width: 100%; height: 100%; display: block;"><?php echo $custom->text; ?></span>
												</button>
												<?php
												break;
											case 'iframe':
												?><img class="video"
												       src="<?php echo reslide_PLUGIN_PATH_IMAGES . "/play.youtube.png"; ?>">
												<div class="properties">
													<span
														class="w"><?php echo $params->description->style->width; ?></span>
													<span
														class="h"><?php echo $params->description->style->height; ?></span>
												</div>
												<?php
												break;
										}
										?>
									<?php }
									?>
									<div id="zoom" class="sizer">
									</div>
									<a id="reslide_remove" title="Remove Element"><i class="fa fa-remove"
									                                                 aria-hidden="true"></i></a>

									<!--	</div>-->
								</div>
						</li>
						<li class="arrows">
							<ul id="arrow-settings">
								<li class="params">
									<label for="reslide-arrows-show">Show Arrows:</label>
									<form id="reslide-arrows-show">
										<div>
											<label>Always:</label>
											<input type="radio" id="ui" name="params[arrows][show]"
											       value="2" <?php if ( $params->arrows->show == '2' ) {
												echo "checked";
											} ?> >
										</div>
										<div>
											<label>On Hover:</label>
											<input type="radio" name="params[arrows][show]"
											       value="1" <?php if ( $params->arrows->show == '1' ) {
												echo "checked";
											} ?>>
										</div>
										<div>
											<label>Never:</label>
											<input type="radio" name="params[arrows][show]"
											       value="0" <?php if ( $params->arrows->show == '0' ) {
												echo "checked";
											} ?>>
										</div>
									</form>
								</li>
								<li class="params">
									<label for="reslide-arrows-background">Background:&nbsp;<span class="reslide-free"
									                                                              style="color:red;">(PRO)&nbsp;</span></label>
									<form id="reslide-arrows-background">
										<span>
										<input type="radio" id="params-arrows-background0"
										       name="params[arrows][style][background][free]" rel="0"
										       value='{"width":"60","height":"60","left":"-0px -115px","right":"-57px -57px","hover":{"left":"-0px -115px","right":"-57px -57px"}}' <?php if ( $params->arrows->type == '0' ) {
											echo "checked";
										} ?>>
										<label for="params-arrows-background0"><img
												src="<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES . '/arrows/arrows-0.png'; ?>"></label>
										</span>
										<span>
										<input type="radio" id="params-arrows-background1"
										       name="params[arrows][style][background][free]" rel="1"
										       value='{"width":"49","height":"49","left":"91px 46px","right":"-44px 1px","hover":{"left":"91px 46px","right":"-44px 1px"}}' <?php if ( $params->arrows->type == '1' ) {
											echo "checked";
										} ?>>
										<label for="params-arrows-background1"><img
												src="<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES . '/arrows/arrows-1.png'; ?>"></label><br>
										</span>
										<span>
										<input type="radio" id="params-arrows-background2"
										       name="params[arrows][style][background][free]" rel="2"
										       value='{"width":"48","height":"48","left":"0px -96px","right":"48px 0px","hover":{"left":"0px -48px","right":"48px -48px"}}' <?php if ( $params->arrows->type == '2' ) {
											echo "checked";
										} ?>>
										<label for="params-arrows-background2"><img
												src="<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES . '/arrows/arrows-2.png'; ?>"></label>
										</span>
										<!--<span>
										<input type="radio" id="params-arrows-background3" name="params[arrows][style][background]" rel="2"  value='{"width":"48","height":"49","left":"0px -95px","right":"47px -95px"}'  <?php if ( $params->arrows->type == '2' ) {
											echo "checked";
										} ?>>
										<label for="params-arrows-background3"><img src="<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES . '/arrows/arrows-3.png'; ?>"></label>
										<input type="hidden" id="params-arrows-type" name="params[arrows][type]" value="<?php echo $params->arrows->type; ?>">
										</span>-->
										<span>
										<input type="radio" id="params-arrows-background4"
										       name="params[arrows][style][background][free]" rel="3"
										       value='{"width":"60","height":"60","left":"0px -60px","right":"60px -60px","hover":{"left":"0px -120px","right":"60px -120px"}}' <?php if ( $params->arrows->type == '3' ) {
											echo "checked";
										} ?>>
										<label for="params-arrows-background4"><img
												src="<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES . '/arrows/arrows-3.png'; ?>"></label>
										</span>
										<span>
										<input type="radio" id="params-arrows-background5"
										       name="params[arrows][style][background][free]" rel="4"
										       value='{"width":"60","height":"60","left":"0px -60px","right":"60px -60px"}' <?php if ( $params->arrows->type == '4' ) {
											echo "checked";
										} ?>>
										<label for="params-arrows-background5"><img
												src="<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES . '/arrows/arrows-4.png'; ?>"></label>
										<span>		
										<input type="hidden" id="params-arrows-type" name="params[arrows][type][free]"
										       value="<?php echo $params->arrows->type; ?>">

									</form>
								</li>
							</ul>
						</li>
						<li class="thumbnails">
							<ul id="thumbnail-settings">
								<li class="params">
									<label for="reslide-thumbnails-show">Show thumbnails:<span class="reslide-free"
									                                                           style="color:red;">&nbsp;(PRO)</span></label>
									<form id="reslide-thumbnails-show-free">
										<div>
											<label>Always:</label>
											<input type="radio" name="params[thumbnails][show]"
											       value="0" <?php if ( $params->thumbnails->show == '2' ) {
												echo "checked";
											} ?> >
										</div>
										<div>
											<label>Hover:</label>
											<input type="radio" name="params[thumbnails][show]"
											       value="0" <?php if ( $params->thumbnails->show == '1' ) {
												echo "checked";
											} ?>>
										</div>
										<div>
											<label>Never:</label>
											<input type="radio" name="params[thumbnails][show]"
											       value="0" <?php if ( $params->thumbnails->show == '0' ) {
												echo "checked";
											} ?>>
										</div>
									</form>
								</li>
								<li class="params">
									<label for="reslide-thumbnails-positioning">Positioning:<span class="reslide-free"
									                                                              style="color:red;">&nbsp;(PRO)</span>
									</label>
									<form id="reslide-thumbnails-positioning">
										<div>
											<label>Default:</label>
											<input type="radio" name="params[thumbnails][positioning]"
											       value="0" <?php if ( $params->thumbnails->positioning == '0' ) {
												echo "checked";
											} ?> >
										</div>
										<div>
											<label>Show all:</label>
											<input type="radio" name="params[thumbnails][positioning]"
											       value="0" <?php if ( $params->thumbnails->positioning == '1' ) {
												echo "checked";
											} ?>>
										</div>
									</form>
								</li>
							</ul>
						</li>
						<li class="bullets">
							<ul id="bullet-settings">
								<li class="params">
									<label for="reslide-bullets-show">Show bullets:<span class="reslide-free"
									                                                     style="color:red;">&nbsp;(PRO)</span></label>
									<form id="reslide-bullets-show">
										<div>
											<label>Always:</label>
											<input type="radio" name="params[bullets][show]"
											       value="0" <?php if ( $params->arrows->show == '2' ) {
												echo "checked";
											} ?> >
										</div>
										<div>
											<label>Hover:</label>
											<input type="radio" name="params[bullets][show]"
											       value="0" <?php if ( $params->arrows->show == '1' ) {
												echo "checked";
											} ?>>
										</div>
										<div>
											<label>Never:</label>
											<input type="radio" name="params[bullets][show]"
											       value="0" <?php if ( $params->arrows->show == '0' ) {
												echo "checked";
											} ?>>
										</div>
									</form>
								</li>
								<li class="params">
									<label for="reslide-bullets-position"> Position:&nbsp;<span class="reslide-free"
									                                                            style="color:red;">(PRO)&nbsp;</span></label>
									<form id="reslide-bullets-position">
										<input type="radio" id="params-bullets-position0"
										       name="params[bullets][style][position]" rel="0"
										       value='{"top": "16px","left": "10px"}' <?php if ( $params->bullets->position == '0' ) {
											echo "checked";
										} ?>>
										<input type="radio" id="params-bullets-position1"
										       name="params[bullets][style][position]" rel="1"
										       value='{"top": "16px"}' <?php if ( $params->bullets->position == '1' ) {
											echo "checked";
										} ?>>
										<input type="radio" id="params-bullets-position2"
										       name="params[bullets][style][position]" rel="2"
										       value='{"top": "16px","right": "10px"}' <?php if ( $params->bullets->position == '2' ) {
											echo "checked";
										} ?>><br>
										<input type="radio" id="params-bullets-position3"
										       name="params[bullets][style][position]" rel="3"
										       value='{"left": "10px"}' <?php if ( $params->bullets->position == '3' ) {
											echo "checked";
										} ?>>
										<input type="radio" id="params-bullets-position4"
										       name="params[bullets][style][position]" rel="4"
										       value='4' <?php if ( $params->bullets->position == '4' ) {
											echo "checked";
										} ?>>
										<input type="radio" id="params-bullets-position5"
										       name="params[bullets][style][position]" rel="5"
										       value='{"right": "10px"}' <?php if ( $params->bullets->position == '5' ) {
											echo "checked";
										} ?>><br>
										<input type="radio" id="params-bullets-position6"
										       name="params[bullets][style][position]" rel="6"
										       value='{"bottom": "16px","left": "10px"}' <?php if ( $params->bullets->position == '6' ) {
											echo "checked";
										} ?>>
										<input type="radio" id="params-bullets-position7"
										       name="params[bullets][style][position]" rel="7"
										       value='7' <?php if ( $params->bullets->position == '7' ) {
											echo "checked";
										} ?>>
										<input type="radio" id="params-bullets-position8"
										       name="params[bullets][style][position]" rel="8"
										       value='{"bottom": "16px","right": "10px"}' <?php if ( $params->bullets->position == '8' ) {
											echo "checked";
										} ?>>
										<input type="hidden" id="params-bullets-position"
										       name="params[bullets][position]"
										       value="<?php echo $params->bullets->position; ?>">
									</form>
								</li>
								<li class="params">
									<label for="reslide-bullets-background">Background:&nbsp;<span class="reslide-free"
									                                                               style="color:red;">(PRO)&nbsp;</span></label>
									<form id="reslide-bullets-background">
										<span>
										<label for="params-bullets-background-link">Color:</label>
										<input type="text" class="jscolor" id="params-bullets-background-link"
										       name="params[bullets][style][background][color][link]" rel="0"
										       value="<?php echo $params->bullets->style->background->color->link; ?>">
										</span>
										<span>
										<label for="params-bullets-background-hover">Hover:</label>
										<input type="text" class="jscolor" id="params-bullets-background-hover"
										       name="params[bullets][style][background][color][hover]" rel="0"
										       value="<?php echo $params->bullets->style->background->color->hover; ?>">
										</span>
										<!--<label for="params-bullets-background-active" >Active</label>									
										<input  type="text" class="jscolor" id="params-bullets-background-active" name="params[bullets][style][background][color][active]" rel="0" value="<?php echo $params->bullets->style->background->color->active; ?>" >-->
									</form>
								</li>
								<li class="params">
									<label for="reslide-bullets-orientation">Orientation:&nbsp;<span
											class="reslide-free"
											style="color:red;">(PRO)&nbsp;</span></label>
									<form id="reslide-bullets-orientation">
										<div>
											<label for="params-bullets-orientation-horizontal">Horizontal:</label>
											<input type="radio" id="params-bullets-orientation-horizontal"
											       name="params[bullets][orientation]" rel="0"
											       value='1' <?php if ( $params->bullets->orientation == '1' ) {
												echo "checked";
											} ?>>
										</div>
										<div>
											<label for="params-bullets-orientation-vertical">Vertical:</label>
											<input type="radio" id="params-bullets-orientation-vertical"
											       name="params[bullets][orientation]" rel="1"
											       value='2' <?php if ( $params->bullets->orientation == '2' ) {
												echo "checked";
											} ?>>
										</div>
										<div>
											<label for="params-bullets-orientation-row">Rows:</label>
											<input type="number" id="params-bullets-orientation-row"
											       name="params[bullets][rows]" rel="2"
											       value='<?php echo $params->bullets->rows; ?>'>
										</div>
									</form>
								</li>
								<li class="params">
									<label for="reslide-bullets-space">Inline Space(px):&nbsp;<span class="reslide-free"
									                                                                style="color:red;">(PRO)&nbsp;</span></label>
									<form id="reslide-bullets-space">
										<div>
											<label for="params-bullets-space-x">Horizontal:</label>
											<input type="number" id="params-bullets-space-x" name="params[bullets][s_x]"
											       rel="0" size="5" value='<?php echo $params->bullets->s_x; ?>'>
										</div>
										<div>
											<label for="params-bullets-space-y">Vertical:</label>
											<input type="number" id="params-bullets-space-y" name="params[bullets][s_y]"
											       rel="0" size="5" value='<?php echo $params->bullets->s_y; ?>'>
										</div>
									</form>
								</li>
							</ul>
						</li>
						<li class="shortcodes">
							<div class="shortcode">
								<div class="header">
									<h3>Shortcode Usage</h3>
								</div>
								<div class="usual usage">
									Copy & paste the shortcode directly into any WordPress post or page.
									<span>[R-slider id="<?php echo $_slider[0]->id; ?>"]</span>
								</div>
								<div class="php usage">
									Copy & paste this code into a template file to include the slideshow within your
									theme.
									<span>&lt;?php echo do_shortcode("[R-slider id='<?php echo $_slider[0]->id; ?>']"); ?></span>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div id="reslide_slide_edit" style="display:none;">
			<input class="title" name="title" value=""/>
			<input class="description" name="description" value=""/>
			<div class="content">
				<span id="logo">Logo</span>
				<div class="contents">

				</div>
			</div>
		</div>
		<div id="reslide_slider_preview_popup">

		</div>
		<div id="reslide_slider_preview">
			<div class="reslide_close" style="position:fixed;top: 12%;right: 6%;"><i class="fa fa-remove"
			                                                                         aria-hidden="true"></i></div>
		</div>

		<!--SLIDER-->
		<style>

			/*** title ***/

			.reslide_bullets {
				position: absolute;

			}

			.reslide_bullets div, .reslide_bullets div:hover, .reslide_bullets .av {
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

			.reslide_bullets div {
				background-color: #<?php echo $params->bullets->style->background->color->link;?>;
			}

			body .reslide_bullets div:hover {
				background-color: #<?php echo $params->bullets->style->background->color->hover;?>;
			}

			.reslide_bullets .bulletav {
				background-color: #74B8CF !important;
				border: #fff 1px solid;
			}

			.reslide_bullets .dn, .reslide_bullets .dn:hover {
				background-color: #555555;
			}

			/* arrows */

			.reslide_arrow_left, .reslide_arrow_right {
				display: block;
				position: absolute;
				/* size of arrow element */
				width: <?php echo $params->arrows->style->background->width;?>px;
				height: <?php echo $params->arrows->style->background->height;?>px;
				cursor: pointer;
				background-image: url(<?php echo reslide_PLUGIN_PATH_FRONT_IMAGES;?>/arrows/arrows-<?php echo $params->arrows->type;?>.png);
				overflow: hidden;
			}

			.reslide_arrow_left {
				background-position: <?php echo $params->arrows->style->background->left;?>;
			}

			.reslide_arrow_left:hover {
				background-position: <?php echo $params->arrows->style->background->left;?>;
			}

			.reslide_arrow_right {
				background-position: <?php echo $params->arrows->style->background->right;?>;
			}

			.reslide_arrow_right:hover {
				background-position: <?php echo $params->arrows->style->background->right;?>;
			}

			.reslide_arrow_left.reslide_arrow_leftdn {
				background-position: <?php echo $params->arrows->style->background->left;?>;
			}

			.reslide_arrow_right.reslide_arrow_rightdn {
				background-position: <?php echo $params->arrows->style->background->right;?>;
			}

			/*** title ***/
			.reslidetitle {
				box-sizing: border-box;
				padding: 1%;
				overflow: hidden;
			}

			.reslidetitle h3 {
				margin: 0;
				padding: 0;
				word-wrap: break-word;
				width: 100%;
				text-align: center;
				font-size: inherit !important;
			}


		</style>
	</div>
	<div id="reslide_slider_title_styling" class="reslide-styling main-content">
		<div class="reslide_close"><i class="fa fa-remove" aria-hidden="true"></i></div>
		<span class="popup-type" data="off"><img
				src="<?php echo reslide_PLUGIN_PATH_IMAGES . "/light_1.png"; ?>"></span>
		<form id="reslide-title-styling " class="params">
			<input type="hidden" class="width" name="params[title][style][width]" rel="px"
			       value="<?php echo $params->title->style->width; ?>">
			<input type="hidden" class="height" name="params[title][style][height]" rel="px"
			       value="<?php echo $params->title->style->height; ?>">
			<input type="hidden" class="top" name="params[title][style][top]" rel="0"
			       value="<?php echo $params->title->style->top; ?>">
			<input type="hidden" class="left" name="params[title][style][left]" rel="0"
			       value="<?php echo $params->title->style->left; ?>">
		<span class="color">		
		<label for="params-title-background-color-link">Color:</label>									
		<input type="text" class="jscolor" id="params-bullets-background-color-link"
		       name="params[title][style][background][color]" rel="#"
		       value="<?php echo $params->title->style->background->color; ?>">
		</span>
		<span class="color">
		<label for="params-title-background-color-hover">Hover Color:</label>																			
		<input type="text" class="jscolor" id="params-bullets-background-color-hover"
		       name="params[title][style][background][hover]" rel="#"
		       value="<?php echo $params->title->style->background->hover; ?>">
		</span>		
		<span class="size">	
		<label for="params-title-background-opacity">Opacity(%):</label>																			
		<input type="number" id="params-title-background-opacity" name="params[title][style][opacity]" rel="0"
		       value="<?php echo $params->title->style->opacity; ?>">
		</span>
		<span class="size">
		<label for="params-title-border-size">Border:</label>																			
		<input type="number" id="params-title-border-width" name="params[title][style][border][width]" rel="px"
		       value="<?php echo $params->title->style->border->width; ?>">
		</span>
		<span class="color">
		<label for="params-title-border-color">Border Color:</label>																			
		<input type="text" class="jscolor" id="params-title-border-color" name="params[title][style][border][color]"
		       rel="#" value="<?php echo $params->title->style->border->color; ?>">
		</span>		
		<span class="size">
		<label for="params-title-background-radius">Border Radius:</label>																			
		<input type="number" id="params-title-border-radius" name="params[title][style][border][radius]" rel="px"
		       value="<?php echo $params->title->style->border->radius; ?>">
		</span>		
		<span class="size">
		<label for="params-title-font-size">Font Size:</label>																			
		<input type="number" id="params-title-font-size" name="params[title][style][font][size]" rel="px"
		       value="<?php echo $params->title->style->font->size; ?>">
		</span>	
		<span class="color">
		<label for="params-title-font-color">Font Color:</label>																			
		<input type="text" class="jscolor" id="params-title-font-color" name="params[title][style][color]" rel="#"
		       value="<?php echo $params->title->style->color; ?>">
		</span>
		</form>
		<div class="reslide_content">
			<div class="reslide_title">
				<div class="reslide_title_child"></div>
				<span class="title">Title</span>
			</div>
		</div>
	</div>
	<div id="reslide_slider_description_styling" class="reslide-styling main-content">
		<div class="reslide_close"><i class="fa fa-remove" aria-hidden="true"></i></div>
		<span class="popup-type" data="off"><img
				src="<?php echo reslide_PLUGIN_PATH_IMAGES . "/light_1.png"; ?>"></span>
		<form id="reslide-description-styling " class="params">
			<input type="hidden" class="width" name="params[description][style][width]" rel="px"
			       value="<?php echo $params->description->style->width; ?>">
			<input type="hidden" class="height" name="params[description][style][height]" rel="px"
			       value="<?php echo $params->description->style->height; ?>">
			<input type="hidden" class="top" name="params[description][style][top]" rel="0"
			       value="<?php echo $params->description->style->top; ?>">
			<input type="hidden" class="left" name="params[description][style][left]" rel="0"
			       value="<?php echo $params->description->style->left; ?>">
		<span class="color">		
		<label for="params-description-background-color-link">Color:</label>									
		<input type="text" class="jscolor" id="params-bullets-background-color-link"
		       name="params[description][style][background][color]" rel="#"
		       value="<?php echo $params->description->style->background->color; ?>">
		</span>		
		<span class="color">
		<label for="params-description-background-color-hover">Hover Color:</label>																			
		<input type="text" class="jscolor" id="params-bullets-background-color-hover"
		       name="params[description][style][background][hover]" rel="#"
		       value="<?php echo $params->description->style->background->hover; ?>">
		</span>		
		<span class="size">	
		<label for="params-description-background-opacity">Opacity(%):</label>																			
		<input type="number" id="params-description-background-opacity" name="params[description][style][opacity]"
		       rel="0" value="<?php echo $params->description->style->opacity; ?>">
		</span>
		<span class="size">
		<label for="params-description-border-size">Border:</label>																			
		<input type="number" id="params-description-border-width" name="params[description][style][border][width]"
		       rel="px" value="<?php echo $params->description->style->border->width; ?>">
		</span>
		<span class="color">
		<label for="params-description-border-color">Border Color:</label>																			
		<input type="text" class="jscolor" id="params-description-border-color"
		       name="params[description][style][border][color]" rel="#"
		       value="<?php echo $params->description->style->border->color; ?>">
		</span>		
		<span class="size">
		<label for="params-description-background-radius">Border Radius:</label>																			
		<input type="number" id="params-description-border-radius" name="params[description][style][border][radius]"
		       rel="px" value="<?php echo $params->description->style->border->radius; ?>">
		</span>		
		<span class="size">
		<label for="params-description-font-size">Font Size:</label>																			
		<input type="number" id="params-description-font-size" name="params[description][style][font][size]" rel="px"
		       value="<?php echo $params->description->style->font->size; ?>">
		</span>	
		<span class="color">
		<label for="params-description-font-color">Font Color:</label>																			
		<input type="text" class="jscolor" id="params-description-font-color" name="params[description][style][color]"
		       rel="#" value="<?php echo $params->description->style->color; ?>">
		</span>
		</form>
		<div class="reslide_content">
			<div class="reslide_description">
				<div class="reslide_description_child"></div>
				<span class="description">description</span>
			</div>
		</div>
	</div>
	<?php
	foreach ( $customs as $custom ) {
		if ( $custom->type == "button" || $custom->type == "h3" ) {
			$text = $custom->text;
			$text = str_replace( '&#39;', "'", $custom->text );
			//	$text = str_replace( '&#34;','"',$custom->text);
			?>

			<div id="reslide_slider_<?php echo $custom->type . $custom->id; ?>_styling"
			     class="reslide-styling reslide-custom-styling main-content" style="display:none;">
				<div class="reslide_close"><i class="fa fa-remove" aria-hidden="true"></i></div>
				<span class="popup-type" data="off"><img
						src="<?php echo reslide_PLUGIN_PATH_IMAGES . "/light_1.png"; ?>"></span>
				<form id="reslide-<?php echo $custom->type; ?>-styling" class="custom">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>]" rel="0" value="{}">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][id]" rel="0"
					       value="<?php echo $custom->id; ?>">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][type]" rel="0"
					       value="<?php echo $custom->type; ?>">
					<input type="hidden" class="text" name="custom[<?php echo $custom->type . $custom->id; ?>][text]"
					       rel="0" value="<?php echo esc_attr( $custom->text ); ?>">
					<?php if ( $custom->type == 'button' ) { ?>
						<span class="size">
							<label>Button url:</label>
							<input class="link" type="text"
							       name="custom[<?php echo $custom->type . $custom->id; ?>][link]" rel="0" value="#">
							</span>
					<?php } ?>
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][style]" rel="0"
					       value="{}">
					<input type="hidden" class="width"
					       name="custom[<?php echo $custom->type . $custom->id; ?>][style][width]" rel="0"
					       value="<?php echo $custom->style->width; ?>">
					<input type="hidden" class="height"
					       name="custom[<?php echo $custom->type . $custom->id; ?>][style][height]" rel="0"
					       value="<?php echo $custom->style->height; ?>">
					<input type="hidden" class="top"
					       name="custom[<?php echo $custom->type . $custom->id; ?>][style][top]" rel="0"
					       value="<?php echo $custom->style->top; ?>">
					<input type="hidden" class="left"
					       name="custom[<?php echo $custom->type . $custom->id; ?>][style][left]" rel="0"
					       value="<?php echo $custom->style->left; ?>">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][style][background]"
					       rel="0" value="{}">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][style][border]"
					       rel="0" value="{}">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][style][font]" rel="0"
					       value="{}">
				<span class=" color">
				<label for="custom-background-color-link">Background Color:</label>									
				<input type="text" class="jscolor" id="custom-bullets-background-color-link"
				       name="custom[<?php echo $custom->type . $custom->id; ?>][style][background][color]" rel="#"
				       value="<?php echo $custom->style->background->color; ?>">
				</span class="border-width">
				<span class=" color">
				<label for="custom-background-color-hover">Hover Color:</label>																			
				<input type="text" class="jscolor" id="custom-bullets-background-color-hover"
				       name="custom[<?php echo $custom->type . $custom->id; ?>][style][background][hover]" rel="#"
				       value="<?php echo $custom->style->background->hover; ?>">
				</span>
				<span class=" color">
				<label for="custom-background-opacity">Opacity(%):</label>																			
				<input type="number" id="custom-background-opacity"
				       name="custom[<?php echo $custom->type . $custom->id; ?>][style][opacity]" rel="0"
				       value="<?php echo $custom->style->opacity; ?>">
				</span>
				<span class=" size">
				<label for="custom-border-size">Border:</label>																			
				<input type="number" id="custom-border-width"
				       name="custom[<?php echo $custom->type . $custom->id; ?>][style][border][width]" rel="px"
				       value="<?php echo $custom->style->border->width; ?>">
				</span>
				<span class="color">
				<label for="custom-border-color">Border Color:</label>																			
				<input type="text" class="jscolor" id="custom-custom-border-color"
				       name="custom[<?php echo $custom->type . $custom->id; ?>][style][border][color]" rel="#"
				       value="<?php echo $custom->style->border->color; ?>">
				</span>
				<span class=" size">
				<label for="custom-background-radius">Border Radius:</label>																			
				<input type="number" id="custom-custom-border-radius"
				       name="custom[<?php echo $custom->type . $custom->id; ?>][style][border][radius]" rel="px"
				       value="<?php echo $custom->style->border->radius; ?>">
				</span>
				<span class="size">
				<label for="custom-font-size">Font Size:</label>																			
				<input type="number" id="custom-font-size"
				       name="custom[<?php echo $custom->type . $custom->id; ?>][style][font][size]" rel="px"
				       value="<?php echo $custom->style->font->size; ?>">
				</span>
				<span class="color">
				<label for="custom-font-color">Font Color:</label>																			
				<input type="text" class="jscolor" id="custom-font-color"
				       name="custom[<?php echo $custom->type . $custom->id; ?>][style][color]" rel="#"
				       value="<?php echo $custom->style->color; ?>">
				</span>
				</form>
				<div class="reslide_content">
					<div class="reslide_<?php echo $custom->type; ?> reslide_custom"
					     style="width: <?php echo $custom->style->width; ?>; height: <?php echo $custom->style->height; ?>;">
						<div class="reslide_custom_child"></div>
						<?php if ( $custom->type == 'button' ) {
							$custom->text = str_replace( '&#39;', "'", $custom->text );
							$custom->text = str_replace( '&#34;', '"', $custom->text );
							?>
							<span class="btn"><?php echo $custom->text; ?></span>
						<?php } else if ( $custom->type == 'h3' ) {
							$custom->text = str_replace( '&#39;', "'", $custom->text );
							$custom->text = str_replace( '&#34;', '"', $custom->text );
							?>
							<span class="h3"><?php echo $custom->text; ?></span>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php

		} elseif ( $custom->type == "img" ) { ?>
			<div id="reslide_slider_<?php echo $custom->type . $custom->id; ?>_styling"
			     class="reslide-styling reslide-custom-styling main-content" style="display:none;">
				<div class="reslide_close"><i class="fa fa-remove" aria-hidden="true"></i></div>
				<span class="popup-type" data="off"><img
						src="<?php echo reslide_PLUGIN_PATH_IMAGES . "/light_1.png"; ?>"></span>
				<form id="reslide-<?php echo $custom->type; ?>-styling" class="custom">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>]" rel="0" value="{}">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][style]" rel="0"
					       value="{}">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][id]" rel="0"
					       value="<?php echo $custom->id; ?>">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][style][border]"
					       rel="0" value="{}">
					<input type="hidden" id="custom_src" name="custom[<?php echo $custom->type . $custom->id; ?>][src]"
					       rel="0" value="<?php echo $custom->src; ?>">
					<input type="hidden" id="custom_alt" name="custom[<?php echo $custom->type . $custom->id; ?>][alt]"
					       rel="0" value="<?php echo $custom->alt; ?>">
					<input type="hidden" name="custom[<?php echo $custom->type . $custom->id; ?>][type]" rel="0"
					       value="img">
					<input type="hidden" class="width"
					       name="custom[<?php echo $custom->type . $custom->id; ?>][style][width]" rel="0"
					       value="<?php echo $custom->style->width; ?>">
					<input type="hidden" class="height"
					       name="custom[<?php echo $custom->type . $custom->id; ?>][style][height]" rel="0"
					       value="<?php echo $custom->style->height; ?>">
					<input type="hidden" class="top"
					       name="custom[<?php echo $custom->type . $custom->id; ?>][style][top]" rel="0"
					       value="<?php echo $custom->style->top; ?>">
					<input type="hidden" class="left"
					       name="custom[<?php echo $custom->type . $custom->id; ?>][style][left]" rel="0"
					       value="<?php echo $custom->style->left; ?>">
			<span class=" color">
			<label for="custom-background-opacity">Opacity(%):</label>																			
			<input type="number" id="custom-background-opacity"
			       name="custom[<?php echo $custom->type . $custom->id; ?>][style][opacity]" rel="0"
			       value="<?php echo $custom->style->opacity; ?>">
			</span>
			<span class="border-width size">
			<label for="custom-custom-border-size">Border:</label>																			
			<input type="number" id="custom-custom-border-width"
			       name="custom[<?php echo $custom->type . $custom->id; ?>][style][border][width]" rel="px"
			       value="<?php echo $custom->style->border->width; ?>">
			</span>		
			<span class="border-color color">			
			<label for="custom-custom-border-color">Border Color:</label>																			
			<input type="text" class="jscolor" id="custom-custom-border-color"
			       name="custom[<?php echo $custom->type . $custom->id; ?>][style][border][color]" rel="#"
			       value="<?php echo $custom->style->border->color; ?>">
			</span>									
			<span class="border-radius size">									
			<label for="custom-custom-background-radius">Border Radius:</label>	
			<input type="number" id="custom-custom-border-radius"
			       name="custom[<?php echo $custom->type . $custom->id; ?>][style][border][radius]" rel="px"
			       value="<?php echo $custom->style->border->radius; ?>">
			</span>
				</form>
				<div class="reslide_content">
					<div class="reslide_img reslide_custom"><img class="img" src="<?php echo $custom->src; ?>"></div>
				</div>
			</div>

			<?php
		}
	} ?>
	<style>
		#reslide_slider_preview_popup {
			display: none;
			position: fixed;
			height: 100%;
			width: 100%;
			background: #000000;
			opacity: 0.7;
			top: 0;
			left: 0;
			z-index: 9998;
		}

		#reslide_slider_preview {
			padding: 40px;
			overflow-y: scroll;
			overflow: overlay;
			display: none;
			position: fixed;
			height: 80%;
			width: 90%;
			background: #f1f1f1;
			opacity: 1;
			top: 10%;
			left: 5%;
			z-index: 10000;
			box-sizing: border-box;
		}

		/*** title styling***/

		/* #reslide_slider_title_styling, #reslide_slider_description_styling,#reslide_slider_button_styling,.reslide-custom-styling {
			  display: none;
			position: fixed;
			height: 95%;
			width: 95%;
			background: #D3D3D6;
			opacity: 1;
			top: 6.5%;
			left: 2.5%;
			z-index: 10000;
		 }*/

		#reslide_slider_title_styling .reslide_content .reslide_title {
			border-width: <?php echo $params->title->style->border->width;?>px;
			border-color: #<?php echo $params->title->style->border->color;?>;
			border-radius: <?php echo $params->title->style->border->radius;?>px;

			font-size: <?php echo $params->title->style->font->size;?>;
			color: #<?php echo $params->title->style->color;?>;
			border-style: solid;
			box-sizing: border-box;
			overflow: hidden;
		}

		#reslide_slider_title_styling .reslide_content .reslide_title .reslide_title_child {
			background: #<?php echo $params->title->style->background->color;?>;
			opacity: <?php echo $params->title->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $params->title->style->opacity;?>);
		}

		#reslide_slider_description_styling .reslide_content .reslide_description {
			background: #<?php echo $params->description->style->background->color;?>;
			border-width: <?php echo $params->description->style->border->width;?>px;
			border-color: #<?php echo $params->description->style->border->color;?>;
			border-radius: <?php echo $params->description->style->border->radius;?>px;
			opacity: <?php echo $params->description->style->opacity/100;?>;
			filter: alpha(opacity=<?php echo $params->description->style->opacity;?>);
			font-size: <?php echo $params->description->style->font->size;?>;
			color: #<?php echo $params->description->style->color;?>;
			border-style: solid;
			box-sizing: border-box;
			overflow: hidden;
		}

		/*** title styling***/
		.reslide-custom-styling .reslide_content .reslide_custom .reslide_img {
			box-sizing: border-box;
			border-style: solid !important;
		}

		.reslide-custom-styling .reslide_content .reslide_custom img {
			width: 100%;
			height: 100%;
			max-width: 100%;
			max-height: 100%;
			display: block;
		}

		.reslideimg {
			overflow: hidden;
			box-sizing: border-box;
			box-sizing: border-box;
		}

		#reslide_slider_preview .reslide_content {
			position: absolute;
			background: #FBABAB;
			width: 100%;
			height: 100%;
		}

		#reslide-slider-construct {
			width: <?php echo $style->width;?>px;
			height: <?php echo $style->height;?>px;
			position: relative;
			background-size: 100% 100%;
			background-repeat: no-repeat;
			overflow: hidden;
			background: rgba(223, 223, 223, 0.36);
			background-size: 100% 100%;
			background-repeat: no-repeat;
			box-sizing: border-box;
			-moz-box-shadow: inset 0 0 1px #000000;
			-webkit-box-shadow: inset 0 0 1px #000000;
			box-shadow: inset 0 0 1px #000000;

		}

		.reslide_construct {
			max-width: <?php echo $style->width;?>px;
			max-height: <?php echo $style->height;?>px;
			position: absolute;
			width: 100px;
			height: 50px;
			margin: 0;
			padding: 0;
			word-wrap: break-word;
			background: green;
			display: inline-block;
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			cursor: move;
		}

		img.reslide_construct {
			width: 100px;
			height: auto;
		}

		#reslide-title-construct {
			position: absolute;
			min-width: 50px;
			width: <?php echo $params->title->style->width;?>px;
			height: <?php echo $params->title->style->height;?>px;
			/*	background: #
		
		<?php echo $params->title->style->background->color;?>  ;*/
			background: transparent;
			cursor: move;
			top: <?php echo $params->title->style->top;?>;
			left: <?php echo $params->title->style->left;?>;
			/*opacity:
		
		<?php echo $params->title->style->opacity/100;?>  ;*/
			opacity: 0.9;
			color: rgb(86, 88, 85);
			filter: alpha(opacity=<?php echo $params->title->style->opacity;?>);
			border: 2px dashed #898989;
			word-wrap: break-word;
			overflow: hidden;
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			box-sizing: border-box;
		}

		#reslide-description-construct {
			position: absolute;
			min-width: 50px;
			width: <?php echo $params->description->style->width;?>px;
			height: <?php echo $params->description->style->height;?>px;
			background: #<?php echo $params->description->style->background->color;?>;
			background: transparent;

			cursor: move;
			top: <?php echo $params->description->style->top;?>;
			left: <?php echo $params->description->style->left;?>;
			/*	opacity:
		
		<?php echo $params->description->style->opacity/100;?>  ;*/
			opacity: 0.9;
			color: rgb(86, 88, 85);
			border: 2px dashed #898989;
			filter: alpha(opacity=<?php echo $params->description->style->opacity;?>);
			word-wrap: break-word;
			overflow: hidden;
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			box-sizing: border-box;
		}

		#reslide-custom-construct {
			position: absolute;
			min-width: 50px;
			cursor: move;
			word-wrap: break-word;
			overflow: hidden;
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}

		<?php	foreach ($customs as $custom) {
			switch ($custom->type) {
				case 'img':
				?>
		/*** construct conatiner ***/

		#reslide_<?php echo $custom->type.$custom->id;?> {
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			top: <?php echo $custom->style->top;?>;
			left: <?php echo $custom->style->left;?>;
		}

		#reslide_<?php echo $custom->type.$custom->id;?> img {
			width: 100%;
			height: 100%;
		}

		/*** styling conatiner ***/

		#reslide_slider_<?php echo $custom->type.$custom->id;?>_styling .reslide_content .reslide_img {
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			border-width: <?php echo $custom->style->border->width;?>px;
			border-radius: <?php echo $custom->style->border->radius;?>px;
			border-color: #<?php echo $custom->style->border->color;?>;
			border-style: solid;
			overflow: hidden;
		}

		#reslide_slider_<?php echo $custom->type.$custom->id;?>_styling .img {
			width: 100%;
			height: 100%;
			display: block;
			opacity: <?php echo ($custom->style->opacity/100)?>;
		}

		<?php 
		case 'h3':
		?>

		/*** construct conatiner ***/

		#reslide_<?php echo $custom->type.$custom->id;?> {
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			top: <?php echo $custom->style->top;?>;
			left: <?php echo $custom->style->left;?>;
			position: absolute;
			box-sizing: border-box;
		}

		#reslide_<?php echo $custom->type.$custom->id;?> h3 {
			margin: 0;
			padding: 0;
			word-wrap: break-word;
		}

		/*** styling conatiner ***/

		#reslide_slider_<?php echo $custom->type.$custom->id;?>_styling .reslide_content .reslide_h3 {
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			color: #<?php echo $custom->style->color;?>;
			font-size: <?php echo $custom->style->font->size;?>px;
			border-width: <?php echo $custom->style->border->width;?>px;
			border-radius: <?php echo $custom->style->border->radius;?>px;
			border-color: #<?php echo $custom->style->border->color;?>;
			border-style: solid;
		}

		#reslide_slider_<?php echo $custom->type.$custom->id;?>_styling .reslide_custom_child {
			background: #<?php echo $custom->style->background->color;?>;
			opacity: <?php echo ($custom->style->opacity/100)?>;
		}

		<?php 
		break;		
		case 'button':
		?>

		/*** construct conatiner ***/

		#reslide_<?php echo $custom->type.$custom->id;?> {
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			top: <?php echo $custom->style->top;?>;
			left: <?php echo $custom->style->left;?>;
			position: absolute;
		}

		#reslide_<?php echo $custom->type.$custom->id;?> button {
			width: 100%;
			height: 100%;
			display: block;
		}

		/*** styling conatiner ***/

		#reslide_slider_<?php echo $custom->type.$custom->id;?>_styling .reslide_content .reslide_button {
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			color: #<?php echo $custom->style->color;?>;
			font-size: <?php echo $custom->style->font->size;?>px;
			border-width: <?php echo $custom->style->border->width;?>px;
			border-color: #<?php echo $custom->style->border->color;?>;
			border-radius: <?php echo $custom->style->border->radius;?>px;
			border-style: solid;
		}

		#reslide_slider_<?php echo $custom->type.$custom->id;?>_styling .reslide_custom_child {
			background: #<?php echo $custom->style->background->color;?>;
			opacity: <?php echo ($custom->style->opacity/100)?>;
		}

		<?php 
		break;		
		case 'iframe':
		?>
		#reslide_<?php echo $custom->type.$custom->id;?> {
			width: <?php echo $custom->style->width;?>px;
			height: <?php echo $custom->style->height;?>px;
			top: <?php echo $custom->style->top;?>;
			left: <?php echo $custom->style->left;?>;
			position: relative;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}

		#reslide_<?php echo $custom->type.$custom->id;?> img {
			width: 40px;
			height: 20px;
			position: absolute;
			top: 50%;
			left: 50%;
			display: block;
			transform: translate(-50%, -50%);
		}

		<?php 
		break;																
		}
		?>
		<?php } ?>
		#reslide-description-construct #reslide_remove {
			opacity: 0;
		}

	</style>


	<?php
}