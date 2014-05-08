<?php
class MaxGalleriaShortcode {
	public function __construct() {
		add_shortcode('maxgallery', array($this, 'maxgallery_shortcode'));
	}
	
	public function maxgallery_shortcode($atts) {	
		extract(shortcode_atts(array(
			'id' => '',
			'name' => ''
		), $atts));
		
		$gallery_id = sanitize_text_field("{$id}");
		$gallery_name = sanitize_text_field("{$name}");
		
		$output = '';
		$gallery = null;
		
		if ($gallery_id != '' && $gallery_name != '') {
			// If both given, the id wins
			$gallery = get_post($gallery_id);
		}

		if ($gallery_id != '' && $gallery_name == '') {
			// Get the gallery by id
			$gallery = get_post($gallery_id);
		}
		
		if ($gallery_id == '' && $gallery_name != '') {
			// Get the gallery by name
			$query = new WP_Query(array('name' => $gallery_name, 'post_type' => MAXGALLERIA_POST_TYPE));
			$gallery = $query->get_queried_object();
		}
		
		if (isset($gallery) && $gallery->post_status == 'publish') {
			$args = array(
				'post_parent' => $gallery->ID,
				'post_type' => 'attachment',
				'orderby' => 'menu_order',
				'order' => 'asc',
				'numberposts' => -1 // All of them
			);

			$attachments = get_posts($args);
			
			if (count($attachments) > 0) {
				$options = new MaxGalleryOptions($gallery->ID);

				global $maxgalleria;
				$templates = $maxgalleria->get_template_addons();
				
				foreach ($templates as $template) {
					if ($template['key'] == $options->get_template()) {
						$output = call_user_func($template['output'], $gallery, $attachments);
					}
				}
			}
		}
		
		return $output;
	}
}
?>