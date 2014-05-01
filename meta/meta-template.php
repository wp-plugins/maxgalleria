<?php
global $post;
$options = new MaxGalleryOptions($post->ID);

// Get all templates
global $maxgalleria;
$all_templates = $maxgalleria->get_template_addons();

// Filter for image templates
$image_templates = array('-- Select Template --' => '');
foreach ($all_templates as $template) {
	if ($template['subtype'] == 'image') {
		$image_templates = array_merge($image_templates, array($template['name'] => $template['key']));
	}
}

// Filter for video templates
$video_templates = array('-- Select Template --' => '');
foreach ($all_templates as $template) {
	if ($template['subtype'] == 'video') {
		$video_templates = array_merge($video_templates, array($template['name'] => $template['key']));
	}
}

// Default to image template name/value pairs
$templates = $image_templates;

// But check to see if it should use video template name/value pairs
if ($options->is_video_gallery()) {
	$templates = $video_templates;
}

asort($templates);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery("#<?php echo $options->template_key ?>").change(function() {
			jQuery("#post").submit();
			return false;
		});
	});
</script>

<div class="maxgalleria-meta">
	<table>
		<tr>
			<td width="60">
				<?php _e('Template', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo $options->template_key ?>" name="<?php echo $options->template_key ?>">
				<?php foreach ($templates as $name => $key) { ?>
					<?php $selected = ($options->get_template() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
				
				<!-- Saves count is for internal use only -->
				<input type="hidden" id="<?php echo $options->saves_count_key ?>" name="<?php echo $options->saves_count_key ?>" value="<?php echo $options->get_saves_count() ?>" />
			</td>
		</tr>
		<?php if (count($templates) <= 1) { ?>
			<tr>
				<td colspan="2">
					<?php if ($options->is_image_gallery()) { ?>
						<p class="no-templates"><?php _e('You do not have any image gallery templates installed.', 'maxgalleria')?></p>
					<?php } ?>
					
					<?php if ($options->is_video_gallery()) { ?>
						<p class="no-templates"><?php _e('You do not have any video gallery templates installed.', 'maxgalleria')?></p>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</table>
</div>