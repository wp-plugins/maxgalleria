<?php
global $post;
$options = new MaxGalleriaVideoTilesOptions($post->ID);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		enableDisableThumbClickNewWindow();
    enableDisableOverflowY();
    enableGallery();
		
		jQuery("#<?php echo $options->thumb_click_key ?>").change(function() {
			enableDisableThumbClickNewWindow();
		});

		jQuery("#<?php echo $options->fixed_content_position_key ?>").change(function() {
			enableDisableOverflowY();
		});
    
		jQuery("#<?php echo $options->gallery_enabled_key ?>").change(function() {
			enableGallery();
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
    
		if (thumb_click !== "lightbox") {
			jQuery(".mag-popup-settings").attr("disabled", "disabled");
      enableGallery();
    } else {
			jQuery(".mag-popup-settings").removeAttr("disabled");
      enableGallery();
    }  
    
	}
  
	function enableDisableOverflowY() {
		var fcp_click = jQuery("#<?php echo $options->fixed_content_position_key ?>").val();
        
		if (fcp_click !== 'true') {
			jQuery("#<?php echo $options->overflow_y_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->overflow_y_key ?>").removeAttr("checked");
		}
		else {
			jQuery("#<?php echo $options->overflow_y_key ?>").removeAttr("disabled");
		}
	}
  
	function enableGallery() {
		if (jQuery("#<?php echo $options->gallery_enabled_key ?>").attr("checked") == "checked") {      
			jQuery("#<?php echo $options->arrow_markup_key ?>").removeAttr("disabled");
			jQuery("#<?php echo $options->prev_button_title_key ?>").removeAttr("disabled");
			jQuery("#<?php echo $options->next_button_title_key ?>").removeAttr("disabled");
			jQuery("#<?php echo $options->counter_markup_key ?>").removeAttr("disabled");
		}
		else {
			jQuery("#<?php echo $options->arrow_markup_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->prev_button_title_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->next_button_title_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->counter_markup_key ?>").attr("disabled", "disabled");
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
				<?php _e('Videos Per Page:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" class="small" id="<?php echo $options->videos_per_page_key ?>" name="<?php echo $options->videos_per_page_key ?>" value="<?php echo $options->get_videos_per_page() ?>" />
			</td>
		</tr>
    
		<tr>
			<td>
				<?php _e('Image display order:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo $options->sort_order_key ?>" name="<?php echo $options->sort_order_key ?>">
				<?php foreach ($options->sort_orders as $key => $name) { ?>
					<?php $selected = ($options->get_sort_order() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
        
    <tr><td><span class="mg-bold">Lighbox Settings</span></td></tr>
		<tr>
			<td>
				<label for="<?php echo $options->align_top_enabled_key ?>"><?php _e('Align Top Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" class="mag-popup-settings" id="<?php echo $options->align_top_enabled_key ?>" name="<?php echo $options->align_top_enabled_key ?>" <?php echo (($options->get_align_top_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo $options->bg_click_close_enabled_key ?>"><?php _e('Close on Background Click Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" class="mag-popup-settings" id="<?php echo $options->bg_click_close_enabled_key ?>" name="<?php echo $options->bg_click_close_enabled_key ?>" <?php echo (($options->get_bg_click_close_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo $options->escape_key_enabled_key ?>"><?php _e('Close with Escape Key Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" class="mag-popup-settings" id="<?php echo $options->escape_key_enabled_key ?>" name="<?php echo $options->escape_key_enabled_key ?>" <?php echo (($options->get_escape_key_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Fixed Content Position:', 'maxgalleria') ?>
			</td>
			<td>
				<select class="mag-popup-settings" id="<?php echo $options->fixed_content_position_key ?>" name="<?php echo $options->fixed_content_position_key ?>">
				<?php foreach ($options->content_positions as $key => $name) { ?>
					<?php $selected = ($options->get_fixed_content_position() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>    
		<tr>
			<td>
				<label for="<?php echo $options->hide_close_btn_enabled_key ?>"><?php _e('Hide Close Button:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" class="mag-popup-settings" id="<?php echo $options->hide_close_btn_enabled_key ?>" name="<?php echo $options->hide_close_btn_enabled_key ?>" <?php echo (($options->get_hide_close_btn_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>      
			<td width="300">
				<?php _e('Overflow Y (Displays a vertical scroll bar on the page when fixed content position is "on" or "auto"):', 'maxgalleria') ?>
			</td>
			<td>
				<select class="mag-popup-settings" id="<?php echo $options->overflow_y_key ?>" name="<?php echo $options->overflow_y_key ?>">
				<?php foreach ($options->overflow_y_settings as $key => $name) { ?>
					<?php $selected = ($options->get_overflow_y() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr> 
			<td>
				<?php _e('Popup Custom Class:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" class="mag-popup-settings" id="<?php echo $options->main_class_key ?>" name="<?php echo $options->main_class_key ?>" value="<?php echo $options->get_main_class() ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Removal Delay:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" class="small mag-popup-settings" id="<?php echo $options->removal_delay_key ?>" name="<?php echo $options->removal_delay_key ?>" value="<?php echo $options->get_removal_delay() ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo $options->vertical_fit_enabled_key ?>"><?php _e('Vertical Fit Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" class="mag-popup-settings" id="<?php echo $options->vertical_fit_enabled_key ?>" name="<?php echo $options->vertical_fit_enabled_key ?>" <?php echo (($options->get_vertical_fit_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo $options->gallery_enabled_key ?>"><?php _e('Gallery Enabled (Displays previous and next navigation arrows):', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" class="mag-popup-settings" id="<?php echo $options->gallery_enabled_key ?>" name="<?php echo $options->gallery_enabled_key ?>" <?php echo (($options->get_gallery_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
    <tr><td><span class="mg-bold">Gallery Options</span></td></tr>
		<tr>
			<td>
				<?php _e('Arrow Button Markup:*', 'maxgalleria') ?>
			</td>
			<td>
        <?php 
          $button_markup = trim($options->get_arrow_markup()); 
          if ($button_markup === '')
            $button_markup = $options->arrow_markup_default;          
        ?>
				<input type="text" class="wide mag-popup-settings" id="<?php echo $options->arrow_markup_key ?>" name="<?php echo $options->arrow_markup_key ?>" value="<?php echo $button_markup; ?>" />        
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Left Button Title:', 'maxgalleria') ?>
			</td>
			<td>
        <?php 
          $left_title = trim($options->get_prev_button_title()); 
          if ($left_title === '')
            $left_title = $options->prev_button_title_default;
        ?>
				<input type="text" class="medium mag-popup-settings" id="<?php echo $options->prev_button_title_key ?>" name="<?php echo $options->prev_button_title_key ?>" value="<?php echo $left_title; ?>" />
			</td>
		</tr>    
		<tr>
			<td>
				<?php _e('Right Button Title:', 'maxgalleria') ?>
			</td>
			<td>
        <?php 
          $right_title = trim($options->get_next_button_title()); 
          if ($right_title === '')
            $right_title = $options->next_button_title_default;
        ?>
				<input type="text" class="medium mag-popup-settings" id="<?php echo $options->next_button_title_key ?>" name="<?php echo $options->next_button_title_key ?>" value="<?php echo $right_title; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Counter Markup*:', 'maxgalleria') ?>
			</td>
			<td>
        <?php 
          $counter_markup = trim($options->get_counter_markup()); 
          if ($counter_markup === '')
            $counter_markup = $options->counter_markup_default;
        ?>
				<input type="text" class="wide mag-popup-settings" id="<?php echo $options->counter_markup_key ?>" name="<?php echo $options->counter_markup_key ?>" value="<?php echo $counter_markup; ?>" />
			</td>
		</tr>    
        
	</table>  
  <p>*<?php _e("Please use only single quotes in markup text.", "maxgalleria") ?></p>    
  <p><a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific Popup</a> Copyright (c) 2014 Dmitry Semenov</p>
  
</div>
<script type="text/javascript">		
	jQuery(document).ready(function() {
    
    //replaces double quotes with single quotes so that the markup text
    //does not interfer with the form
    jQuery("#<?php echo $options->arrow_markup_key; ?>").keyup(function() {  
      var a = jQuery(this).val();
      var newTemp = a.replace(/"/g, "'");
      jQuery(this).val(newTemp);
    });        
  
    jQuery("#<?php echo $options->counter_markup_key; ?>").keyup(function() {  
      var a = jQuery(this).val();
      var newTemp = a.replace(/"/g, "'");
      jQuery(this).val(newTemp);
    });        
	});
</script>

