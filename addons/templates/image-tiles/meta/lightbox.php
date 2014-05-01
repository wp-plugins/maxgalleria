<?php
global $post;
$options = new MaxGalleriaImageTilesOptions($post->ID);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery(".maxgalleria-meta .accordion.lightbox").accordion({ collapsible: true, active: false, heightStyle: "auto" });
		
		enableDisableLightboxCustomSize();
		
		jQuery("#<?php echo $options->lightbox_image_size_key ?>").change(function() {
			enableDisableLightboxCustomSize();
		});
	});
	
	function enableDisableLightboxCustomSize() {
		image_size = jQuery("#<?php echo $options->lightbox_image_size_key ?>").val();
		
		if (image_size == "full") {
			jQuery("#<?php echo $options->lightbox_image_size_custom_width_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->lightbox_image_size_custom_width_key ?>").attr("readonly", "readonly");
			jQuery("#<?php echo $options->lightbox_image_size_custom_height_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->lightbox_image_size_custom_height_key ?>").attr("readonly", "readonly");
		}
		
		if (image_size == "custom") {
			jQuery("#<?php echo $options->lightbox_image_size_custom_width_key ?>").removeAttr("disabled");
			jQuery("#<?php echo $options->lightbox_image_size_custom_width_key ?>").removeAttr("readonly");
			jQuery("#<?php echo $options->lightbox_image_size_custom_height_key ?>").removeAttr("disabled");
			jQuery("#<?php echo $options->lightbox_image_size_custom_height_key ?>").removeAttr("readonly");
		}
	}
</script>

<div class="maxgalleria-meta">
	<div class="accordion lightbox">
		<h4><?php _e('Captions', 'maxgalleria') ?></h4>
		<div>
			<table>
				<tr>
					<td width="60">
						<label for="<?php echo $options->lightbox_caption_enabled_key ?>"><?php _e('Enabled', 'maxgalleria') ?></label>
					</td>
					<td>
						<input type="checkbox" id="<?php echo $options->lightbox_caption_enabled_key ?>" name="<?php echo $options->lightbox_caption_enabled_key ?>" <?php echo (($options->get_lightbox_caption_enabled() == 'on') ? 'checked' : '') ?> />
					</td>
				</tr>
				<tr>
					<td width="60">
						<?php _e('Position', 'maxgalleria') ?>
					</td>
					<td>
						<select id="<?php echo $options->lightbox_caption_position_key ?>" name="<?php echo $options->lightbox_caption_position_key ?>">
						<?php foreach ($options->caption_positions as $key => $name) { ?>
							<?php $selected = ($options->get_lightbox_caption_position() == $key) ? 'selected="selected"' : ''; ?>
							<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
			</table>
		</div>
		
		<h4><?php _e('Image Size', 'maxgalleria') ?></h4>
		<div>
			<table>
				<tr>
					<td width="100">
						<?php _e('Image Size', 'maxgalleria') ?>
					</td>
					<td>
						<select class="small" id="<?php echo $options->lightbox_image_size_key ?>" name="<?php echo $options->lightbox_image_size_key ?>">
						<?php foreach ($options->lightbox_sizes as $key => $name) { ?>
							<?php $selected = ($options->get_lightbox_image_size() == $key) ? 'selected="selected"' : ''; ?>
							<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="100">
						<?php _e('Custom Width', 'maxgalleria') ?>
					</td>
					<td>
						<input type="text" class="small" id="<?php echo $options->lightbox_image_size_custom_width_key ?>" name="<?php echo $options->lightbox_image_size_custom_width_key ?>" value="<?php echo $options->get_lightbox_image_size_custom_width() ?>" /> px
					</td>
				</tr>
				<tr>
					<td width="100">
						<?php _e('Custom Height', 'maxgalleria') ?>
					</td>
					<td>
						<input type="text" class="small" id="<?php echo $options->lightbox_image_size_custom_height_key ?>" name="<?php echo $options->lightbox_image_size_custom_height_key ?>" value="<?php echo $options->get_lightbox_image_size_custom_height() ?>" /> px
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>