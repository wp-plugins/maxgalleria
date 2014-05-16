<?php
class MaxGalleriaImageGallery {
	public $nonce_image_edit = array(
		'action' => 'image_edit',
		'name' => 'maxgalleria_image_edit'
	);

	public $nonce_image_edit_bulk = array(
		'action' => 'image_edit_bulk',
		'name' => 'maxgalleria_image_edit_bulk'
	);
	
	public $nonce_image_include_single = array(
		'action' => 'image_include_single',
		'name' => 'maxgalleria_image_include_single'
	);

	public $nonce_image_include_bulk = array(
		'action' => 'image_include_bulk',
		'name' => 'maxgalleria_image_include_bulk'
	);

	public $nonce_image_exclude_single = array(
		'action' => 'image_exclude_single',
		'name' => 'maxgalleria_image_exclude_single'
	);

	public $nonce_image_exclude_bulk = array(
		'action' => 'image_exclude_bulk',
		'name' => 'maxgalleria_image_exclude_bulk'
	);

	public $nonce_image_remove_single = array(
		'action' => 'image_remove_single',
		'name' => 'maxgalleria_image_remove_single'
	);

	public $nonce_image_remove_bulk = array(
		'action' => 'image_remove_bulk',
		'name' => 'maxgalleria_image_remove_bulk'
	);

	public $nonce_image_reorder = array(
		'action' => 'image_reorder',
		'name' => 'maxgalleria_image_reorder'
	);
	
	public function __construct() {
		$this->setup_hooks();
	}

	public function setup_hooks() {
		// Ajax call to add media library images to a gallery
		add_action('wp_ajax_add_media_library_images_to_gallery', array($this, 'add_media_library_images_to_gallery'));
		add_action('wp_ajax_nopriv_add_media_library_images_to_gallery', array($this, 'add_media_library_images_to_gallery'));
		
		// Ajax call to include a single image in a gallery
		add_action('wp_ajax_include_single_image_in_gallery', array($this, 'include_single_image_in_gallery'));
		add_action('wp_ajax_nopriv_include_single_image_in_gallery', array($this, 'include_single_image_in_gallery'));

		// Ajax call to include bulk images in a gallery
		add_action('wp_ajax_include_bulk_images_in_gallery', array($this, 'include_bulk_images_in_gallery'));
		add_action('wp_ajax_nopriv_include_bulk_images_in_gallery', array($this, 'include_bulk_images_in_gallery'));

		// Ajax call to exclude a single image from a gallery
		add_action('wp_ajax_exclude_single_image_from_gallery', array($this, 'exclude_single_image_from_gallery'));
		add_action('wp_ajax_nopriv_exclude_single_image_from_gallery', array($this, 'exclude_single_image_from_gallery'));

		// Ajax call to exclude bulk images from a gallery
		add_action('wp_ajax_exclude_bulk_images_from_gallery', array($this, 'exclude_bulk_images_from_gallery'));
		add_action('wp_ajax_nopriv_exclude_bulk_images_from_gallery', array($this, 'exclude_bulk_images_from_gallery'));

		// Ajax call to remove a single image from a gallery
		add_action('wp_ajax_remove_single_image_from_gallery', array($this, 'remove_single_image_from_gallery'));
		add_action('wp_ajax_nopriv_remove_single_image_from_gallery', array($this, 'remove_single_image_from_gallery'));
		
		// Ajax call to remove bulk images from a gallery
		add_action('wp_ajax_remove_bulk_images_from_gallery', array($this, 'remove_bulk_images_from_gallery'));
		add_action('wp_ajax_nopriv_remove_bulk_images_from_gallery', array($this, 'remove_bulk_images_from_gallery'));

		// Ajax call to reorder images
		add_action('wp_ajax_reorder_images', array($this, 'reorder_images'));
		add_action('wp_ajax_nopriv_reorder_images', array($this, 'reorder_images'));
	}
	
