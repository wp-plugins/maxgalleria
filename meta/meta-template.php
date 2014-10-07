<?php
global $post;
$options = new MaxGalleryOptions($post->ID);

// Get all templates
global $maxgalleria;
$all_templates = $maxgalleria->get_template_addons();

// Filter for image templates
$image_templates = array();
foreach ($all_templates as $template) {
	if ($template['subtype'] == 'image') {
		$arr = array($template['key'] => array('name' => $template['name'], 'image' => $template['image']));
		$image_templates = array_merge($image_templates, $arr);
	}
}

// Filter for video templates
$video_templates = array();
foreach ($all_templates as $template) {
	if ($template['subtype'] == 'video') {
		$arr = array($template['key'] => array('name' => $template['name'], 'image' => $template['image']));
		$video_templates = array_merge($video_templates, $arr);
	}
}

// Default to image templates
$templates = $image_templates;

// But check to see if it should use video templates
if ($options->is_video_gallery()) {
	$templates = $video_templates;
}

asort($templates);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery(".meta-options .meta-template img").click(function() {
			var images = jQuery(".meta-options .meta-template img");
			jQuery.each(images, function() {
				jQuery(this).removeClass("selected");
			});
			
			jQuery(this).addClass("selected");
			jQuery(this).next().attr("checked", true);
			
			jQuery("#post").submit();
			return false;
		});
	});
</script>

<div class="meta-options">
	<?php if (count($templates) > 0) { ?>
		<?php foreach ($templates as $key => $template) { ?>
			<div class="meta-template">
				<img src="<?php echo $template['image'] ?>" alt="<?php echo $template['name'] ?>" title="<?php echo $template['name'] ?>" <?php echo ($options->get_template() == $key) ? 'class="selected"' : '' ?> />
				<input type="radio" name="<?php echo $options->template_key ?>" id="<?php echo $options->template_key ?>_<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ($options->get_template() == $key) ? 'checked="checked"' : '' ?> />
				<br />
				<?php echo $template['name'] ?>			
			</div>
		<?php } ?>
		
		<!-- Saves count is for internal use only -->
		<input type="hidden" id="<?php echo $options->saves_count_key ?>" name="<?php echo $options->saves_count_key ?>" value="<?php echo $options->get_saves_count() ?>" />
	<?php } else { ?>
		<?php if ($options->is_image_gallery()) { ?>
			<p class="no-templates"><?php _e('You do not have any image gallery templates installed.', 'maxgalleria')?></p>
		<?php } ?>
		
		<?php if ($options->is_video_gallery()) { ?>
			<p class="no-templates"><?php _e('You do not have any video gallery templates installed.', 'maxgalleria')?></p>
		<?php } ?>
	<?php } ?>
</div>
