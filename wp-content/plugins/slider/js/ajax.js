
(function( $ ) {
 $(function() {
	function reslide_loading() {
		var popup = jQuery('#reslide_loading_overlay');
		if(arguments[0] === false)
			popup.css('display','none');
		else 
			popup.css('display','block');
	};	
	jQuery('.reslide_save_all').on('click',function(e){
		jQuery("#save_slider").click();
		if(!_reslide._('.reslideitem').length) {
			alert('Firstly add slides in your slider!');
			return false;
		}		
		jQuery('#reslide_preview').click();
		getSliderParams('custom');
		getSliderMainOptions();
		getSliderParams();
		getSliderStyles();
		var data = {
			'action': 'reslide_actions',
			'reslide_do' : 'reslide_save_all',
			'nonce' : reslide_ajax_object.nonce
		}
		var allData = _reslide.parseJSON(reslider);
		var data = Object.assign(allData,data);
		//console.log(data);
		$.ajax({url: reslide_ajax_object.ajax_url,data:data, method:'POST',  beforeSend: function(){
	   reslide_loading();
   },
   complete: function(){


   }, success: function(result){

	   	 // reslide_loading(false);


	}});
		return false;
	
	
	});
	
	/***  add images on slider ***/
	
	
	jQuery('#save_slider').on('click',function(e){
		if(!_reslide._('.reslideitem').length) {
			return false;
		}		
		getSlidesInput();		
		
		var data = {
			'action': 'reslide_actions',
			'nonce' : reslide_ajax_object.nonce,
			'reslide_do' : 'reslide_save_images',
			'id' : reslider.id,
			'existitems' : getExistImagesId(),
			'slides' : reslider['slides']		
		}
	//	console.log(data.slides);
		var allImages = {'images':(getAddedImages())};
		var data = Object.assign(allImages,data);
		$.ajax({url: reslide_ajax_object.ajax_url,data:(data), method:'POST',  beforeSend: function(){
	   	  reslide_loading();

   },
   complete: function(){
		reslide_loading(false);
   }, success: function(result){
			var newresult = JSON.parse(result);
			if(newresult.error) {
				alert(newresult.error);
				return false;
			}
			reslider.slides = {};
			var result = JSON.parse(result);
		
			var appendHTML = '',published = ' value="0" ';
			var i = 0,j = 0;
			for(var res in result) {
				result[res] = JSON.parse(result[res]);
			}
			reslider['slides'] = result;
			
			result = reslider['slides'];
			
			for(var res in result) {
				i++;		
				if(result[res]['published'] == '1') {
					j++;
				};
				var html ;
				reslider['slides'][res] = result[res];
				html = ['<li id="reslideitem_'+result[res]["id"]+'" class="reslideitem">',				
									'<div class="reslideitem-img-container">',
										'<a class="edit" href="?page=reslider&amp;task=editslide&amp;slideid='+result[res]["id"]+'&amp;id='+reslider.id+'">',
											'<img src="'+result[res]["url"]+'">',
											'<span class="edit-image"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>',																		
											'<span class="title">'+result[res]["title"]+'</span>',
										'</a>',
										'<div class="reslideitem-properties">',
											'<b><a href="#" class="quick_edit" data-slide-id="'+result[res]["id"]+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>Quick Edit</span></a></b>',
											
											'<b><a href="#" class="reslide_remove_image" data-slide-id="'+result[res]["id"]+'"><i class="fa fa-remove" aria-hidden="true"></i><span>Remove</span></a></b>',
											'<b><label href="#" class="reslide_on_off_image"><input value="'+result[res]["published"]+'"'+((parseInt(result[res]["published"]))&&' checked')+' data-slide-id="'+result[res]["id"]+'" class="slide-checkbox" type="checkbox"><span>Public</span></label></b>',
										'<div>',
									'</div>',
									
								'</div>',
									'<form class="reslide-nodisplay">',
									'<input type="text" class="reslideitem-edit-title" value="'+result[res]["title"]+'">',
										'<textarea class="reslideitem-edit-description">'+result[res]["description"]+'</textarea>',
										'<input type="hidden" class="reslideitem-edit-type" value="">',
										'<input type="hidden" class="reslideitem-edit-url" value="'+result[res]["url"]+'">',
										'<input type="hidden" class="reslideitem-ordering" value="'+result[res]["ordering"]+'">',
									'</form>',				
								'</div>',
				'</li>'].join("");				
				
				appendHTML += html;

				
			};
			jQuery('#reslide_slider_images_list .reslideitem.add').remove();
			jQuery('#reslide_slider_images_list').html('');			
			jQuery('#reslide_slider_images_list').prepend(appendHTML);

			reslider.length = i;
			reslider.count = j;
			
	
		}});
		return false;
	
	
	});	
	
	
	jQuery('#save_custom_slide').on('click',function(e){
		var slide = 'slide'+getparamsFromUrl('slideid',location.href);

		getSlideParams(slide);
		var data = {
			'action': 'reslide_actions',
			'nonce' : reslide_ajax_object.nonce,
			'reslide_do' : 'reslide_save_image',
			'id' : reslider.id,
			'custom' : _reslide.parseJSON(reslider['slides'][slide])['custom'],
			'title' : reslider['slides'][slide]['title'],
			'description' : reslider['slides'][slide]['description'],
			'slide' : 	getparamsFromUrl('slideid',location.href)
		}
	//	var allData = reslider;
		$.ajax({url: reslide_ajax_object.ajax_url,data:(data), method:'POST',  beforeSend: function(){
		 reslide_loading();

   },
   complete: function(){

   }, success: function(result){
			var newresult = JSON.parse(result);
			if(newresult.error) {
				alert(newresult.error);
				return false;
			}	   
			reslide_loading(false);

		}});
		return false;
	
	
	});	
	
	/***  remove images from slider ***/

	jQuery('#reslide_slider_images_list').on('click','.reslide_remove_image',function(e){
		var t = confirm("Approve Image Remove action...");
			if(!t)
					return false;		
		var slideid = jQuery(this).attr('data-slide-id');
		var data = {
			'action': 'reslide_actions',
			'nonce' : reslide_ajax_object.nonce,
			'reslide_do' : 'reslide_remove_image',
			'id' : reslider.id,
			'slide' : slideid	
		}
	//	var allData = reslider;
		$.ajax({url: reslide_ajax_object.ajax_url,data:data, method:'POST',  beforeSend: function(){
			reslide_loading();
   },
   complete: function(){
		reslide_loading(false);

     // Handle the complete event
   }, success: function(result){
			var newresult = JSON.parse(result);
			if(newresult.error) {
				alert(newresult.error);
				return false;
			}
			jQuery('#reslideitem_'+result).remove();
			if(!jQuery('#reslide_slider_images_list .reslideitem').length)
				jQuery('#reslide_slider_images_list .noimage').show();			
			delete reslider['slides']['slide'+result];
			reslider.length--;
		}});
		return false;
	
	
	});	
	jQuery('#reslide_slider_images_list').on('change','.slide-checkbox',function(e){
		(jQuery(this).attr('checked'))?(jQuery(this).val(1)):(jQuery(this).val(0));
		
		function AllSlidesUnPublished() {
			var sumPublishSlides = 0;
				jQuery('.slide-checkbox').each(function(){
				if(parseInt(jQuery(this).val()))sumPublishSlides++;
				});
			return 	sumPublishSlides;
		} 
		if(!AllSlidesUnPublished()) {
			jQuery(this).attr('checked','checked');
			alert('Slider must contain minimum one published slide...');
			reslider.count = 1;
			return false;
		}
		reslider.count = AllSlidesUnPublished();	
		var slideid = jQuery(this).attr('data-slide-id');
		var published = (jQuery(this).val());
		var data = {
			'action': 'reslide_actions',
			'nonce' : reslide_ajax_object.nonce,
			'reslide_do' : 'reslide_on_image',
			'id' : reslider.id,
			'slide' : slideid,
			'published' : published	
		}
		$.ajax({url: reslide_ajax_object.ajax_url,data:data, method:'POST',  beforeSend: function(){
			reslide_loading();
   },
   complete: function(){
		reslide_loading(false);
   }, success: function(result){
			var newresult = JSON.parse(result);
			if(newresult.error) {
				alert(newresult.error);
				return false;
			}	   
			 reslider['slides']['slide'+result]['published'] = +published;
		}});
		return false;
	
	
	});		

})
})( jQuery );		
