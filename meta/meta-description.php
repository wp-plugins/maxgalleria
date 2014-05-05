<?php
global $post;
$options = new MaxGalleryOptions($post->ID);

$description_positions = array(
	__('Above Gallery', 'maxgalleria') => 'above',
	__('Below Gallery', 'maxgalleria') => 'below'
);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery(".maxgalleria-meta .accordion.description").accordion({ collapsible: true, active: false, heightStyle: "auto" });
	});
</script>

<div class="maxgalleria-meta">
	<div class="accordion description">
		<h4><?php _e('Description', 'maxgalleria') ?></h4>
		<div>			
			<table>
				<tr>
					<td width="60">
						<label for="<?php echo $options->description_enabled_key ?>"><?php _e('Enabled', 'maxgalleria') ?></label>
					</td>
					<td>
						<input type="checkbox" id="<?php echo $options->description_enabled_key ?>" name="<?php echo $options->description_enabled_key ?>" <?php echo (($options->get_description_enabled() == 'on') ? 'checked' : '') ?> />
					</td>
				</tr>
				<tr>
					<td width="60">
						<?php _e('Location', 'maxgalleria') ?>
					</td>
					<td>
						<select id="<?php echo $options->description_position_key ?>" name="<?php echo $options->description_position_key ?>">
						<?php foreach ($description_positions as $name => $value) { ?>
							<?php $selected = ($options->get_description_position() == $value) ? 'selected="selected"' : ''; ?>
							<option value="<?php echo $value ?>" <?php echo $selected ?>><?php echo $name ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding-top: 10px;">
						<label for="<?php echo $options->description_text_key ?>"><?php _e('Text', 'maxgalleria') ?></label>
						<div style="vertical-align: middle; display: inline-block; color: #808080; font-style: italic; margin-left: 20px;"><?php _e('HTML is allowed', 'maxgalleria') ?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea id="<?php echo $options->description_text_key ?>" name="<?php echo $options->description_text_key ?>"><?php echo $options->get_description_text() ?></textarea>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>