<?php
require_once MAXGALLERIA_PLUGIN_DIR . '/maxgallery-options.php';

class MaxGalleriaImageTilesOptions extends MaxGalleryOptions {
	public $nonce_save_image_tiles_defaults = array(
		'action' => 'save_image_tiles_defaults',
		'name' => 'maxgalleria_save_image_tiles_defaults'
	);

	public $caption_positions = array();
	public $lightbox_sizes = array();
	public $skins = array();
	public $thumb_clicks = array();
	public $thumb_columns = array();
	public $thumb_shapes = array();
	
	public function __construct($post_id = 0) {
		parent::__construct($post_id);
		
		$this->caption_positions = array(
			'below' => __('Below Image', 'maxgalleria'),
			'bottom' => __('Bottom of Image', 'maxgalleria')
		);

		$this->lightbox_sizes = array(
			'full' => __('Full', 'maxgalleria'),
			'custom' => __('Custom', 'maxgalleria')
		);

		$this->skins = array(
			'no-border' => __('No Border', 'maxgalleria'),
			'picture-frame' => __('Picture Frame', 'maxgalleria'),
			'portal' => __('Portal', 'maxgalleria'),
			'portal-dark' => __('Portal Dark', 'maxgalleria'),
			'standard' => __('Standard', 'maxgalleria'),
			'standard-dark' => __('Standard Dark', 'maxgalleria'),
			'tightness' => __('Tightness', 'maxgalleria'),
			'tightness-dark' => __('Tightness Dark', 'maxgalleria')
		);
		
		$this->thumb_clicks = array(
			'lightbox' => __('Lightbox Image', 'maxgalleria'),
			'attachment_image_page' => __('Image Page', 'maxgalleria'),
			'attachment_image_link' => __('Image Link', 'maxgalleria'),
			'attachment_image_source' => __('Original Image', 'maxgalleria')
		);
		
		$this->thumb_columns = array(
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4,
			5 => 5,
			6 => 6,
			7 => 7,
			8 => 8,
			9 => 9,
			10 => 10
		);
		
		$this->thumb_shapes = array(
			'landscape' => __('Landscape', 'maxgalleria'),
			'portrait' => __('Portrait', 'maxgalleria'),
			'square' => __('Square', 'maxgalleria')
		);
	}
	
	public $images_per_page_key = 'maxgallery_images_per_page';
	public $images_per_page_default = '';
	public $images_per_page_default_key = 'maxgallery_images_per_page';
	public $lightbox_caption_enabled_default = '';
	public $lightbox_caption_enabled_default_key = 'maxgallery_lightbox_caption_enabled_image_tiles_default';
	public $lightbox_caption_enabled_key = 'maxgallery_lightbox_caption_enabled';
	public $lightbox_caption_position_default = 'below';
	public $lightbox_caption_position_default_key = 'maxgallery_lightbox_caption_position_image_tiles_default';
	public $lightbox_caption_position_key = 'maxgallery_lightbox_caption_position';
	public $lightbox_image_size_custom_height_default = '';
	public $lightbox_image_size_custom_height_default_key = 'maxgallery_lightbox_image_size_custom_height_image_tiles_default';
	public $lightbox_image_size_custom_height_key = 'maxgallery_lightbox_image_size_custom_height';
	public $lightbox_image_size_custom_width_default = '';
	public $lightbox_image_size_custom_width_default_key = 'maxgallery_lightbox_image_size_custom_width_image_tiles_default';
	public $lightbox_image_size_custom_width_key = 'maxgallery_lightbox_image_size_custom_width';
	public $lightbox_image_size_default = 'full';
	public $lightbox_image_size_default_key = 'maxgallery_lightbox_image_size_image_tiles_default';
	public $lightbox_image_size_key = 'maxgallery_lightbox_image_size';
	public $skin_default = 'standard';
	public $skin_default_key = 'maxgallery_skin_image_tiles_default';
	public $skin_key = 'maxgallery_skin';
	public $thumb_caption_enabled_default = '';
	public $thumb_caption_enabled_default_key = 'maxgallery_thumb_caption_enabled_image_tiles_default';
	public $thumb_caption_enabled_key = 'maxgallery_thumb_caption_enabled';
	public $thumb_caption_position_default = 'below';
	public $thumb_caption_position_default_key = 'maxgallery_thumb_caption_position_image_tiles_default';
	public $thumb_caption_position_key = 'maxgallery_thumb_caption_position';
	public $thumb_click_default = 'lightbox';
	public $thumb_click_default_key = 'maxgallery_thumb_click_image_tiles_default';
	public $thumb_click_key = 'maxgallery_thumb_click';
	public $thumb_click_new_window_default = '';
	public $thumb_click_new_window_key = 'maxgallery_thumb_click_new_window';
	public $thumb_columns_default = 5;
	public $thumb_columns_default_key = 'maxgallery_thumb_columns_image_tiles_default';
	public $thumb_columns_key = 'maxgallery_thumb_columns';
	public $thumb_image_class_default = '';
	public $thumb_image_class_default_key = 'maxgallery_thumb_image_class_image_tiles_default';
	public $thumb_image_class_key = 'maxgallery_thumb_image_class';
	public $thumb_image_container_class_default = '';
	public $thumb_image_container_class_default_key = 'maxgallery_thumb_image_container_class_image_tiles_default';
	public $thumb_image_container_class_key = 'maxgallery_thumb_image_container_class';
	public $thumb_image_rel_attribute_default = 'mg-rel-image-thumbs';
	public $thumb_image_rel_attribute_default_key = 'maxgallery_thumb_image_rel_attribute_image_tiles_default';
	public $thumb_image_rel_attribute_key = 'maxgallery_thumb_image_rel_attribute';
	public $thumb_shape_default = 'square';
	public $thumb_shape_default_key = 'maxgallery_thumb_shape_image_tiles_default';
	public $thumb_shape_key = 'maxgallery_thumb_shape';

