jQuery(document).ready(function() {
	jQuery("span.hidden-image-tiles-gallery-id").each(function() {
		var gallery_id = jQuery(this).html();
		var lightbox_caption_enabled = jQuery("#maxgallery-" + gallery_id + " span.hidden-image-tiles-lightbox-caption-enabled").html();
		var lightbox_caption_position = jQuery("#maxgallery-" + gallery_id + " span.hidden-image-tiles-lightbox-caption-position").html();

		if (lightbox_caption_enabled == "on") {
			if (lightbox_caption_position == "bottom") {
				jQuery("#maxgallery-" + gallery_id + " .mg-thumbs a").fancybox({ "titleShow": true, "titlePosition": "over" });
			}
			
			if (lightbox_caption_position == "below") {
				jQuery("#maxgallery-" + gallery_id + " .mg-thumbs a").fancybox({ "titleShow": true, "titlePosition": "inside" });
			}
		}
		else {
			jQuery("#maxgallery-" + gallery_id + " .mg-thumbs a").fancybox({ "titleShow": false });
		}
	});
});