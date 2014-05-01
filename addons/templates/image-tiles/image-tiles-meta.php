<?php
require_once MAXGALLERIA_PLUGIN_DIR . '/maxgalleria-meta.php';

class MaxGalleriaImageTilesMeta extends MaxGalleriaMeta {
	public function __construct() {
		parent::__construct();
		
		add_action('maxgalleria_template_meta_boxes', array($this, 'add_gallery_meta_boxes'));
		add_action('save_post', array($this, 'save_gallery_options'));
	}
	
	public function add_gallery_meta_boxes() {
		global $post;
		$mg_image_tiles_options = new MaxGalleriaImageTilesOptions($post->ID);
		
		if ($mg_image_tiles_options->get_template() == 'image-tiles') {
			$this->add_side_meta_box('maxgalleria-image-tiles-skin', __('Skin', 'maxgalleria'), array($this, 'show_meta_box_skin'));
			$this->add_side_meta_box('maxgalleria-image-tiles-thumbnails', __('Thumbnails', 'maxgalleria'), array($this, 'show_meta_box_thumbnails'));
			$this->add_side_meta_box('maxgalleria-image-tiles-lightbox', __('Lightbox', 'maxgalleria'), array($this, 'show_meta_box_lightbox'));
		}
	}
	
	public function show_meta_box_skin() {
		require_once 'meta/skin.php';
	}
	
	public function show_meta_box_thumbnails() {
		require_once 'meta/thumbnails.php';
	}
	
	public function show_meta_box_lightbox() {
		require_once 'meta/lightbox.php';
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
			
			$mg_image_tiles_options = new MaxGalleriaImageTilesOptions($post->ID);
			$mg_image_tiles_options->save_options();
		}
	}
}
?>