	public function add_media_library_images_to_gallery() {
		if (isset($_POST)) {
			$result = 0;
			
			$gallery_id = $_POST['gallery_id'];
			
			do_action(MAXGALLERIA_ACTION_BEFORE_ADD_IMAGES_TO_GALLERY, $gallery_id, $_POST['url']);
			
			$count = count($_POST['url']);
			for ($i = 0; $i < $count; $i++) {
				$url = $_POST['url'][$i];
				$title = $_POST['title'][$i];
				$caption = $_POST['caption'][$i];
				$description = $_POST['description'][$i];
				$alt_text = $_POST['alt_text'][$i];

				if ($url != '') {
					do_action(MAXGALLERIA_ACTION_BEFORE_ADD_IMAGE_TO_GALLERY, $gallery_id, $url, $title, $caption, $description, $alt_text);

					$attachment_id = $this->download_image_attach_to_gallery($gallery_id, $url, $title, $caption, $description, $alt_text);
					$result = $attachment_id;
					
					do_action(MAXGALLERIA_ACTION_AFTER_ADD_IMAGE_TO_GALLERY, $gallery_id, $url, $title, $caption, $description, $alt_text);
				}
			}

			// Once the images have been added to the gallery, delete any attachments with menu_order
			// of zero. The reason is because starting with WP 3.9, when a user adds a new image to
			// their media library, it automatically attaches to the post behind the scenes. This causes
			// the images to get added to the gallery twice - once with menu_order of 0 and another with
			// menu_order equal to whatever the next menu_order is supposed to be. The latter image is
			// the one we want, which means the image with menu_order of 0 can be safely deleted.
			$bad_children = get_children(array(
				'post_parent' => $gallery_id,
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'menu_order' => 0
			));

			$force_delete = true; // Bypass trash and do hard delete
			foreach ($bad_children as $child) {
				wp_delete_attachment($child->ID, $force_delete);
			}

			do_action(MAXGALLERIA_ACTION_AFTER_ADD_IMAGES_TO_GALLERY, $gallery_id, $_POST['url']);

			echo $result;
			die();
		}
	}
	
	public function download_image_attach_to_gallery($gallery_id, $image_url, $title = '', $caption = '', $description = '', $alt_text = '') {		
		global $maxgalleria;
		
		$result = 0;
		$download_success = true;
		
		// Download the image into a temp file
		$temp_file = download_url($image_url);

		// Parse the url and use the temp file to form the file array (used in media_handle_sideload below)
		preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $image_url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $temp_file;
		
		// If we got an error or the file is not a valid image, delete the temp file
		if (is_wp_error($temp_file) || !file_is_valid_image($temp_file)) {
			@unlink($temp_file);
			$download_success = false;
		}
		
		if ($download_success) {
			// Get the next menu order value for the gallery
			$menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);

			// Set post data; the empty post_date ensures it gets today's date
			$post_data = array(
				'post_date' => '',
				'post_parent' => $gallery_id,
				'post_title' => $title,
				'post_excerpt' => $caption,
				'post_content' => $description,
				'menu_order' => $menu_order,
				'ancestors' => array()
			);

			// Sideload the image to create its attachment to the gallery
			$attachment_id = media_handle_sideload($file_array, $gallery_id, $description, $post_data);

			if (!is_wp_error($attachment_id)) {
				$result = $attachment_id;
				
				// Add the alt text
				update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
			}
			
			// Delete the temp file
			@unlink($temp_file);
		}

