<?php
global $post;
$options = new MaxGalleriaVideoTilesOptions($post->ID);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery(".maxgalleria-meta .accordion.lightbox").accordion({ collapsible: true, active: false, heightStyle: "auto" });
		
		enableDisableLightboxCustomSize();
		
		jQuery("#<?php echo $options->lightbox_video_size_key ?>").change(function() {
			enableDisableLightboxCustomSize();
		});
	});
	
	function enableDisableLightboxCustomSize() {
		video_size = jQuery("#<?php echo $options->lightbox_video_size_key ?>").val();
		
		if (video_size == "full") {
			jQuery("#<?php echo $options->lightbox_video_size_custom_width_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->lightbox_video_size_custom_width_key ?>").attr("readonly", "readonly");
			jQuery("#<?php echo $options->lightbox_video_size_custom_height_key ?>").attr("disabled", "disabled");
			jQuery("#<?php echo $options->lightbox_video_size_custom_height_key ?>").attr("readonly", "readonly");
		}
		
		if (video_size == "custom") {
			jQuery("#<?php echo $options->lightbox_video_size_custom_width_key ?>").removeAttr("disabled");
			jQuery("#<?php echo $options->lightbox_video_size_custom_width_key ?>").removeAttr("readonly");
			jQuery("#<?php echo $options->lightbox_video_size_custom_height_key ?>").removeAttr("disabled");
			jQuery("#<?php echo $options->lightbox_video_size_custom_height_key ?>").removeAttr("readonly");
		}
	}
</script>

<div class="maxgalleria-meta">
	<div style="padding-bottom: 10px;"><?php _e('These settings apply only if the thumbnail click option is set to open a video lightbox.', 'maxgalleria') ?></div>
	
	<div class="accordion lightbox">
		<h4><?php _e('Video Size', 'maxgalleria') ?></h4>
		<div>
			<table>
				<tr>
					<td width="100">
						<?php _e('Video Size', 'maxgalleria') ?>
					</td>
					<td>
						<select class="small" id="<?php echo $options->lightbox_video_size_key ?>" name="<?php echo $options->lightbox_video_size_key ?>">
						<?php foreach ($options->lightbox_sizes as $key => $name) { ?>
							<?php $selected = ($options->get_lightbox_video_size() == $key) ? 'selected="selected"' : ''; ?>
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
						<input type="text" class="small" id="<?php echo $options->lightbox_video_size_custom_width_key ?>" name="<?php echo $options->lightbox_video_size_custom_width_key ?>" value="<?php echo $options->get_lightbox_video_size_custom_width() ?>" /> px
					</td>
				</tr>
				<tr>
					<td width="100">
						<?php _e('Custom Height', 'maxgalleria') ?>
					</td>
					<td>
						<input type="text" class="small" id="<?php echo $options->lightbox_video_size_custom_height_key ?>" name="<?php echo $options->lightbox_video_size_custom_height_key ?>" value="<?php echo $options->get_lightbox_video_size_custom_height() ?>" /> px
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>