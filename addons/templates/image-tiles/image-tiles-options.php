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
	public $content_positions = array();
  public $easing_types = array();
  public $zoom_durations = array();
  public $overflow_y_settings = array();
  public $sort_orders = array();
	
	public function __construct($post_id = 0) {
		parent::__construct($post_id);
		
		$this->caption_positions = array(
			'below' => __('Below Image', 'maxgalleria'),
			'bottom' => __('Bottom of Image', 'maxgalleria')
		);
    
		$this->content_positions = array(
			'auto' => __('Auto', 'maxgalleria'),
			'true' => __('On', 'maxgalleria'),
			'false' => __('Off', 'maxgalleria')
		);
    
		$this->easing_types = array(
			'ease-in-out' => __('ease-in-out', 'maxgalleria'),
			'ease-in' => __('ease-in', 'maxgalleria'),
			'ease-out' => __('ease-out', 'maxgalleria'),
			'ease' => __('ease', 'maxgalleria'),
			'linear' => __('linear', 'maxgalleria')
		);
    
		$this->zoom_durations = array(
			100 => 100,
			200 => 200,
			300 => 300,
			400 => 400,
			500 => 500,
			600 => 600,
			700 => 700,
			800 => 800,
			900 => 900,
			1000 => 1000
		);    
    
		$this->overflow_y_settings = array(
			'auto' => __('Auto', 'maxgalleria'),
			'scroll' => __('Scroll', 'maxgalleria'),
			'hidden' => __('Hidden', 'maxgalleria')
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
			'attachment_image_source' => __('Original Image', 'maxgalleria'),
			'no_link' => __('No Link', 'maxgalleria')
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
    
    $this->sort_orders = array(
			'asc' => __('Ascending ', 'maxgalleria'),
			'desc' => __('Descending', 'maxgalleria')        
    );
	}
	
	public $images_per_page_key = 'maxgallery_images_per_page';
	public $images_per_page_default = '';
	public $images_per_page_default_key = 'maxgallery_images_per_page';
	public $lightbox_caption_enabled_default = '';
	public $lightbox_caption_enabled_default_key = 'maxgallery_lightbox_caption_enabled_image_tiles_default';
	public $lightbox_caption_enabled_key = 'maxgallery_lightbox_caption_enabled';
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
  
	public $lazy_load_enabled_default = '';
	public $lazy_load_enabled_default_key = 'maxgallery_lazy_load_enabled_image_tiles_default';
	public $lazy_load_enabled_key = 'maxgallery_lazy_load_enabled';
  
	public $lazy_load_threshold_default = '';
	public $lazy_load_threshold_default_key = 'maxgallery_lazy_load_threshold_image_tiles_default';
	public $lazy_load_threshold_key = 'maxgallery_lazy_load_threshold';

	public $vertical_fit_enabled_default = '';
	public $vertical_fit_enabled_default_key = 'maxgallery_vertical_fit_enabled_image_tiles_default';
	public $vertical_fit_enabled_key = 'maxgallery_vertical_fit_enabled';
  
	public $content_click_close_enabled_default = '';
	public $content_click_close_enabled_default_key = 'maxgallery_content_click_close_enabled_image_tiles_default';
	public $content_click_close_enabled_key = 'maxgallery_content_click_close_enabled';
    
	public $bg_click_close_enabled_default = '';
	public $bg_click_close_enabled_default_key = 'maxgallery_bg_click_close_enabled_image_tiles_default';
	public $bg_click_close_enabled_key = 'maxgallery_bg_click_close_enabled';
  
	public $close_btn_inside_enabled_default = '';
	public $close_btn_inside_enabled_default_key = 'maxgallery_close_btn_inside_enabled_image_tiles_default';
	public $close_btn_inside_enabled_key = 'maxgallery_close_btn_inside_enabled';  
  
	public $hide_close_btn_enabled_default = '';
	public $hide_close_btn_enabled_default_key = 'maxgallery_hide_close_btn_enabled_image_tiles_default';
	public $hide_close_btn_enabled_key = 'maxgallery_hide_close_btn_enabled';
  
	public $escape_key_enabled_default = '';
	public $escape_key_enabled_default_key = 'maxgallery_escape_key_enabled_image_tiles_default';
	public $escape_key_enabled_key = 'maxgallery_escape_key_enabled';
  
	public $align_top_enabled_default = '';
	public $align_top_enabled_default_key = 'maxgallery_align_top_enabled_image_tiles_default';
	public $align_top_enabled_key = 'maxgallery_align_top_enabled';
  
	public $fixed_content_position_default = 'auto';
	public $fixed_content_position_default_key = 'maxgallery_fixed_content_position_enabled_image_tiles_default';
	public $fixed_content_position_key = 'maxgallery_fixed_content_position_enabled';
  
	public $zoom_enabled_default = '';
	public $zoom_enabled_default_key = 'maxgallery_zoom_enabled_image_tiles_default';
	public $zoom_enabled_key = 'maxgallery_zoom_enabled';
  
	public $main_class_default = '';
	public $main_class_default_key = 'maxgallery_main_class_image_tiles_default';
	public $main_class_key = 'maxgallery_main_class';
  
	public $easing_type_default = 'ease-in-out';
	public $easing_type_default_key = 'maxgallery_easing_type_image_tiles_default';
	public $easing_type_key = 'maxgallery_easing_type';
  
	public $zoom_duration_default = '300';
	public $zoom_duration_default_key = 'maxgallery_zoom_duration_image_tiles_default';
	public $zoom_duration_key = 'maxgallery_zoom_duration';
  
	public $overflow_y_default = 'auto';
	public $overflow_y_default_key = 'maxgallery_overflow_y_image_tiles_default';
	public $overflow_y_key = 'maxgallery_overflow_y';
  
	public $retina_enabled_default = '';
	public $retina_enabled_default_key = 'maxgallery_retina_enabled_image_tiles_default';
	public $retina_enabled_key = 'maxgallery_retina_enabled';
  
	public $removal_delay_default = '0';
	public $removal_delay_default_key = 'maxgallery_removal_delay_image_tiles_default';
	public $removal_delay_key = 'maxgallery_removal_delay';

	public $gallery_enabled_default = '';
	public $gallery_enabled_default_key = 'maxgallery_gallery_enabled_image_tiles_default';
	public $gallery_enabled_key = 'maxgallery_gallery_enabled';
  
	public $navigate_by_img_click_enabled_default = '';
	public $navigate_by_img_click_enabled_default_key = 'maxgallery_navigate_by_img_click_enabled_image_tiles_default';
	public $navigate_by_img_click_enabled_key = 'maxgallery_navigate_by_img_click_enabled';

	public $arrow_markup_default = "<button title='%title%' type='button' class='mfp-arrow mfp-arrow-%dir%'></button>";
	public $arrow_markup_default_key = 'maxgallery_arrow_markup_image_tiles_default';
	public $arrow_markup_key = 'maxgallery_arrow_markup';
  
	public $prev_button_title_default = "Previous (Left arrow key)";
	public $prev_button_title_default_key = 'maxgallery_prev_button_title_image_tiles_default';
	public $prev_button_title_key = 'maxgallery_prev_button_title';
  
	public $next_button_title_default = "Next (Right arrow key)";
	public $next_button_title_default_key = 'maxgallery_next_button_title_image_tiles_default';
	public $next_button_title_key = 'maxgallery_next_button_title';
  
	public $counter_markup_default = "<div class='mfp-counter'>%curr% of %total%</div>";
	public $counter_markup_default_key = 'maxgallery_counter_markup_image_tiles_default';
	public $counter_markup_key = 'maxgallery_counter_markup';
  
	public $sort_order_default = 'asc';
	public $sort_order_default_key = 'maxgallery_sort_order_image_tiles_default';
	public $sort_order_key = 'maxgallery_sort_order_image_tiles';
  
  public function get_sort_order() {
		$value = $this->get_post_meta($this->sort_order_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_sort_order_default();
		}
		
		return $value;
	}
	
	public function get_sort_order_default() {
		return get_option($this->sort_order_default_key, $this->sort_order_default);
	}
  
	public function get_lazy_load_threshold() {
		$value = $this->get_post_meta($this->lazy_load_threshold_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_lazy_load_threshold_default();
		}
		
		return $value;
	}
	
	public function get_lazy_load_threshold_default() {
		return get_option($this->lazy_load_threshold_default_key, $this->lazy_load_threshold_default);
	}
  
	public function get_lazy_load_enabled() {
		$value = $this->get_post_meta($this->lazy_load_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_lazy_load_enabled_default();
		}
		
		return $value;
	}
	
	public function get_lazy_load_enabled_default() {
		return get_option($this->lazy_load_enabled_default_key, $this->lazy_load_enabled_default);
	}

	public function get_counter_markup() {
		$value = $this->get_post_meta($this->counter_markup_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_counter_markup_default();
		}
		
		return $value;
	}
	
	public function get_counter_markup_default() {
		return get_option($this->counter_markup_default_key, $this->counter_markup_default);
	}
  
	public function get_next_button_title() {
		$value = $this->get_post_meta($this->next_button_title_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_next_button_title_default();
		}
		
		return $value;
	}
	
	public function get_next_button_title_default() {
		return get_option($this->next_button_title_default_key, $this->next_button_title_default);
	}

	public function get_prev_button_title() {
		$value = $this->get_post_meta($this->prev_button_title_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_prev_button_title_default();
		}
		
		return $value;
	}
	
	public function get_prev_button_title_default() {
		return get_option($this->prev_button_title_default_key, $this->prev_button_title_default);
	}
  
	public function get_arrow_markup() {
		$value = $this->get_post_meta($this->arrow_markup_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_arrow_markup_default();
		}
		
		return $value;
	}
	
	public function get_arrow_markup_default() {
		return get_option($this->arrow_markup_default_key, $this->arrow_markup_default);
	}
  
	public function get_navigate_by_img_click_enabled() {
		$value = $this->get_post_meta($this->navigate_by_img_click_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_navigate_by_img_click_enabled_default();
		}
		
		return $value;
	}
	
	public function get_navigate_by_img_click_enabled_default() {
		return get_option($this->navigate_by_img_click_enabled_default_key, $this->navigate_by_img_click_enabled_default);
	}
  
    
	public function get_gallery_enabled() {
		$value = $this->get_post_meta($this->gallery_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_gallery_enabled_default();
		}
		
		return $value;
	}
	
	public function get_gallery_enabled_default() {
		return get_option($this->gallery_enabled_default_key, $this->gallery_enabled_default);
	}
    
	public function get_removal_delay() {
		$value = $this->get_post_meta($this->removal_delay_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_removal_delay_default();
		}
		
		return $value;
	}
	
	public function get_removal_delay_default() {
		return get_option($this->removal_delay_default_key, $this->removal_delay_default);
	}
 
	public function get_retina_enabled() {
		$value = $this->get_post_meta($this->retina_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_retina_enabled_default();
		}
		
		return $value;
	}
	
	public function get_retina_enabled_default() {
		return get_option($this->retina_enabled_default_key, $this->retina_enabled_default);
	}
    
	public function get_overflow_y() {
		$value = $this->get_post_meta($this->overflow_y_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_overflow_y_default();
		}
		
		return $value;
	}
	
	public function get_overflow_y_default() {
		return get_option($this->overflow_y_default_key, $this->overflow_y_default);
	}
  
	public function get_zoom_duration() {
		$value = $this->get_post_meta($this->zoom_duration_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_zoom_duration_default();
		}
		
		return $value;
	}
	
	public function get_zoom_duration_default() {
		return get_option($this->zoom_duration_default_key, $this->zoom_duration_default);
	}
  
	public function get_easing_type() {
		$value = $this->get_post_meta($this->easing_type_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_easing_type_default();
		}
		
		return $value;
	}
	
	public function get_easing_type_default() {
		return get_option($this->easing_type_default_key, $this->easing_type_default);
	}
  
  
	public function get_main_class() {
		$value = $this->get_post_meta($this->main_class_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_main_class_default();
		}
		
		return $value;
	}
	
	public function get_main_class_default() {
		return get_option($this->main_class_default_key, $this->main_class_default);
	}
    
	public function get_zoom_enabled() {
		$value = $this->get_post_meta($this->zoom_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_zoom_enabled_default();
		}
		
		return $value;
	}
	
	public function get_zoom_enabled_default() {
		return get_option($this->zoom_enabled_default_key, $this->zoom_enabled_default);
	}
    
  public function get_fixed_content_position() {
		$value = $this->get_post_meta($this->fixed_content_position_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_fixed_content_position_default();
		}
		
		return $value;
	}
	
	public function get_fixed_content_position_default() {
		return get_option($this->fixed_content_position_default_key, $this->fixed_content_position_default);
	}

  
	public function get_align_top_enabled() {
		$value = $this->get_post_meta($this->align_top_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_align_top_enabled_default();
		}
		
		return $value;
	}
	
	public function get_align_top_enabled_default() {
		return get_option($this->align_top_enabled_default_key, $this->align_top_enabled_default);
	}
  
  
	public function get_escape_key_enabled() {
		$value = $this->get_post_meta($this->escape_key_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_escape_key_enabled_default();
		}
		
		return $value;
	}
	
	public function get_escape_key_enabled_default() {
		return get_option($this->escape_key_enabled_default_key, $this->escape_key_enabled_default);
	}
  
	public function get_hide_close_btn_enabled() {
		$value = $this->get_post_meta($this->hide_close_btn_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_hide_close_btn_enabled_default();
		}
		
		return $value;
	}
	
	public function get_hide_close_btn_enabled_default() {
		return get_option($this->hide_close_btn_enabled_default_key, $this->hide_close_btn_enabled_default);
	}
  
  
	public function get_close_btn_inside_enabled() {
		$value = $this->get_post_meta($this->close_btn_inside_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_close_btn_inside_enabled_default();
		}
		
		return $value;
	}
	
	public function get_close_btn_inside_enabled_default() {
		return get_option($this->close_btn_inside_enabled_default_key, $this->close_btn_inside_enabled_default);
	}
    
	public function get_bg_click_close_enabled() {
		$value = $this->get_post_meta($this->bg_click_close_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_bg_click_close_enabled_default();
		}
		
		return $value;
	}
	
	public function get_bg_click_close_enabled_default() {
		return get_option($this->bg_click_close_enabled_default_key, $this->bg_click_close_enabled_default);
	}  
  
	public function get_content_click_close_enabled() {
		$value = $this->get_post_meta($this->content_click_close_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_content_click_close_enabled_default();
		}
		
		return $value;
	}
	
	public function get_content_click_close_enabled_default() {
		return get_option($this->content_click_close_enabled_default_key, $this->content_click_close_enabled_default);
	}
  
  
	public function get_vertical_fit_enabled() {
		$value = $this->get_post_meta($this->vertical_fit_enabled_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_vertical_fit_enabled_default();
		}
		
		return $value;
	}
	
	public function get_vertical_fit_enabled_default() {
		return get_option($this->vertical_fit_enabled_default_key, $this->vertical_fit_enabled_default);
	}
    
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
      $this->images_per_page_key,
      $this->lazy_load_enabled_key,
      $this->lazy_load_threshold_key,
      $this->vertical_fit_enabled_key,
      $this->content_click_close_enabled_key,
      $this->bg_click_close_enabled_key,
      $this->close_btn_inside_enabled_key,
      $this->hide_close_btn_enabled_key,
      $this->escape_key_enabled_key,
      $this->align_top_enabled_key,
      $this->fixed_content_position_key,
      $this->zoom_enabled_key,
      $this->main_class_key,
      $this->easing_type_key,
      $this->zoom_duration_key,
      $this->overflow_y_key,
      $this->retina_enabled_key,
      $this->removal_delay_key,
      $this->gallery_enabled_key,
      $this->navigate_by_img_click_enabled_key,
      $this->arrow_markup_key,
      $this->prev_button_title_key,
      $this->next_button_title_key,
      $this->counter_markup_key,  
      $this->sort_order_key
		);
	}
}
?>