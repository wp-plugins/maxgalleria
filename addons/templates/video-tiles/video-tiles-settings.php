<?php
$options = new MaxGalleriaVideoTilesOptions();
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery("#save-video-tiles-settings").click(function() {
			jQuery("#save-video-tiles-settings-success").hide();
			
			var form_data = jQuery("#form-video-tiles-settings").serialize();

			// If thumb caption enabled is not checked, we have to add it to form data with an empty value
			if (jQuery("#<?php echo $options->thumb_caption_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->thumb_caption_enabled_default_key ?>=";
			}
			
			// Add the action to the form data
			form_data += "&action=save_video_tiles_defaults";
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: form_data,
				success: function(message) {
					if (message == "success") {
						jQuery("#save-video-tiles-settings-success").show();
					}
				}
			});
			
			return false;
		});
		
		jQuery("#revert-video-tiles-defaults").click(function() {
			jQuery.each(jQuery("input, select", "#form-video-tiles-settings"), function() {
				var type = jQuery(this)[0].type;
				var default_value = jQuery(this).attr("data-default");
				
				if (type != "hidden") {
					if (type == "checkbox") {
						if (default_value == "on") {
							jQuery(this).attr("checked", "checked");
						}
						else {
							jQuery(this).removeAttr("checked");
						}
					}
					else {
						jQuery(this).val(default_value);
					}
				}
			});
			
			return false;
		});
	});
</script>

<div id="save-video-tiles-settings-success" class="alert alert-success" style="display: none;">
	<?php _e('Settings saved.', 'maxgalleria') ?>
</div>

<div class="settings-title">
	<?php _e('Video Tiles Defaults', 'maxgalleria') ?>
</div>

<div class="settings-options">
	<p class="note"><?php _e('These are the default settings that will be used every time you create a gallery with the Video Tiles template. Each of these settings can be changed per gallery.', 'maxgalleria') ?></p>
	
	<form id="form-video-tiles-settings">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td><?php _e('Gallery Skin:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->skin_default ?>" id="<?php echo $options->skin_default_key ?>" name="<?php echo $options->skin_default_key ?>">
					<?php foreach ($options->skins as $key => $name) { ?>
						<?php $selected = ($options->get_skin_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Thumbnail Columns:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->thumb_columns_default ?>" id="<?php echo $options->thumb_columns_default_key ?>" name="<?php echo $options->thumb_columns_default_key ?>">
					<?php foreach ($options->thumb_columns as $key => $name) { ?>
						<?php $selected = ($options->get_thumb_columns_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Thumbnail Shape:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->thumb_shape_default ?>" id="<?php echo $options->thumb_shape_default_key ?>" name="<?php echo $options->thumb_shape_default_key ?>">
					<?php foreach ($options->thumb_shapes as $key => $name) { ?>
						<?php $selected = ($options->get_thumb_shape_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Thumbnail Captions Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->thumb_caption_enabled_default ?>" type="checkbox" id="<?php echo $options->thumb_caption_enabled_default_key ?>" name="<?php echo $options->thumb_caption_enabled_default_key ?>" <?php echo (($options->get_thumb_caption_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
			</tr>
			<tr>
				<td><?php _e('Thumbnail Captions Position:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->thumb_caption_position_default ?>" id="<?php echo $options->thumb_caption_position_default_key ?>" name="<?php echo $options->thumb_caption_position_default_key ?>">
					<?php foreach ($options->caption_positions as $key => $name) { ?>
						<?php $selected = ($options->get_thumb_caption_position_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Thumbnail Click Opens:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->thumb_click_default ?>" id="<?php echo $options->thumb_click_default_key ?>" name="<?php echo $options->thumb_click_default_key ?>">
					<?php foreach ($options->thumb_clicks as $key => $name) { ?>
						<?php $selected = ($options->get_thumb_click_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Thumbnail Custom Image Class:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->thumb_image_class_default ?>" type="text" id="<?php echo $options->thumb_image_class_default_key ?>" name="<?php echo $options->thumb_image_class_default_key ?>" value="<?php echo $options->get_thumb_image_class_default() ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e('Thumbnail Custom Image Container Class:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->thumb_image_container_class_default ?>" type="text" id="<?php echo $options->thumb_image_container_class_default_key ?>" name="<?php echo $options->thumb_image_container_class_default_key ?>" value="<?php echo $options->get_thumb_image_container_class_default() ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e('Thumbnail Custom Rel Attribute:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->thumb_image_rel_attribute_default ?>" type="text" id="<?php echo $options->thumb_image_rel_attribute_default_key ?>" name="<?php echo $options->thumb_image_rel_attribute_default_key ?>" value="<?php echo $options->get_thumb_image_rel_attribute_default() ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e('Lightbox Video Size:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->lightbox_video_size_default ?>" class="small" id="<?php echo $options->lightbox_video_size_default_key ?>" name="<?php echo $options->lightbox_video_size_default_key ?>">
					<?php foreach ($options->lightbox_sizes as $key => $name) { ?>
						<?php $selected = ($options->get_lightbox_video_size_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Lightbox Video Custom Width:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->lightbox_video_size_custom_width_default ?>" type="text" class="small" id="<?php echo $options->lightbox_video_size_custom_width_default_key ?>" name="<?php echo $options->lightbox_video_size_custom_width_default_key ?>" value="<?php echo $options->get_lightbox_video_size_custom_width_default() ?>" /> px
				</td>
			</tr>
			<tr>
				<td><?php _e('Lightbox Video Custom Height:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->lightbox_video_size_custom_height_default ?>" type="text" class="small" id="<?php echo $options->lightbox_video_size_custom_height_default_key ?>" name="<?php echo $options->lightbox_video_size_custom_height_default_key ?>" value="<?php echo $options->get_lightbox_video_size_custom_height_default() ?>" /> px
				</td>
			</tr>
		</table>
		
		<?php wp_nonce_field($options->nonce_save_video_tiles_defaults['action'], $options->nonce_save_video_tiles_defaults['name']) ?>
	</form>
</div>

<a id="save-video-tiles-settings" href="#" class="button button-primary"><?php _e('Save Settings', 'maxgalleria') ?></a>
<a id="revert-video-tiles-defaults" href="#" class="button" style="margin-left: 10px;"><?php _e('Revert Defaults', 'maxgalleria') ?></a>