	public function get_lightbox_caption_enabled() {
		$value = $this->get_post_meta($this->lightbox_caption_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_lightbox_caption_enabled_default();
		}
		
		return $value;
	}
	
	public function get_lightbox_caption_enabled_default() {
		return get_option($this->lightbox_caption_enabled_default_key, $this->lightbox_caption_enabled_default);
	}
	
	public function get_lightbox_caption_position() {
		$value = $this->get_post_meta($this->lightbox_caption_position_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_lightbox_caption_position_default();
		}
		
		return $value;
	}
	
	public function get_lightbox_caption_position_default() {
		return get_option($this->lightbox_caption_position_default_key, $this->lightbox_caption_position_default);
	}
	
	public function get_lightbox_image_size() {
		$value = $this->get_post_meta($this->lightbox_image_size_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_lightbox_image_size_default();
		}
		
		return $value;
	}
	
	public function get_lightbox_image_size_default() {
		return get_option($this->lightbox_image_size_default_key, $this->lightbox_image_size_default);
	}
	
	public function get_lightbox_image_size_custom_height() {
		$value = $this->get_post_meta($this->lightbox_image_size_custom_height_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_lightbox_image_size_custom_height_default();
		}
		
		return $value;
	}
	
	public function get_lightbox_image_size_custom_height_default() {
		return get_option($this->lightbox_image_size_custom_height_default_key, $this->lightbox_image_size_custom_height_default);
	}
  
  public function get_images_per_page() {
		$value = $this->get_post_meta($this->images_per_page_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_images_per_page_default();
		}
		
		return $value;
	}
	
	public function get_images_per_page_default() {
		return get_option($this->images_per_page_default_key, $this->images_per_page_default);
	}
  
	public function get_lightbox_image_size_custom_width() {
		$value = $this->get_post_meta($this->lightbox_image_size_custom_width_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_lightbox_image_size_custom_width_default();
		}
		
		return $value;
	}
	
	public function get_lightbox_image_size_custom_width_default() {
		return get_option($this->lightbox_image_size_custom_width_default_key, $this->lightbox_image_size_custom_width_default);
	}
	
	public function get_skin() {
		$value = $this->get_post_meta($this->skin_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_skin_default();
		}
		
		return $value;
	}
	
	public function get_skin_default() {
		return get_option($this->skin_default_key, $this->skin_default);
	}
	
	public function get_thumb_caption_enabled() {
		$value = $this->get_post_meta($this->thumb_caption_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_thumb_caption_enabled_default();
		}
		
		return $value;
	}
	
