<?php
global $post;
$maxgallery_options = new MaxGalleryOptions($post->ID);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery(".maxgalleria-meta .accordion.advanced").accordion({ collapsible: true, active: false, heightStyle: "auto" });
	});
</script>

<div class="maxgalleria-meta">
	<div class="accordion advanced">
		<h4><?php _e('Custom Styles', 'maxgalleria') ?></h4>
		<div>			
			<table>
				<tr>
					<td colspan="2">
						<div style="color: #808080; font-style: italic; padding-bottom: 10px;"><?php _e('Add custom styles by using another stylesheet. This stylesheet will be loaded after all other gallery styles.', 'maxgalleria') ?></div>
					</td>
				</tr>
				<tr>
					<td width="60">
						<label for="<?php echo $maxgallery_options->custom_styles_enabled_key ?>"><?php _e('Enabled', 'maxgalleria') ?></label>
					</td>
					<td>
						<input type="checkbox" id="<?php echo $maxgallery_options->custom_styles_enabled_key ?>" name="<?php echo $maxgallery_options->custom_styles_enabled_key ?>" <?php echo (($maxgallery_options->get_custom_styles_enabled() == 'on') ? 'checked' : '') ?> />
					</td>
				</tr>
				<tr>
					<td width="60">
						<?php _e('URL', 'maxgalleria') ?>
					</td>
					<td>
						<input type="text" id="<?php echo $maxgallery_options->custom_styles_url_key ?>" name="<?php echo $maxgallery_options->custom_styles_url_key ?>" value="<?php echo $maxgallery_options->get_custom_styles_url() ?>" />
					</td>
				</tr>
			</table>
		</div>
		
		<h4><?php _e('Custom Scripts', 'maxgalleria') ?></h4>
		<div>			
			<table>
				<tr>
					<td colspan="2">
						<div style="color: #808080; font-style: italic; padding-bottom: 10px;"><?php _e('Add custom scripts by using another JavaScript file. This file will be loaded after all other gallery scripts.', 'maxgalleria') ?></div>
					</td>
				</tr>
				<tr>
					<td width="60">
						<label for="<?php echo $maxgallery_options->custom_scripts_enabled_key ?>"><?php _e('Enabled', 'maxgalleria') ?></label>
					</td>
					<td>
						<input type="checkbox" id="<?php echo $maxgallery_options->custom_scripts_enabled_key ?>" name="<?php echo $maxgallery_options->custom_scripts_enabled_key ?>" <?php echo (($maxgallery_options->get_custom_scripts_enabled() == 'on') ? 'checked' : '') ?> />
					</td>
				</tr>
				<tr>
					<td width="60">
						<?php _e('URL', 'maxgalleria') ?>
					</td>
					<td>
						<input type="text" id="<?php echo $maxgallery_options->custom_scripts_url_key ?>" name="<?php echo $maxgallery_options->custom_scripts_url_key ?>" value="<?php echo $maxgallery_options->get_custom_scripts_url() ?>" />
					</td>
				</tr>
			</table>
		</div>
		
		<h4><?php _e('Reset', 'maxgalleria') ?></h4>
		<div>
			<div style="color: #808080; font-style: italic; padding-bottom: 10px;"><?php _e('Reset all gallery options to their default values. This action cannot be undone.', 'maxgalleria') ?></div>
			<input type="checkbox" id="<?php echo $maxgallery_options->reset_options_key ?>" name="<?php echo $maxgallery_options->reset_options_key ?>" />
			<label for="<?php echo $maxgallery_options->reset_options_key ?>"><?php _e('Yes, I understand', 'maxgalleria') ?></label>
		</div>
	</div>
</div>