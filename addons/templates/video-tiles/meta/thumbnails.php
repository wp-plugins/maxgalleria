<?php
global $post;
$options = new MaxGalleriaVideoTilesOptions($post->ID);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery(".maxgalleria-meta .accordion.thumbnails").accordion({ collapsible: true, active: false, heightStyle: "auto" });
		
		enableDisableThumbClickNewWindow();
		
		jQuery("#<?php echo $options->thumb_columns_key ?>").val(<?php echo $options->get_thumb_columns() ?>);
		
		jQuery("#thumb_columns_slider").slider({
			range: "min",
			value: <?php echo $options->get_thumb_columns() ?>,
			min: 1,
			max: 10,
			step: 1,
			slide: function(event, ui) {
				jQuery("#thumb_columns_slider_result").html(ui.value);
				jQuery("#<?php echo $options->thumb_columns_key ?>").val(ui.value);
			},
			change: function(event, ui) {
				jQuery("#<?php echo $options->thumb_columns_key ?>").val(ui.value);
			}
		});
		
		jQuery("#<?php echo $options->thumb_click_key ?>").change(function() {
			enableDisableThumbClickNewWindow();
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
</script>

<div class="maxgalleria-meta">
	<div class="accordion thumbnails">
		<h4><?php _e('Columns', 'maxgalleria') ?></h4>
		<div>
			<div align="center">
				<div id="thumb_columns_slider_result" class="value-slider-result"><?php echo $options->get_thumb_columns() ?></div>
				<div id="thumb_columns_slider" class="value-slider"></div>
				<input type="hidden" id="<?php echo $options->thumb_columns_key ?>" name="<?php echo $options->thumb_columns_key ?>" />
			</div>
		</div>
		
		<h4><?php _e('Shape', 'maxgalleria') ?></h4>
		<div>
			<div align="center">
				<select class="large" id="<?php echo $options->thumb_shape_key ?>" name="<?php echo $options->thumb_shape_key ?>">
				<?php foreach ($options->thumb_shapes as $key => $name) { ?>
					<?php $selected = ($options->get_thumb_shape() == $key) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</div>
		</div>
		
		<h4><?php _e('Captions', 'maxgalleria') ?></h4>
		<div>
			<table>
				<tr>
					<td width="60">
						<label for="<?php echo $options->thumb_caption_enabled_key ?>"><?php _e('Enabled', 'maxgalleria') ?></label>
					</td>
					<td>
						<input type="checkbox" id="<?php echo $options->thumb_caption_enabled_key ?>" name="<?php echo $options->thumb_caption_enabled_key ?>" <?php echo (($options->get_thumb_caption_enabled() == 'on') ? 'checked' : '') ?> />
					</td>
				</tr>
				<tr>
					<td width="60">
						<?php _e('Position', 'maxgalleria') ?>
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
			</table>
		</div>
		
		<h4><?php _e('Click', 'maxgalleria') ?></h4>
		<div>
			<table>
				<tr>
					<td colspan="2">
						<strong><?php _e('Thumb Click Should Open', 'maxgalleria') ?></strong>
						<br />
						<select id="<?php echo $options->thumb_click_key ?>" name="<?php echo $options->thumb_click_key ?>">
						<?php foreach ($options->thumb_clicks as $key => $name) { ?>
							<?php $selected = ($options->get_thumb_click() == $key) ? 'selected="selected"' : ''; ?>
							<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $name ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" id="<?php echo $options->thumb_click_new_window_key ?>" name="<?php echo $options->thumb_click_new_window_key ?>" <?php echo (($options->get_thumb_click_new_window() == 'on') ? 'checked' : '') ?> />
					</td>
					<td>
						<label for="<?php echo $options->thumb_click_new_window_key ?>"><?php _e('Open in New Window', 'maxgalleria') ?></label>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<div style="color: #808080; font-style: italic; padding-bottom: 10px;"><?php _e('Does not apply if Video Lightbox is selected.', 'maxgalleria') ?></div>
					</td>
				</tr>
			</table>
		</div>
		
		<h4><?php _e('Custom', 'maxgalleria') ?></h4>
		<div>
			<table>
				<tr>
					<td colspan="2">
						<div style="color: #808080; font-style: italic; padding-bottom: 10px;"><?php _e('These options for developers only.', 'maxgalleria') ?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<strong><?php _e('Image Class', 'maxgalleria') ?></strong>
						<br />
						<input type="text" id="<?php echo $options->thumb_image_class_key ?>" name="<?php echo $options->thumb_image_class_key ?>" value="<?php echo $options->get_thumb_image_class() ?>" />
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
						<strong><?php _e('Image Container Class', 'maxgalleria') ?></strong>
						<br />
						<input type="text" id="<?php echo $options->thumb_image_container_class_key ?>" name="<?php echo $options->thumb_image_container_class_key ?>" value="<?php echo $options->get_thumb_image_container_class() ?>" />
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
						<strong><?php _e('Rel Attribute', 'maxgalleria') ?></strong>
						<br />
						<input type="text" id="<?php echo $options->thumb_image_rel_attribute_key ?>" name="<?php echo $options->thumb_image_rel_attribute_key ?>" value="<?php echo $options->get_thumb_image_rel_attribute() ?>" />
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>