	public function get_thumb_caption_enabled_default() {
		return get_option($this->thumb_caption_enabled_default_key, $this->thumb_caption_enabled_default);
	}
	
	public function get_thumb_caption_position() {
		$value = $this->get_post_meta($this->thumb_caption_position_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_thumb_caption_position_default();
		}
		
		return $value;
	}
	
	public function get_thumb_caption_position_default() {
		return get_option($this->thumb_caption_position_default_key, $this->thumb_caption_position_default);
	}
	
	public function get_thumb_click() {
		$value = $this->get_post_meta($this->thumb_click_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_thumb_click_default();
		}
		
		return $value;
	}
	
	public function get_thumb_click_default() {
		return get_option($this->thumb_click_default_key, $this->thumb_click_default);
	}
	
	public function get_thumb_click_new_window() {
		$value = $this->get_post_meta($this->thumb_click_new_window_key);
		if ($value == '') {
			$value = $this->thumb_click_new_window_default;
		}
		
		return $value;
	}
	
	public function get_thumb_columns() {
		$value = $this->get_post_meta($this->thumb_columns_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_thumb_columns_default();
		}
		
		return $value;
	}
	
	public function get_thumb_columns_default() {
		return get_option($this->thumb_columns_default_key, $this->thumb_columns_default);
	}
	
	public function get_thumb_columns_class() {
		$value = '';
		
		$columns = $this->get_thumb_columns();
		if ($columns == 1) { $value = 'mg-onecol'; }
		if ($columns == 2) { $value = 'mg-twocol'; }
		if ($columns == 3) { $value = 'mg-threecol'; }
		if ($columns == 4) { $value = 'mg-fourcol'; }
		if ($columns == 5) { $value = 'mg-fivecol'; }
		if ($columns == 6) { $value = 'mg-sixcol'; }
		if ($columns == 7) { $value = 'mg-sevencol'; }
		if ($columns == 8) { $value = 'mg-eightcol'; }
		if ($columns == 9) { $value = 'mg-ninecol'; }
		if ($columns == 10) { $value = 'mg-tencol'; }
		
		return $value;
	}
	
	public function get_thumb_image_class() {
		$value = $this->get_post_meta($this->thumb_image_class_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_thumb_image_class_default();
		}
		
		return $value;
	}
	
	public function get_thumb_image_class_default() {
		return get_option($this->thumb_image_class_default_key, $this->thumb_image_class_default);
	}
	
	public function get_thumb_image_container_class() {
		$value = $this->get_post_meta($this->thumb_image_container_class_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_thumb_image_container_class_default();
		}
		
		return $value;
	}
	
	public function get_thumb_image_container_class_default() {
		return get_option($this->thumb_image_container_class_default_key, $this->thumb_image_container_class_default);
	}
	
	public function get_thumb_image_rel_attribute() {
		$value = $this->get_post_meta($this->thumb_image_rel_attribute_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_thumb_image_rel_attribute_default();
		}
		
		return $value;
	}
	
	public function get_thumb_image_rel_attribute_default() {
		return get_option($this->thumb_image_rel_attribute_default_key, $this->thumb_image_rel_attribute_default);
	}
	
	public function get_thumb_shape() {
		$value = $this->get_post_meta($this->thumb_shape_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_thumb_shape_default();
		}
		
		return $value;
	}
	
	public function get_thumb_shape_default() {
		return get_option($this->thumb_shape_default_key, $this->thumb_shape_default);
	}
	
	public function save_options($options = null) {
		if ($this->get_template() == 'image-tiles') {
			$options = $this->get_options();
			parent::save_options($options);
		}
	}
	
	private function get_options() {
		return array(
			$this->lightbox_caption_enabled_key,
			$this->lightbox_caption_position_key,
			$this->lightbox_image_size_custom_height_key,
			$this->lightbox_image_size_custom_width_key,
			$this->lightbox_image_size_key,
			$this->skin_key,
			$this->thumb_caption_enabled_key,
			$this->thumb_caption_position_key,
			$this->thumb_click_key,
			$this->thumb_click_new_window_key,
			$this->thumb_columns_key,
			$this->thumb_image_class_key,
			$this->thumb_image_container_class_key,
			$this->thumb_image_rel_attribute_key,
			$this->thumb_shape_key,
      		$this->images_per_page_key
		);
	}
}
?>