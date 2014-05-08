<?php
global $post;
$options = new MaxGalleriaImageTilesOptions($post->ID);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		enableDisableThumbClickNewWindow();
		enableDisableLightboxCustomSize();
		
		jQuery("#<?php echo $options->thumb_click_key ?>").change(function() {
			enableDisableThumbClickNewWindow();
		});

		jQuery("#<?php echo $options->lightbox_image_size_key ?>").change(function() {
			enableDisableLightboxCustomSize();
		});
	});
	
	function enableDisableThumbClickNewWindow() {
		thumb_click = jQuery("#<?php echo $options->thumb_click_key ?>").val();
		
		if (thumb_click == "lightbox") {
			jQuery("#<?php echo $options->thumb_click_new_window_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->thumb_click_new_window_key ?>").removeAttr("checked");
		}
		else {
			jQuery("#<?php echo $options->thumb_click_new_window_key ?>").removeAttr("disabled");
		}
	}
	
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

<div class="meta-options">
	<table>
		<tr>
			<td>
				<?php _e('Gallery Skin:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo $options->skin_key ?>" name="<?php echo $options->skin_key ?>">
				<?php foreach ($options->skins as $key => $name) { ?>
					<?php $selected = ($options->get_skin() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Thumbnail Columns:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo $options->thumb_columns_key ?>" name="<?php echo $options->thumb_columns_key ?>">
				<?php foreach ($options->thumb_columns as $key => $name) { ?>
					<?php $selected = ($options->get_thumb_columns() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Thumbnail Shape:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo $options->thumb_shape_key ?>" name="<?php echo $options->thumb_shape_key ?>">
				<?php foreach ($options->thumb_shapes as $key => $name) { ?>
					<?php $selected = ($options->get_thumb_shape() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo $options->thumb_caption_enabled_key ?>"><?php _e('Thumbnail Captions Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo $options->thumb_caption_enabled_key ?>" name="<?php echo $options->thumb_caption_enabled_key ?>" <?php echo (($options->get_thumb_caption_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Thumbnail Captions Position:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo $options->thumb_caption_position_key ?>" name="<?php echo $options->thumb_caption_position_key ?>">
				<?php foreach ($options->caption_positions as $key => $name) { ?>
					<?php $selected = ($options->get_thumb_caption_position() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Thumbnail Click Opens:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo $options->thumb_click_key ?>" name="<?php echo $options->thumb_click_key ?>">
				<?php foreach ($options->thumb_clicks as $key => $name) { ?>
					<?php $selected = ($options->get_thumb_click() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo $options->thumb_click_new_window_key ?>"><?php _e('Thumbnail Click New Window:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo $options->thumb_click_new_window_key ?>" name="<?php echo $options->thumb_click_new_window_key ?>" <?php echo (($options->get_thumb_click_new_window() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Thumbnail Custom Image Class:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" id="<?php echo $options->thumb_image_class_key ?>" name="<?php echo $options->thumb_image_class_key ?>" value="<?php echo $options->get_thumb_image_class() ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Thumbnail Custom Image Container Class:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" id="<?php echo $options->thumb_image_container_class_key ?>" name="<?php echo $options->thumb_image_container_class_key ?>" value="<?php echo $options->get_thumb_image_container_class() ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Thumbnail Custom Rel Attribute:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" id="<?php echo $options->thumb_image_rel_attribute_key ?>" name="<?php echo $options->thumb_image_rel_attribute_key ?>" value="<?php echo $options->get_thumb_image_rel_attribute() ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo $options->lightbox_caption_enabled_key ?>"><?php _e('Lightbox Captions Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo $options->lightbox_caption_enabled_key ?>" name="<?php echo $options->lightbox_caption_enabled_key ?>" <?php echo (($options->get_lightbox_caption_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Lightbox Captions Position:', 'maxgalleria') ?>
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
		<tr>
			<td>
				<?php _e('Lightbox Image Size:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo $options->lightbox_image_size_key ?>" name="<?php echo $options->lightbox_image_size_key ?>">
				<?php foreach ($options->lightbox_sizes as $key => $name) { ?>
					<?php $selected = ($options->get_lightbox_image_size() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Lightbox Image Custom Width:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" class="small" id="<?php echo $options->lightbox_image_size_custom_width_key ?>" name="<?php echo $options->lightbox_image_size_custom_width_key ?>" value="<?php echo $options->get_lightbox_image_size_custom_width() ?>" /> px
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Lightbox Image Custom Height:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" class="small" id="<?php echo $options->lightbox_image_size_custom_height_key ?>" name="<?php echo $options->lightbox_image_size_custom_height_key ?>" value="<?php echo $options->get_lightbox_image_size_custom_height() ?>" /> px
			</td>
		</tr>
	</table>
</div>
