<?php
$options = new MaxGalleriaImageTilesOptions();
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
    
    //jQuery("#<?php echo $options->counter_markup_default_key; ?>").change(function(){
      
    //jQuery("input#<?php echo $options->counter_markup_default_key; ?>").on('input',function(e){
 	
		jQuery("#save-image-tiles-settings").click(function() {
			jQuery("#save-image-tiles-settings-success").hide();
			
			var form_data = jQuery("#form-image-tiles-settings").serialize();

			// If thumb caption enabled is not checked, we have to add it to form data with an empty value
			if (jQuery("#<?php echo $options->thumb_caption_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->thumb_caption_enabled_default_key ?>=";
			}
			
			// If lightbox caption enabled is not checked, we have to add it to form data with an empty value
			if (jQuery("#<?php echo $options->lightbox_caption_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->lightbox_caption_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->prev_button_title_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->lazy_load_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->vertical_fit_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->vertical_fit_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->content_click_close_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->content_click_close_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->bg_click_close_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->bg_click_close_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->hide_close_btn_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->hide_close_btn_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->escape_key_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->escape_key_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->align_top_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->align_top_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->zoom_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->zoom_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->gallery_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->gallery_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->navigate_by_img_click_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->navigate_by_img_click_enabled_default_key ?>=";
			}
      
			if (jQuery("#<?php echo $options->retina_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->retina_enabled_default_key ?>=";
			}
            
			// Add the action to the form data
			form_data += "&action=save_image_tiles_defaults";

			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: form_data,
				success: function(message) {
					if (message == "success") {
						jQuery("#save-image-tiles-settings-success").show();
					}
				}
			});
			
			return false;
		});
		
		jQuery("#revert-image-tiles-defaults").click(function() {
			jQuery.each(jQuery("input, select", "#form-image-tiles-settings"), function() {
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

<div id="save-image-tiles-settings-success" class="alert alert-success" style="display: none;">
	<?php _e('Settings saved.', 'maxgalleria') ?>
</div>

<div class="settings-title">
	<?php _e('Image Tiles Defaults', 'maxgalleria') ?>
</div>

<div class="settings-options">
	<p class="note"><?php _e('These are the default settings that will be used every time you create a gallery with the Image Tiles template. Each of these settings can be changed per gallery.', 'maxgalleria') ?></p>
	
	<form id="form-image-tiles-settings">
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
				<td><?php _e('Number of Images Per Page:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->images_per_page_default; ?>" type="text" class="small" id="<?php echo $options->images_per_page_default_key ?>" name="<?php echo $options->images_per_page_default_key ?>" value="<?php echo $options->get_images_per_page_default() ?>" />
				</td>
			</tr>
      <tr>
				<td><?php _e('Lazy Load Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->lazy_load_enabled_default ?>" type="checkbox" id="<?php echo $options->lazy_load_enabled_default_key ?>" name="<?php echo $options->lazy_load_enabled_default_key ?>" <?php echo (($options->get_lazy_load_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
			<tr>
				<td><?php _e('Lazy Load Threshold:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->lazy_load_threshold_default; ?>" type="text" class="small" id="<?php echo $options->lazy_load_threshold_default_key ?>" name="<?php echo $options->lazy_load_threshold_default_key ?>" value="<?php echo $options->get_lazy_load_threshold_default() ?>" />
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
				<td><?php _e('Close Content on Click Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->content_click_close_enabled_default ?>" type="checkbox" id="<?php echo $options->content_click_close_enabled_default_key ?>" name="<?php echo $options->content_click_close_enabled_default_key ?>" <?php echo (($options->get_content_click_close_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
      <tr>
				<td><?php _e('Close on Background Click Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->bg_click_close_enabled_default ?>" type="checkbox" id="<?php echo $options->bg_click_close_enabled_default_key ?>" name="<?php echo $options->bg_click_close_enabled_default_key ?>" <?php echo (($options->get_bg_click_close_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
<!--      <tr>
				<td><?php _e('Close Button Inside Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->close_btn_inside_enabled_default ?>" type="checkbox" id="<?php echo $options->close_btn_inside_enabled_default_key ?>" name="<?php echo $options->close_btn_inside_enabled_default_key ?>" <?php echo (($options->get_close_btn_inside_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  -->
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
				<td><?php _e('Lightbox Captions Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->lightbox_caption_enabled_default ?>" type="checkbox" id="<?php echo $options->lightbox_caption_enabled_default_key ?>" name="<?php echo $options->lightbox_caption_enabled_default_key ?>" <?php echo (($options->get_lightbox_caption_enabled_default() == 'on') ? 'checked' : '') ?> />
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
				<td><?php _e('Retina Images Enabled*:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->retina_enabled_default ?>" type="checkbox" id="<?php echo $options->retina_enabled_default_key ?>" name="<?php echo $options->retina_enabled_default_key ?>" <?php echo (($options->get_retina_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
      <tr>
				<td><?php _e('Vertical Fit Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->vertical_fit_enabled_default ?>" type="checkbox" id="<?php echo $options->vertical_fit_enabled_default_key ?>" name="<?php echo $options->vertical_fit_enabled_default_key ?>" <?php echo (($options->get_vertical_fit_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
      <tr>
				<td><?php _e('Zoom Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->zoom_enabled_default ?>" type="checkbox" id="<?php echo $options->zoom_enabled_default_key ?>" name="<?php echo $options->zoom_enabled_default_key ?>" <?php echo (($options->get_zoom_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
			<tr>
				<td><?php _e('Zoom Easing:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->easing_type_default ?>" id="<?php echo $options->easing_type_default_key ?>" name="<?php echo $options->easing_type_default_key ?>">
					<?php foreach ($options->easing_types as $key => $name) { ?>
						<?php $selected = ($options->get_easing_type_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Zoom Duration:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo $options->zoom_duration_default ?>" id="<?php echo $options->zoom_duration_default_key ?>" name="<?php echo $options->zoom_duration_default_key ?>">
					<?php foreach ($options->zoom_durations as $key => $name) { ?>
						<?php $selected = ($options->get_zoom_duration_default() == $key) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
					<?php } ?>
					</select>
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
				<td><?php _e('Navigate By Image Click Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->navigate_by_img_click_enabled_default ?>" type="checkbox" id="<?php echo $options->navigate_by_img_click_enabled_default_key ?>" name="<?php echo $options->navigate_by_img_click_enabled_default_key ?>" <?php echo (($options->get_navigate_by_img_click_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>
			<tr>                
				<td><?php _e('Arrow Button Markup**:', 'maxgalleria') ?></td>
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
				<td><?php _e('Counter Markup**:', 'maxgalleria') ?></td>
				<td>          
					<input data-default="<?php echo $options->counter_markup_default; ?>" type="text" class="wide" id="<?php echo $options->counter_markup_default_key ?>" name="<?php echo $options->counter_markup_default_key ?>" value="<?php echo $options->get_counter_markup() ?>" />
				</td>
			</tr>
            
		</table>
    <p style="font-style:italic">*<?php _e("Enabling Retina Images requires two images both with the same path, a normal resolution image and a high-resolution
       image with a file name ending with '@2x'. Example: image.jpg & image@2x.jpg. Include the normal resolution image in
       a gallery and load high-resolution images directly to the Wordpress Media Library. When Retina Images are enbled then the popup will display the high-resolution
       image on high-dpi screens.", "maxgalleria") ?></p>
    <p>**<?php _e("Please use only single quotes in markup text.", "maxgalleria") ?></p>
    <p><a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific Popup</a> Copyright (c) 2014 Dmitry Semenov</p>

		
		<?php wp_nonce_field($options->nonce_save_image_tiles_defaults['action'], $options->nonce_save_image_tiles_defaults['name']) ?>
	</form>
</div>

<a id="save-image-tiles-settings" href="#" class="button button-primary"><?php _e('Save Settings', 'maxgalleria') ?></a>
<a id="revert-image-tiles-defaults" href="#" class="button" style="margin-left: 10px;"><?php _e('Revert Defaults', 'maxgalleria') ?></a>
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