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
      
			if (jQuery("#<?php echo $options->vertical_fit_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->vertical_fit_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->escape_key_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->escape_key_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->align_top_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->align_top_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->hide_close_btn_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->hide_close_btn_enabled_default_key ?>=";
			}
            
			if (jQuery("#<?php echo $options->bg_click_close_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->bg_click_close_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->gallery_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->gallery_enabled_default_key ?>=";
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
				<td><?php _e('Number of Vidoes Per Page:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->videos_per_page_default; ?>" type="text" class="small" id="<?php echo $options->videos_per_page_default_key ?>" name="<?php echo $options->videos_per_page_default_key ?>" value="<?php echo $options->get_videos_per_page_default() ?>" />
				</td>
			</tr>
      
      <tr>
				<td><?php _e('Image display order:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->sort_order_default ?>" id="<?php echo $options->sort_order_default_key ?>" name="<?php echo $options->sort_order_default_key ?>">
					<?php foreach ($options->sort_orders as $key => $name) { ?>
						<?php $selected = ($options->get_sort_order_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
      
      <tr><td><span class="mg-bold">Lightbox Settings</span></td></tr>
      <tr>
				<td><?php _e('Align Top Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->align_top_enabled_default ?>" type="checkbox" id="<?php echo $options->align_top_enabled_default_key ?>" name="<?php echo $options->align_top_enabled_default_key ?>" <?php echo (($options->get_align_top_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
      <tr>
				<td><?php _e('Close on Background Click Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->bg_click_close_enabled_default ?>" type="checkbox" id="<?php echo $options->bg_click_close_enabled_default_key ?>" name="<?php echo $options->bg_click_close_enabled_default_key ?>" <?php echo (($options->get_bg_click_close_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
      <tr>
				<td><?php _e('Close with Escape Key Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->escape_key_enabled_default ?>" type="checkbox" id="<?php echo $options->escape_key_enabled_default_key ?>" name="<?php echo $options->escape_key_enabled_default_key ?>" <?php echo (($options->get_escape_key_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
			<tr>
				<td><?php _e('Fixed Content Position:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->fixed_content_position_default ?>" id="<?php echo $options->fixed_content_position_default_key ?>" name="<?php echo $options->fixed_content_position_default_key ?>">
					<?php foreach ($options->content_positions as $key => $name) { ?>
						<?php $selected = ($options->get_fixed_content_position_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>      
      <tr>
				<td><?php _e('Hide Close Button:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->hide_close_btn_enabled_default ?>" type="checkbox" id="<?php echo $options->hide_close_btn_enabled_default_key ?>" name="<?php echo $options->hide_close_btn_enabled_default_key ?>" <?php echo (($options->get_hide_close_btn_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
  		<tr>
				<td width="300"><?php _e('Overflow Y (Displays a vertical scroll bar on the page when fixed content position is "on" or "auto"):', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->overflow_y_default ?>" id="<?php echo $options->overflow_y_default_key ?>" name="<?php echo $options->overflow_y_default_key ?>">
					<?php foreach ($options->overflow_y_settings as $key => $name) { ?>
						<?php $selected = ($options->get_overflow_y_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Popup Custom Class:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->main_class_default ?>" type="text" id="<?php echo $options->main_class_default_key ?>" name="<?php echo $options->main_class_default_key ?>" value="<?php echo $options->get_main_class_default() ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e('Removal Delay:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->removal_delay_default; ?>" type="text" class="small" id="<?php echo $options->removal_delay_default_key ?>" name="<?php echo $options->removal_delay_default_key ?>" value="<?php echo $options->get_removal_delay_default() ?>" />
				</td>
			</tr>
      <tr>
				<td><?php _e('Vertical Fit Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->vertical_fit_enabled_default ?>" type="checkbox" id="<?php echo $options->vertical_fit_enabled_default_key ?>" name="<?php echo $options->vertical_fit_enabled_default_key ?>" <?php echo (($options->get_vertical_fit_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
      <tr>
				<td><?php _e('Gallery Enabled (Displays previous and next navigation arrows):', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->gallery_enabled_default ?>" type="checkbox" id="<?php echo $options->gallery_enabled_default_key ?>" name="<?php echo $options->gallery_enabled_default_key ?>" <?php echo (($options->get_gallery_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
      <tr><td><span class="mg-bold">Gallery Options</span></td></tr>
			<tr>                
				<td><?php _e('Arrow Button Markup*:', 'maxgalleria') ?></td>
				<td>          
					<input data-default="<?php echo $options->arrow_markup_default; ?>" type="text" class="wide" id="<?php echo $options->arrow_markup_default_key ?>" name="<?php echo $options->arrow_markup_default_key ?>" value="<?php echo $options->get_arrow_markup_default() ?>" />
				</td>
			</tr>
			<tr>                
				<td><?php _e('Left Button Title:', 'maxgalleria') ?></td>
				<td>          
					<input data-default="<?php echo $options->prev_button_title_default; ?>" type="text" class="medium" id="<?php echo $options->prev_button_title_default_key ?>" name="<?php echo $options->prev_button_title_default_key ?>" value="<?php echo $options->get_prev_button_title() ?>" />
				</td>
			</tr>
			<tr>                
				<td><?php _e('Right Button Title:', 'maxgalleria') ?></td>
				<td>          
					<input data-default="<?php echo $options->next_button_title_default; ?>" type="text" class="medium" id="<?php echo $options->next_button_title_default_key ?>" name="<?php echo $options->next_button_title_default_key ?>" value="<?php echo $options->get_next_button_title() ?>" />
				</td>
			</tr>      
			<tr>                
				<td><?php _e('Counter Markup*:', 'maxgalleria') ?></td>
				<td>          
					<input data-default="<?php echo $options->counter_markup_default; ?>" type="text" class="wide" id="<?php echo $options->counter_markup_default_key ?>" name="<?php echo $options->counter_markup_default_key ?>" value="<?php echo $options->get_counter_markup() ?>" />
				</td>
			</tr>
           
		</table>
    <p>*<?php _e("Please use only single quotes in markup text.", "maxgalleria") ?></p>
    <p><a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific Popup</a> Copyright (c) 2014 Dmitry Semenov</p>
		
		<?php wp_nonce_field($options->nonce_save_video_tiles_defaults['action'], $options->nonce_save_video_tiles_defaults['name']) ?>
	</form>
</div>

<a id="save-video-tiles-settings" href="#" class="button button-primary"><?php _e('Save Settings', 'maxgalleria') ?></a>
<a id="revert-video-tiles-defaults" href="#" class="button" style="margin-left: 10px;"><?php _e('Revert Defaults', 'maxgalleria') ?></a>
<script>
  jQuery(document).ready(function() {
    
    jQuery("#<?php echo $options->arrow_markup_default_key; ?>").keyup(function() {  
      var a = jQuery(this).val();
      var newTemp = a.replace(/"/g, "'");
      jQuery(this).val(newTemp);
    });        
     
    jQuery("#<?php echo $options->counter_markup_default_key; ?>").keyup(function() {  
      var a = jQuery(this).val();
      var newTemp = a.replace(/"/g, "'");
      jQuery(this).val(newTemp);
    });   
  
	});  
</script>