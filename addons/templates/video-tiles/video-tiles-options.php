<?php
require_once MAXGALLERIA_PLUGIN_DIR . '/maxgallery-options.php';

class MaxGalleriaVideoTilesOptions extends MaxGalleryOptions {
	public $nonce_save_video_tiles_defaults = array(
		'action' => 'save_video_tiles_defaults',
		'name' => 'maxgalleria_save_video_tiles_defaults'
	);
	
	public $caption_positions = array();
	public $lightbox_sizes = array();
	public $skins = array();
	public $thumb_clicks = array();
	public $thumb_columns = array();
	public $thumb_shapes = array();
	public $content_positions = array();
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
			'lightbox' => __('Video Lightbox', 'maxgalleria'),
			'video_url' => __('Video URL', 'maxgalleria')
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

	public $videos_per_page_key = 'maxgallery_videos_per_page';
	public $videos_per_page_default = '';
	public $videos_per_page_default_key = 'maxgallery_videos_per_page';
	public $skin_default = 'standard';
	public $skin_default_key = 'maxgallery_skin_video_tiles_default';
	public $skin_key = 'maxgallery_skin';
	public $thumb_caption_enabled_default = '';
	public $thumb_caption_enabled_default_key = 'maxgallery_thumb_caption_enabled_video_tiles_default';
	public $thumb_caption_enabled_key = 'maxgallery_thumb_caption_enabled';
	public $thumb_caption_position_default = 'below';
	public $thumb_caption_position_default_key = 'maxgallery_thumb_caption_position_video_tiles_default';
	public $thumb_caption_position_key = 'maxgallery_thumb_caption_position';
	public $thumb_click_default = 'lightbox';
	public $thumb_click_default_key = 'maxgallery_thumb_click_video_tiles_default';
	public $thumb_click_key = 'maxgallery_thumb_click';
	public $thumb_click_new_window_default = '';
	public $thumb_click_new_window_default_key = 'maxgallery_thumb_click_new_window_video_tiles_default';
	public $thumb_click_new_window_key = 'maxgallery_thumb_click_new_window';
	public $thumb_columns_default = 5;
	public $thumb_columns_default_key = 'maxgallery_thumb_columns_video_tiles_default';
	public $thumb_columns_key = 'maxgallery_thumb_columns';
	public $thumb_image_class_default = '';
	public $thumb_image_class_default_key = 'maxgallery_thumb_image_class_video_tiles_default';
	public $thumb_image_class_key = 'maxgallery_thumb_image_class';
	public $thumb_image_container_class_default = '';
	public $thumb_image_container_class_default_key = 'maxgallery_thumb_imagee_container_class_video_tiles_default';
	public $thumb_image_container_class_key = 'maxgallery_thumb_image_container_class';
	public $thumb_image_rel_attribute_default = 'mg-rel-video-thumbs';
	public $thumb_image_rel_attribute_default_key = 'maxgallery_thumb_image_rel_attribute_video_tiles_default';
	public $thumb_image_rel_attribute_key = 'maxgallery_thumb_image_rel_attribute';
	public $thumb_shape_default = 'square';
	public $thumb_shape_default_key = 'maxgallery_thumb_shape_video_tiles_default';
	public $thumb_shape_key = 'maxgallery_thumb_shape';
  
	public $vertical_fit_enabled_default = '';
	public $vertical_fit_enabled_default_key = 'maxgallery_vertical_fit_enabled_video_tiles_default';
	public $vertical_fit_enabled_key = 'maxgallery_vertical_fit_enabled';
  
	public $escape_key_enabled_default = '';
	public $escape_key_enabled_default_key = 'maxgallery_escape_key_enabled_video_tiles_default';
	public $escape_key_enabled_key = 'maxgallery_escape_key_enabled';
  
	public $align_top_enabled_default = '';
	public $align_top_enabled_default_key = 'maxgallery_align_top_enabled_video_tiles_default';
	public $align_top_enabled_key = 'maxgallery_align_top_enabled';
  
