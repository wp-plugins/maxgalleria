<?php
class MaxGalleriaYouTube {	
	public $addon_key;
	public $addon_name;
	public $addon_type;
	public $addon_subtype;
	public $addon_settings;
	public $regex_patterns;

	public function __construct() {
		$this->addon_key = 'maxgalleria-youtube';
		$this->addon_name = __('YouTube', 'maxgalleria');
		$this->addon_type = 'media_source';
		$this->addon_subtype = 'video';
		$this->addon_settings = MAXGALLERIA_PLUGIN_DIR . '/addons/media-sources/youtube/youtube-settings.php';

		$this->regex_patterns = apply_filters(MAXGALLERIA_FILTER_YOUTUBE_REGEX_PATTERNS, array(
			'#^(http:\/\/|https:\/\/)?(www\.)?youtube\.com\/watch\?v=(.*?)(&.*?)?$#',
			'#^(http:\/\/|https:\/\/)?(www\.)?youtube\.com\/v\/(.*?)$#',
			'#^(http:\/\/|https:\/\/)?(www\.)?youtu\.be\/(.*?)$#'
		));

		// Hooks
		add_filter('maxgalleria_video_api_url', array($this, 'get_video_api_url'), 10, 2);
		add_filter('maxgalleria_video_thumb_url', array($this, 'get_video_thumb_url'), 10, 3);
		add_filter('maxgalleria_video_embed_code', array($this, 'get_video_embed_code'), 10, 6);
		add_filter('maxgalleria_video_attachment', array($this, 'get_video_attachment'), 10, 6);
		add_action('maxgalleria_video_attachment_post_meta', array($this, 'save_video_attachment_post_meta'), 10, 4);
	}

	public function get_video_api_url($api_url, $video_url) {
		if ($api_url == '') {
			if ($this->is_youtube_video($video_url)) {
				$video_id = $this->get_video_id($video_url);
				$api_url = 'http://gdata.youtube.com/feeds/api/videos/' . $video_id . '?v=2&alt=json';
			}
		}

		return $api_url;
	}

	public function get_video_id($video_url) {
		foreach ($this->regex_patterns as $pattern) {
			preg_match($pattern, $video_url, $matches);

			if (isset($matches[3])) {
				return $matches[3];
			}
		}

		return '';
	}

	public function get_video_thumb_url($thumb_url, $video_url, $data) {
		if ($thumb_url == '') {
			if ($this->is_youtube_video($video_url)) {
				foreach ($data['entry']['media$group']['media$thumbnail'] as $item) {
					if ($item['yt$name'] == 'hqdefault') {
						return $item['url'];
					}
				}

				return '';
			}
		}

		return $thumb_url;
	}

	public function get_video_embed_code($embed_code, $video_url, $enable_related_videos, $enable_hd_playback, $width='768', $height='432') {
		if ($embed_code == '') {
			if ($this->is_youtube_video($video_url)) {
				global $wp_embed;
				global $maxgalleria;

				// Try getting the embed code
				$embed_code = $wp_embed->run_shortcode('[embed width="' . $width . '" height="' . $height . '"]' . $video_url . '[/embed]');

				// If the embed code doesn't contain any embeddable elements,
				// then we need to fall back to building the embed code manually
				if (!$maxgalleria->common->string_contains_embeddable_element($embed_code)) {
					$video_id = $this->get_video_id($video_url);
					$embed_code = '<iframe src="http://www.youtube.com/embed/' . $video_id . '?feature=oembed" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
				}
        
        //check for SSL on the server, iso change to https
        if ($_SERVER["HTTPS"] == "on") {
          $embed_code = str_replace('http:', 'https:', $embed_code);
        }
          
				// We always add support for fullscreen mode and javascript API access on YouTube videos
				$embed_code = str_replace('feature=oembed', 'feature=oembed&fs=1&enablejsapi=1', $embed_code);

				// Check to maintain backwards compatibility for related videos
				if (!isset($enable_related_videos) || $enable_related_videos == '') {
					$enable_related_videos = 1; // True
				}

				// Check to disable related videos
				if ($enable_related_videos == 0) {
					$embed_code = str_replace('feature=oembed', 'feature=oembed&rel=0', $embed_code);
				}

				// Check to maintain backwards compatibility for HD playback
				if (!isset($enable_hd_playback) || $enable_hd_playback == '') {
					$enable_hd_playback = 0; // False
				}

				// Check to enable HD playback
				if ($enable_hd_playback == 1) {
					$embed_code = str_replace('feature=oembed', 'feature=oembed&hd=1', $embed_code);
				}
			}
		}

		return $embed_code;
	}

	public function get_video_attachment($attachment, $video_url, $gallery_id, $guid, $file_type, $data) {
		if (empty($attachment)) {
			if ($this->is_youtube_video($video_url)) {
				global $maxgalleria;

				$menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);

				$attachment = array(
					'ID' => 0,
					'guid' => $guid,
					'post_title' => $data['entry']['media$group']['media$title']['$t'],
					'post_excerpt' => $data['entry']['media$group']['media$title']['$t'],
					'post_content' => $data['entry']['media$group']['media$description']['$t'],
					'post_date' => '', // Ensures it gets today's date
					'post_parent' => $gallery_id,
					'post_mime_type' => $file_type,
					'ancestors' => array(),
					'menu_order' => $menu_order
				);
			}
		}

		return $attachment;
	}

	public function save_video_attachment_post_meta($attachment_id, $video_url, $thumb_url, $data) {
		if ($this->is_youtube_video($video_url)) {
			update_post_meta($attachment_id, 'maxgallery_attachment_video_url', $video_url);
			update_post_meta($attachment_id, 'maxgallery_attachment_video_thumb_url', $thumb_url);
			update_post_meta($attachment_id, 'maxgallery_attachment_video_id', $data['entry']['media$group']['yt$videoid']['$t']);
			update_post_meta($attachment_id, 'maxgallery_attachment_video_seconds', $data['entry']['media$group']['yt$duration']['seconds']);
			update_post_meta($attachment_id, '_wp_attachment_image_alt', $data['entry']['media$group']['media$title']['$t']);
		}
	}

	private function is_youtube_video($video_url) {
		global $maxgalleria;
		$common = $maxgalleria->common;

		if ($common->url_matches_patterns($video_url, $this->regex_patterns)) {
			return true;
		}

		return false;
	}
}
?>