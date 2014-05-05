<?php
global $post;
$options = new MaxGalleriaVideoTilesOptions($post->ID);
?>

<div class="maxgalleria-meta">
	<table>
		<tr>
			<td width="60">
				<?php _e('Skin', 'maxgalleria') ?>
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
	</table>
</div>