 	public $hide_close_btn_enabled_default = '';
	public $hide_close_btn_enabled_default_key = 'maxgallery_hide_close_btn_enabled_video_tiles_default';
	public $hide_close_btn_enabled_key = 'maxgallery_hide_close_btn_enabled';
  
  public $bg_click_close_enabled_default = '';
	public $bg_click_close_enabled_default_key = 'maxgallery_bg_click_close_enabled_video_tiles_default';
	public $bg_click_close_enabled_key = 'maxgallery_bg_click_close_enabled';

	public $fixed_content_position_default = 'auto';
	public $fixed_content_position_default_key = 'maxgallery_fixed_content_position_enabled_video_tiles_default';
	public $fixed_content_position_key = 'maxgallery_fixed_content_position_enabled';
  
	public $overflow_y_default = 'auto';
	public $overflow_y_default_key = 'maxgallery_overflow_y_video_tiles_default';
	public $overflow_y_key = 'maxgallery_overflow_y';
  
	public $removal_delay_default = '0';
	public $removal_delay_default_key = 'maxgallery_removal_delay_video_tiles_default';
	public $removal_delay_key = 'maxgallery_removal_delay';

	public $main_class_default = '';
	public $main_class_default_key = 'maxgallery_main_class_video_tiles_default';
	public $main_class_key = 'maxgallery_main_class';
  
	public $gallery_enabled_default = '';
	public $gallery_enabled_default_key = 'maxgallery_gallery_enabled_video_tiles_default';
	public $gallery_enabled_key = 'maxgallery_gallery_enabled';
  
	public $arrow_markup_default = "<button title='%title%' type='button' class='mfp-arrow mfp-arrow-%dir%'></button>";
	public $arrow_markup_default_key = 'maxgallery_arrow_markup_video_tiles_default';
	public $arrow_markup_key = 'maxgallery_arrow_markup';

	public $prev_button_title_default = "Previous (Left arrow key)";
	public $prev_button_title_default_key = 'maxgallery_prev_button_title_video_tiles_default';
	public $prev_button_title_key = 'maxgallery_prev_button_title';
  
	public $next_button_title_default = "Next (Right arrow key)";
	public $next_button_title_default_key = 'maxgallery_next_button_title_video_tiles_default';
	public $next_button_title_key = 'maxgallery_next_button_title';
  
  public $counter_markup_default = "<div class='mfp-counter'>%curr% of %total%</div>";
	public $counter_markup_default_key = 'maxgallery_counter_markup_video_tiles_default';
	public $counter_markup_key = 'maxgallery_counter_markup';
  
	public $sort_order_default = 'asc';
	public $sort_order_default_key = 'maxgallery_sort_order_video_tiles_default';
	public $sort_order_key = 'maxgallery_sort_order_video_tiles';
  
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
  	  
  public function get_videos_per_page() {
		$value = $this->get_post_meta($this->videos_per_page_key);
		if ($value == '' && $this->get_saves_count() < 1) {
			$value = $this->get_videos_per_page_default();
		}
		
		return $value;
	}
	
	public function get_videos_per_page_default() {
		return get_option($this->videos_per_page_default_key, $this->videos_per_page_default);
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
		if ($this->get_template() == 'video-tiles') {
			$options = $this->get_options();
			parent::save_options($options);
		}
	}
	
	private function get_options() {
		return array(
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
			$this->videos_per_page_key,
      $this->vertical_fit_enabled_key,
      $this->escape_key_enabled_key,
      $this->align_top_enabled_key,
      $this->hide_close_btn_enabled_key,
      $this->bg_click_close_enabled_key,
      $this->fixed_content_position_key,
      $this->overflow_y_key,
      $this->removal_delay_key,
      $this->main_class_key,
      $this->gallery_enabled_key,
      $this->arrow_markup_key,
      $this->prev_button_title_key,
      $this->next_button_title_key,
      $this->counter_markup_key,
      $this->sort_order_key
		);
	}
}
?>