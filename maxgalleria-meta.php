<?php
class MaxGalleriaMeta {
	public function __construct() {
		add_action('add_meta_boxes', array($this, 'add_gallery_meta_boxes'));
		add_action('save_post', array($this, 'save_gallery_options'));
	}
	
	public function add_gallery_meta_boxes() {
		global $post;
		global $maxgalleria;
		
		$new_gallery = $maxgalleria->new_gallery;
		$image_gallery = $maxgalleria->image_gallery;
		$video_gallery = $maxgalleria->video_gallery;
		
		if (isset($post)) {
			$options = new MaxGalleryOptions($post->ID);
			
			if ($options->is_new_gallery()) {
				$this->add_normal_meta_box('meta-new', __('New Gallery', 'maxgalleria'), array($new_gallery, 'show_meta_box_new'));
			}
			
			if ($options->is_image_gallery()) {
				$this->add_side_meta_box('meta-shortcodes', __('Shortcodes', 'maxgalleria'), array($image_gallery, 'show_meta_box_shortcodes'));
				
				// Only show if a template has been chosen
				if ($options->get_template() != '') {
					do_action(MAXGALLERIA_ACTION_BEFORE_TEMPLATE_META_BOXES);
					$this->add_normal_meta_box('meta-image-gallery', __('Gallery', 'maxgalleria'), array($image_gallery, 'show_meta_box_gallery'));
					do_action(MAXGALLERIA_ACTION_AFTER_TEMPLATE_META_BOXES);
				}
			}
			
			if ($options->is_video_gallery()) {
				$this->add_side_meta_box('meta-shortcodes', __('Shortcodes', 'maxgalleria'), array($video_gallery, 'show_meta_box_shortcodes'));
				
				// Only show if a template has been chosen
				if ($options->get_template() != '') {
					do_action(MAXGALLERIA_ACTION_BEFORE_TEMPLATE_META_BOXES);
					$this->add_normal_meta_box('meta-video-gallery', __('Gallery', 'maxgalleria'), array($video_gallery, 'show_meta_box_gallery'));
					do_action(MAXGALLERIA_ACTION_AFTER_TEMPLATE_META_BOXES);
				}
			}
		}
	}
	
	public function add_side_meta_box($id, $title, $callback) {
		$id = $id;
		$title = $title;
		$callback = $callback;
		$post_type = MAXGALLERIA_POST_TYPE;
		$context = 'side';
		$priority = 'default';
		add_meta_box($id, $title, $callback, $post_type, $context, $priority);
	}

	public function add_normal_meta_box($id, $title, $callback) {
		$id = $id;
		$title = $title;
		$callback = $callback;
		$post_type = MAXGALLERIA_POST_TYPE;
		$context = 'normal';
		$priority = 'high';
		add_meta_box($id, $title, $callback, $post_type, $context, $priority);
	}
	
	public function save_gallery_options() {
		global $post;

		if (isset($post)) {
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post->ID;
			}

			if (!current_user_can('edit_post', $post->ID)) {
				return $post->ID;
			}
			
			$options = new MaxGalleryOptions($post->ID);
			$options->save_options();
		}
	}
}
?>