<?php
class MaxGalleriaImageTiles {
	public $addon_key;
	public $addon_name;
	public $addon_type;
	public $addon_subtype;
	public $addon_settings;
	public $addon_output;
	public $meta;
	
	public function __construct() {
		$this->addon_key = 'image-tiles';
		$this->addon_name = __('Image Tiles', 'maxgalleria');
		$this->addon_type = 'template';
		$this->addon_subtype = 'image';
		$this->addon_settings = MAXGALLERIA_PLUGIN_DIR . '/addons/templates/image-tiles/image-tiles-settings.php';
		$this->addon_output = array($this, 'get_output');
		
		// Just include this one, no need to instantiate the MaxGalleriaImageTilesOptions class here
		require_once 'image-tiles-options.php';
		
		// For the meta, include it and go ahead and instantiate the MaxGalleriaImageTilesMeta class
		require_once 'image-tiles-meta.php';
		$this->meta = new MaxGalleriaImageTilesMeta();
		
		// Ajax call for saving default settings
		add_action('wp_ajax_save_image_tiles_defaults', array($this, 'save_image_tiles_defaults'));
		add_action('wp_ajax_nopriv_save_image_tiles_defaults', array($this, 'save_image_tiles_defaults'));
	}
	
	public function save_image_tiles_defaults() {
		$options = new MaxGalleriaImageTilesOptions();
		
		if (isset($_POST) && check_admin_referer($options->nonce_save_image_tiles_defaults['action'], $options->nonce_save_image_tiles_defaults['name'])) {
			global $maxgalleria;
			$message = '';

			foreach ($_POST as $key => $value) {
				if ($maxgalleria->common->string_starts_with($key, 'maxgallery_')) {
					update_option($key, $value);
				}
			}
			
			$message = 'success';
			
			echo $message;
			die();
		}
	}

	public function enqueue_styles($options) {
		// Check to add lightbox styles
		if ($options->get_thumb_click() == 'lightbox') {
			$lightbox_stylesheet = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_LIGHTBOX_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/libs/fancybox/jquery.fancybox-1.3.4.css');
			wp_enqueue_style('maxgalleria-fancybox', $lightbox_stylesheet);
		}
		
		// The main styles for this template
		$main_stylesheet = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_MAIN_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/addons/templates/image-tiles/image-tiles.css');
		wp_enqueue_style('maxgalleria-image-tiles', $main_stylesheet);
		
		// Load skin style
		$skin = $options->get_skin();
		$skin_stylesheet = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_SKIN_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/addons/templates/image-tiles/skins/' . $skin . '.css', $skin);
		wp_enqueue_style('maxgalleria-image-tiles-skin-' . $skin, $skin_stylesheet);
		
		// Check to load custom styles
		if ($options->get_custom_styles_enabled() == 'on' && $options->get_custom_styles_url() != '') {
			wp_enqueue_style('maxgalleria-image-tiles-custom', $options->get_custom_styles_url());
		}
	}

	public function enqueue_scripts($options) {
		wp_enqueue_script('jquery');
		
		if ($options->get_thumb_click() == 'lightbox') {
			$lightbox_script = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_LIGHTBOX_SCRIPT, MAXGALLERIA_PLUGIN_URL . '/libs/fancybox/jquery.fancybox-1.3.4.pack.js');
			wp_enqueue_script('maxgalleria-fancybox', $lightbox_script, array('jquery'));
			
			$easing_script = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_EASING_SCRIPT, MAXGALLERIA_PLUGIN_URL . '/libs/fancybox/jquery.easing-1.3.pack.js');
			wp_enqueue_script('maxgalleria-easing', $easing_script, array('jquery'));
			
