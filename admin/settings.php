<?php
global $maxgalleria;
$settings = $maxgalleria->settings;

$addons = $maxgalleria->get_all_addons();
$templates = $maxgalleria->get_template_addons();
$media_sources = $maxgalleria->get_media_source_addons();

asort($templates);
asort($media_sources);

$addon = isset($_GET['addon']) ? $_GET['addon'] : '';
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery("#save-general-settings").click(function() {
			jQuery("#save-general-settings-success").hide();
			
			var form_data = jQuery("#form-general-settings").serialize();

			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: form_data + "&action=save_general_settings",
				success: function(message) {
					if (message == "success") {
						jQuery("#save-general-settings-success").show();
					}
				}
			});
			
			return false;
		});
	});
</script>

<div id="maxgalleria-admin">
	<div class="wrap">
		<div class="icon32">
			<a href="http://maxgalleria.com" target="_blank"><img src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/maxgalleria-icon-32.png" alt="MaxGalleria" /></a>
		</div>
		
		<h2 class="title"><?php _e('MaxGalleria: Settings', 'maxgalleria') ?></h2>
		
		<div class="clear"></div>
		
		<div class="settings">
			<div class="main">
				<div class="inside">
					<div class="settings-menu">
						<ul>
							<li><!-- Spacer --></li>
							
							<?php if ($addon == '') { ?>
								<li class="selected"><a href="<?php echo MAXGALLERIA_SETTINGS ?>"><?php _e('General', 'maxgalleria') ?></a></li>
							<?php } else { ?>
								<li><a href="<?php echo MAXGALLERIA_SETTINGS ?>"><?php _e('General', 'maxgalleria') ?></a></li>
							<?php } ?>
							
							<?php if (class_exists('MaxGalleriaAlbums')) { ?>
								<?php if ($addon == 'albums') { ?>
									<li class="selected"><a href="<?php echo MAXGALLERIA_SETTINGS . '&addon=albums' ?>"><?php _e('Albums', 'maxgalleria') ?></a></li>
								<?php } else { ?>
									<li><a href="<?php echo MAXGALLERIA_SETTINGS . '&addon=albums' ?>"><?php _e('Albums', 'maxgalleria') ?></a></li>
								<?php } ?>
							<?php } ?>
							
							<li><!-- Spacer --></li>
							<li><strong><?php _e('Templates', 'maxgalleria') ?></strong></li>
							
							<?php foreach ($templates as $template) { ?>
								<li class="<?php echo $template['key'] == $addon ? 'selected' : '' ?>">
									<a href="<?php echo MAXGALLERIA_SETTINGS . '&addon=' . $template['key'] ?>"><?php echo $template['name'] ?></a>
								</li>
							<?php } ?>
							
							<li><!-- Spacer --></li>
							<li><strong><?php _e('Media Sources', 'maxgalleria') ?></strong></li>
							
							<?php foreach ($media_sources as $media_source) { ?>
								<li class="<?php echo $media_source['key'] == $addon ? 'selected' : '' ?>">
									<a href="<?php echo MAXGALLERIA_SETTINGS . '&addon=' . $media_source['key'] ?>"><?php echo $media_source['name'] ?></a>
								</li>
							<?php } ?>
							
							<li><!-- Spacer --></li>
						</ul>
					</div>
					
					<div class="settings-content">
						<?php if ($addon == '') { ?>
							<div id="save-general-settings-success" class="alert alert-success" style="display: none;">
								<?php printf(__('Settings saved. %sRe-save your permalinks%s to avoid "page not found" errors.', 'maxgalleria'), '<a href="' . admin_url() . 'options-permalink.php">', '</a>') ?>
							</div>
							
							<form id="form-general-settings">								
								<div class="settings-title">
									<?php _e('Gallery Rewrite Slug', 'maxgalleria') ?>
								</div>
								
								<div class="settings-options">
									<p><?php _e('The rewrite slug is how WordPress knows to display your galleries.', 'maxgalleria') ?></p>
									<p><?php echo home_url() ?>/<input type="text" id="<?php echo MAXGALLERIA_SETTING_REWRITE_SLUG ?>" name="<?php echo MAXGALLERIA_SETTING_REWRITE_SLUG ?>" value="<?php echo $maxgalleria->settings->get_rewrite_slug() ?>" style="font-size: 13px; width: 80px;" />/<?php _e('gallery-name', 'maxgalleria') ?></p>
								</div>
							
								<div class="settings-title">
									<?php _e('Searchable Galleries', 'maxgalleria') ?>
								</div>
								
								<div class="settings-options">
									<p><?php _e('By default all galleries will appear in your search results; you can disable this using the option below. This setting affects all galleries.', 'maxgalleria') ?></p>
									<p>
										<label for="<?php echo MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH ?>"><?php _e('Exclude from Search:', 'maxgalleria') ?></label>
										<input type="checkbox" style="margin-left: 10px;" id="<?php echo MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH ?>" name="<?php echo MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH ?>" <?php echo (($maxgalleria->settings->get_exclude_galleries_from_search() == 'on') ? 'checked' : '') ?> />
									</p>
								</div>
								
								<?php wp_nonce_field($settings->nonce_save_general_settings['action'], $settings->nonce_save_general_settings['name']) ?>
							</form>
							
							<a id="save-general-settings" href="#" class="button button-primary"><?php _e('Save Settings', 'maxgalleria') ?></a>
						<?php } else { ?>						
							<?php if (class_exists('MaxGalleriaAlbums') && $addon == 'albums') { ?>
								<?php global $maxgalleria_albums ?>
								<?php include_once($maxgalleria_albums->settings) ?>
							<?php } ?>
							
							<?php foreach ($addons as $a) { ?>
								<?php if ($a['key'] == $addon) { ?>
									<?php include_once($a['settings']) ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
					
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
</div>
