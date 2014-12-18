jQuery(document).ready(function() {
	jQuery("span.hidden-video-tiles-gallery-id").each(function() {
		var gallery_id = jQuery(this).html();
		var thumb_click = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-thumb-click").html();
		
		if (thumb_click == "lightbox") {
			jQuery("#maxgallery-" + gallery_id + ".mg-video-tiles .mg-thumbs a").click(function() {
				var thumb_id = jQuery(this).data("video-thumb-id");

				jQuery("#maxgallery-" + gallery_id + ".mg-video-tiles .mg-videos div").each(function() {			
					if (jQuery(this).data("video-id") == thumb_id) {
						var iframe_width = parseInt(jQuery(this).find("iframe").attr("width"));
						var iframe_height = parseInt(jQuery(this).find("iframe").attr("height"));
						
						// Add 30px to ensure enough padding around iframe
						iframe_width += 30;
						iframe_height += 30;
						
						jQuery(this).modal({
							minWidth: iframe_width,
							minHeight: iframe_height,
							overlayClose: true,
							autoResize: true
						});
						
						jQuery("#simplemodal-container").css("width", iframe_width);
						jQuery("#simplemodal-container").css("height", iframe_height);
					}
				});
				
				return false;
			});
		}
	});
});