		return $result;
	}

	public function include_single_image_in_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_include_single['action'], $this->nonce_image_include_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {
				$image_post = get_post($_POST['id']);
				if (isset($image_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_INCLUDE_SINGLE_IMAGE_IN_GALLERY, $image_post);
					delete_post_meta($image_post->ID, 'maxgallery_attachment_image_exclude', true);
					do_action(MAXGALLERIA_ACTION_AFTER_INCLUDE_SINGLE_IMAGE_IN_GALLERY, $image_post);
					
					$message = __('Included the image in this gallery.', 'maxgalleria');
				}
			}
			
			echo $message;
			die();
		}
	}

	public function include_bulk_images_in_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_include_bulk['action'], $this->nonce_image_include_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'include') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_INCLUDE_BULK_IMAGES_IN_GALLERY, $_POST['media-id']);
					
					foreach ($_POST['media-id'] as $id) {
						$image_post = get_post($id);
						if (isset($image_post)) {
							do_action(MAXGALLERIA_ACTION_BEFORE_INCLUDE_SINGLE_IMAGE_IN_GALLERY, $image_post);
							delete_post_meta($image_post->ID, 'maxgallery_attachment_image_exclude', true);
							do_action(MAXGALLERIA_ACTION_AFTER_INCLUDE_SINGLE_IMAGE_IN_GALLERY, $image_post);
							
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_INCLUDE_BULK_IMAGES_IN_GALLERY, $_POST['media-id']);
					
					if ($count == 1) {
						$message = __('Included 1 image in this gallery.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(__('Included %d images in this gallery.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo $message;
			die();
		}
	}

	public function exclude_single_image_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_exclude_single['action'], $this->nonce_image_exclude_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {			
				$image_post = get_post($_POST['id']);
				if (isset($image_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_EXCLUDE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
					update_post_meta($image_post->ID, 'maxgallery_attachment_image_exclude', true);
					do_action(MAXGALLERIA_ACTION_AFTER_EXCLUDE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
					
					$message = __('Excluded the image from this gallery.', 'maxgalleria');
				}
			}
			
			echo $message;
			die();
		}
	}

	public function exclude_bulk_images_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_exclude_bulk['action'], $this->nonce_image_exclude_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'exclude') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_EXCLUDE_BULK_IMAGES_FROM_GALLERY, $_POST['media-id']);
					
					foreach ($_POST['media-id'] as $id) {
						$image_post = get_post($id);
						if (isset($image_post)) {
							do_action(MAXGALLERIA_ACTION_BEFORE_EXCLUDE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
							update_post_meta($image_post->ID, 'maxgallery_attachment_image_exclude', true);
							do_action(MAXGALLERIA_ACTION_AFTER_EXCLUDE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
							
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_EXCLUDE_BULK_IMAGES_FROM_GALLERY, $_POST['media-id']);
					
					if ($count == 1) {
						$message = __('Excluded 1 image from this gallery.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(__('Excluded %d images from this gallery.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo $message;
			die();
		}
	}

	public function remove_single_image_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_remove_single['action'], $this->nonce_image_remove_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {			
				$image_post = get_post($_POST['id']);
				if (isset($image_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
					
					$temp = array();
					$temp['ID'] = $image_post->ID;
					$temp['post_parent'] = null;
					
					wp_update_post($temp);
					
					do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
					$message = __('Removed the image from this gallery. To delete it permanently, use the Media Library.', 'maxgalleria');
				}
			}
			
			echo $message;
			die();
		}
	}

	public function remove_bulk_images_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_remove_bulk['action'], $this->nonce_image_remove_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'remove') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_BULK_IMAGES_FROM_GALLERY, $_POST['media-id']);
					
					foreach ($_POST['media-id'] as $id) {
						$image_post = get_post($id);
						if (isset($image_post)) {
							do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
							
							$temp = array();
							$temp['ID'] = $image_post->ID;
							$temp['post_parent'] = null;
							
							wp_update_post($temp);
							do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
							
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_BULK_IMAGES_FROM_GALLERY, $_POST['media-id']);
					
					if ($count == 1) {
						$message = __('Removed 1 image from this gallery. To delete it permanently, use the Media Library.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(__('Removed %d images from this gallery. To delete them permanently, use the Media Library.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo $message;
			die();
		}
	}

	public function reorder_images() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_reorder['action'], $this->nonce_image_reorder['name'])) {
			$message = '';

			if (isset($_POST['media-order']) && isset($_POST['media-order-id'])) {		
				do_action(MAXGALLERIA_ACTION_BEFORE_REORDER_IMAGES_IN_GALLERY, $_POST['media-order'], $_POST['media-order-id']);
				
				for ($i = 0; $i < count($_POST['media-order']); $i++) {
					$order = $_POST['media-order'][$i];
					$image_id = $_POST['media-order-id'][$i];
					
					$image_post = get_post($image_id);
					if (isset($image_post)) {
						do_action(MAXGALLERIA_ACTION_BEFORE_REORDER_IMAGE_IN_GALLERY, $image_post);
						
						$temp = array();
						$temp['ID'] = $image_post->ID;
						$temp['menu_order'] = $order;
						
						wp_update_post($temp);
						do_action(MAXGALLERIA_ACTION_AFTER_REORDER_IMAGE_IN_GALLERY, $image_post);
					}
				}
				
				do_action(MAXGALLERIA_ACTION_AFTER_REORDER_IMAGES_IN_GALLERY, $_POST['media-order'], $_POST['media-order-id']);
			}
			
			echo $message;
			die();
		}
	}
	
	public function show_meta_box_gallery($post) {
		require_once 'meta/meta-image-gallery.php';
	}
	
	public function show_meta_box_shortcodes($post) {
		require_once 'meta/meta-shortcodes.php';
	}
	
	public function get_image_size_display($attachment) {
		$size = '';
		
		$meta = wp_get_attachment_metadata($attachment->ID);
		if (is_array($meta) && array_key_exists('width', $meta) && array_key_exists('height', $meta)) {
			$size = "{$meta['width']} &times; {$meta['height']}";
		}
		
		return $size;
	}
	
	public function get_thumb_image($attachment, $thumb_shape, $thumb_columns, $thumb_crop = true) {
		$thumb_size = $this->get_thumb_size($thumb_shape, $thumb_columns);
		$thumb_image = $this->resize_image($attachment, $thumb_size['width'], $thumb_size['height'], $thumb_crop);
		return $thumb_image;
	}
	
	public function get_thumb_size($thumb_shape, $thumb_columns) {
		$thumb_size = null;

		switch ($thumb_shape) {
			case MAXGALLERIA_THUMB_SHAPE_LANDSCAPE:
				if ($thumb_columns == 1) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_ONE_COLUMN, array('width' => '700', 'height' => '466')); }
				if ($thumb_columns == 2) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_TWO_COLUMN, array('width' => '550', 'height' => '366')); }
				if ($thumb_columns == 3) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_THREE_COLUMN, array('width' => '400', 'height' => '266')); }
				if ($thumb_columns == 4) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_FOUR_COLUMN, array('width' => '250', 'height' => '166')); }
				if ($thumb_columns == 5) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_FIVE_COLUMN, array('width' => '200', 'height' => '133')); }
				if ($thumb_columns == 6) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_SIX_COLUMN, array('width' => '180', 'height' => '120')); }
				if ($thumb_columns == 7) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_SEVEN_COLUMN, array('width' => '150', 'height' => '100')); }
				if ($thumb_columns == 8) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_EIGHT_COLUMN, array('width' => '130', 'height' => '86')); }
				if ($thumb_columns == 9) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_NINE_COLUMN, array('width' => '115', 'height' => '76')); }
				if ($thumb_columns == 10) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_TEN_COLUMN, array('width' => '100', 'height' => '66')); }
				break;
			case MAXGALLERIA_THUMB_SHAPE_PORTRAIT:
				if ($thumb_columns == 1) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_ONE_COLUMN, array('width' => '700', 'height' => '1050')); }
				if ($thumb_columns == 2) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_TWO_COLUMN, array('width' => '550', 'height' => '825')); }
				if ($thumb_columns == 3) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_THREE_COLUMN, array('width' => '400', 'height' => '600')); }
				if ($thumb_columns == 4) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_FOUR_COLUMN, array('width' => '250', 'height' => '375')); }
				if ($thumb_columns == 5) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_FIVE_COLUMN, array('width' => '200', 'height' => '300')); }
				if ($thumb_columns == 6) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_SIX_COLUMN, array('width' => '180', 'height' => '270')); }
				if ($thumb_columns == 7) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_SEVEN_COLUMN, array('width' => '150', 'height' => '225')); }
				if ($thumb_columns == 8) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_EIGHT_COLUMN, array('width' => '130', 'height' => '195')); }
				if ($thumb_columns == 9) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_NINE_COLUMN, array('width' => '115', 'height' => '172')); }
				if ($thumb_columns == 10) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_TEN_COLUMN, array('width' => '100', 'height' => '150')); }
				break;
			default:
				// Square
				if ($thumb_columns == 1) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_ONE_COLUMN, array('width' => '700', 'height' => '700')); }
				if ($thumb_columns == 2) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_TWO_COLUMN, array('width' => '550', 'height' => '550')); }
				if ($thumb_columns == 3) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_THREE_COLUMN, array('width' => '400', 'height' => '400')); }
				if ($thumb_columns == 4) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_FOUR_COLUMN, array('width' => '250', 'height' => '250')); }
				if ($thumb_columns == 5) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_FIVE_COLUMN, array('width' => '200', 'height' => '200')); }
				if ($thumb_columns == 6) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_SIX_COLUMN, array('width' => '180', 'height' => '180')); }
				if ($thumb_columns == 7) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_SEVEN_COLUMN, array('width' => '150', 'height' => '150')); }
				if ($thumb_columns == 8) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_EIGHT_COLUMN, array('width' => '130', 'height' => '130')); }
				if ($thumb_columns == 9) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_NINE_COLUMN, array('width' => '115', 'height' => '115')); }
				if ($thumb_columns == 10) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_TEN_COLUMN, array('width' => '100', 'height' => '100')); }
				break;
		}
		
		return $thumb_size;
	}
	
	public function resize_image($attachment, $width, $height, $crop) {
		// Get the image source for the attachment, note the following:
		// $image_src[0] = the URL of the image
		// $image_src[1] = the width of the image
		// $image_src[2] = the height of the image
		$image_src = wp_get_attachment_image_src($attachment->ID, 'full');
		
		// If either the width or height of the full size image is bigger than the target size, then we know we need to resize
		if ($image_src[1] > $width || $image_src[2] > $height) {
			$resized_image_path = '';
			$resized_image_url = '';
			
			$file_path = get_attached_file($attachment->ID);
			
			// Get the file info and extension
			$file_info = pathinfo($file_path);
			$extension = '.' . $file_info['extension'];

			// The image path without the extension
			$no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];

			// Build the cropped image path and URL with the width and height as part of the name
			$cropped_image_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
			$cropped_image_url = str_replace(basename($image_src[0]), basename($cropped_image_path), $image_src[0]);
			
			// Check if resized cropped version already exists (for crop = true but will also work for crop = false if the sizes match)
			if (file_exists($cropped_image_path)) {
				return array('url' => $cropped_image_url, 'width' => $width, 'height' => $height);
			}
			else {
				$resized_image_path = $cropped_image_path;
				$resized_image_url = $cropped_image_url;
			}

			// If crop is false then check proportional image
			if ($crop == false) {
				// Calculate the size proportionally
				$proportional_size = wp_constrain_dimensions($image_src[1], $image_src[2], $width, $height);
				$proportional_image_path = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
				$proportional_image_url = str_replace(basename($image_src[0]), basename($proportional_image_path), $image_src[0]);

				// Check if resized proportional version already exists
				if (file_exists($proportional_image_path)) {
					return array('url' => $proportional_image_url, 'width' => $proportional_size[0], 'height' => $proportional_size[1]);
				}
				else {
					$resized_image_path = $proportional_image_path;
					$resized_image_url = $proportional_image_url;
				}
			}

			// Getting this far means that neither the cropped resized image nor the proportional
			// resized image exists, so we use a WP_Image_Editor to do the resizing and save to disk
			$image_editor = wp_get_image_editor($file_path);
			$resized = $image_editor->resize($width, $height, $crop);
			$new_image = $image_editor->save($resized_image_path);
			
			return array('url' => $resized_image_url, 'width' => $new_image['width'], 'height' => $new_image['height']);
		}
		
		// Default output, no resizing
		return array('url' => $image_src[0], 'width' => $image_src[1], 'height' => $image_src[2]);
	}
}
?>