			$main_script = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_MAIN_SCRIPT, MAXGALLERIA_PLUGIN_URL . '/addons/templates/image-tiles/image-tiles.js');
			wp_enqueue_script('maxgalleria-image-tiles', $main_script, array('jquery'));
		}
		
		// Check to load custom scripts
		if ($options->get_custom_scripts_enabled() == 'on' && $options->get_custom_scripts_url() != '') {
			wp_enqueue_script('maxgalleria-image-tiles-custom', $options->get_custom_scripts_url(), array('jquery'));
		}
	}

	public function get_lightbox_image($options, $attachment) {
		global $maxgalleria;
		
		$lightbox_image = null;
		
		if ($options->get_lightbox_image_size() == 'custom') {
			$custom_width = $options->get_lightbox_image_size_custom_width();
			$custom_height = $options->get_lightbox_image_size_custom_height();
			$lightbox_image = $maxgalleria->image_gallery->resize_image($attachment, $custom_width, $custom_height, true);
		}
		else {
			$meta = wp_get_attachment_metadata($attachment->ID);
			if (is_array($meta) && array_key_exists('width', $meta) && array_key_exists('height', $meta)) {
				$lightbox_image = array('url' => $attachment->guid, 'width' => $meta['width'], 'height' => $meta['height']);
			}
			else {
				$lightbox_image = array('url' => $attachment->guid, 'width' => '', 'height' => '');
			}
		}
		
		return $lightbox_image;
	}
	
	public function get_thumb_image($options, $attachment) {
		global $maxgalleria;

		$thumb_shape = $options->get_thumb_shape();
		$thumb_columns = $options->get_thumb_columns();
		
		return $maxgalleria->image_gallery->get_thumb_image($attachment, $thumb_shape, $thumb_columns);
	}
	
	public function get_output($gallery, $attachments) {
		$options = new MaxGalleriaImageTilesOptions($gallery->ID);

		do_action(MAXGALLERIA_ACTION_IMAGE_TILES_BEFORE_ENQUEUE_STYLES, $options);
		$this->enqueue_styles($options);
		do_action(MAXGALLERIA_ACTION_IMAGE_TILES_AFTER_ENQUEUE_STYLES, $options);
		
		do_action(MAXGALLERIA_ACTION_IMAGE_TILES_BEFORE_ENQUEUE_SCRIPTS, $options);
		$this->enqueue_scripts($options);
		do_action(MAXGALLERIA_ACTION_IMAGE_TILES_AFTER_ENQUEUE_SCRIPTS, $options);
		
		$output = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_BEFORE_GALLERY_OUTPUT, '', $gallery, $attachments, $options);
		$output .= '<div id="maxgallery-' . $gallery->ID . '" class="mg-image-tiles ' . $options->get_skin() . '">';
		
		if ($options->get_description_enabled() == 'on' && $options->get_description_position() == 'above') {
			if ($options->get_description_text() != '') {
				$output .= '<p class="mg-description">' . $options->get_description_text() . '</p>';
			}
		}
		
		$output .= '	<div class="mg-thumbs ' . $options->get_thumb_columns_class() . '">';
		$output .= '		<ul>';

		foreach ($attachments as $attachment) {
			$excluded = get_post_meta($attachment->ID, 'maxgallery_attachment_image_exclude', true);
			if (!$excluded) {
				$title = $attachment->post_title;
				$caption = $attachment->post_excerpt; // Used for the thumb and lightbox captions, if enabled
				$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
				$link = get_post_meta($attachment->ID, 'maxgallery_attachment_image_link', true);
				$image_class = $options->get_thumb_image_class();
				$image_container_class = $options->get_thumb_image_container_class();
				$image_rel = $options->get_thumb_image_rel_attribute();
				
				// Default to original, full size image
				$href = $attachment->guid;
				$target = '';
				
				if ($options->get_thumb_click() == 'lightbox') {
					if ($options->get_lightbox_image_size() == 'custom') {
						$lightbox_image = $this->get_lightbox_image($options, $attachment);
						$href = $lightbox_image['url'];
					}
				}
				
				if ($options->get_thumb_click() == 'attachment_image_page') {
					$href = get_attachment_link($attachment->ID);
				}
				
				if ($options->get_thumb_click() == 'attachment_image_link') {
					if ($link != '') {
						$href = $link;
					}
				}
				
				if ($options->get_thumb_click_new_window() == 'on') {
					$target = '_blank';
				}
				
				$thumb_image = $this->get_thumb_image($options, $attachment);
				$thumb_image_element = '<img class="' . $image_class . '" src="' . $thumb_image['url'] . '" width="' . $thumb_image['width'] . '" height="' . $thumb_image['height'] . '" alt="' . $alt . '" title="' . $title . '" />';
				
				$output .= '<li>';
				$output .= "	<a href='" . $href . "' target='" . $target . "' rel='$image_rel' title='" . $caption . "'>";
				$output .= '		<div class="' . $image_container_class . '">';
				$output .= 				apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_BEFORE_THUMB, '', $options);
				$output .=				apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_THUMB, $thumb_image_element, $thumb_image, $image_class, $alt, $title);
				$output .= 				apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_AFTER_THUMB, '', $options);
				
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'bottom') {
					$output .= '		<div class="caption-bottom-container">';
					$output .= '			<p class="caption bottom">' . $caption . '</p>';
					$output .= '		</div>';
				}
				
				$output .= '		</div>';
				$output .= '	</a>';
				
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'below') {
					$output .= '	<p class="caption below">' . $caption . '</p>';
				}
				
				$output .= '</li>';
			}
		}

		$output .= '		</ul>';
		$output .= '		<div class="clear"></div>';
		
		if ($options->get_description_enabled() == 'on' && $options->get_description_position() == 'below') {
			if ($options->get_description_text() != '') {
				$output .= '<p class="mg-description">' . $options->get_description_text() . '</p>';
			}
		}

		$output .= '	</div>';
		
		// Hidden elements used by image-tiles.js
		$output .= '	<span style="display: none;" class="hidden-image-tiles-gallery-id">' . $gallery->ID . '</span>';
		$output .= '	<span style="display: none;" class="hidden-image-tiles-lightbox-caption-enabled">' . $options->get_lightbox_caption_enabled() . '</span>';
		$output .= '	<span style="display: none;" class="hidden-image-tiles-lightbox-caption-position">' . $options->get_lightbox_caption_position() . '</span>';
		$output .= '</div>';
		$output .= apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_AFTER_GALLERY_OUTPUT, '', $gallery, $attachments, $options);
		
		return apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_GALLERY_OUTPUT, $output, $gallery, $attachments, $options);
	}
}
?>