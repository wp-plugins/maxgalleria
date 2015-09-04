<?php
global $maxgalleria_watermark;
$options = new MaxGalleriaMediaLibraryOptions();
?>
<script>
	jQuery(document).ready(function() {
    
    jQuery( "#maxgallery_media_library_default" ).click(function() {
      if(jQuery('#maxgallery_media_library_default').prop('checked') )
        jQuery("#maxgallery_media_library_clear_default").prop("disabled", false);
      else {
        jQuery("#maxgallery_media_library_clear_default").prop("disabled", true);
        jQuery('#maxgallery_media_library_clear_default').prop('checked', false);
      }  
    });


    jQuery("#save-media-library-settings").click(function() {
			jQuery("#save-media-library-settings-success").hide();
      
      var rescan = jQuery('#maxgallery_media_library_clear_default').prop('checked') 
      
      if(rescan)
			  jQuery("#save-media-library-settings-scanning").show();
			
			var form_data = jQuery("#form-media-library-settings").serialize();
            
			if (jQuery("#<?php echo $options->media_library_enabled_default_key ?>").is(":not(:checked)")) {
				form_data += "&<?php echo $options->media_library_enabled_default_key ?>=";
			}
      
      //var turn_mf_on = jQuery('#<?php echo $options->media_library_enabled_default_key ?>').is(":checked");
                  
			form_data += "&action=save_media_library_settings";
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: form_data,
				success: function(message) {
					if (message === "success") {
			      jQuery("#save-media-library-settings-scanning").hide();
						jQuery("#save-media-library-settings-success").show();
            //if(turn_mf_on)
            window.location.reload(true);
					}
				}
			});
			
			return false;
		});

	});
</script>

<div id="save-media-library-settings-success" class="alert alert-success" style="display: none;">
	<?php _e('Settings saved.', 'maxgalleria') ?>
</div>

<div id="save-media-library-settings-scanning" class="alert alert-info" style="display: none;">
	<?php _e('Scanning, please wait...', 'maxgalleria') ?>
</div>


<div class="settings-title">
	<?php _e('Media Library Settings', 'maxgalleria') ?>
</div>

<div class="settings-options">
    
	<form id="form-media-library-settings">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td class="first_col"><?php _e('Activate Media Library:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->media_library_enabled_default_key ?>" type="checkbox" id="<?php echo $options->media_library_enabled_default_key ?>" name="<?php echo $options->media_library_enabled_default_key ?>" <?php echo (($options->get_media_library_default() == 'on') ? 'checked' : '') ?> />
				</td>
			</tr>
			<tr>
				<td class="first_col"><?php _e('Clear and rescan folders:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo $options->media_library_clear_default_key ?>" type="checkbox" id="<?php echo $options->media_library_clear_default_key ?>" name="<?php echo $options->media_library_clear_key ?>" disabled="disabled"  />
				</td>
			</tr>
    </table>
      
		<?php wp_nonce_field($options->nonce_save_media_library_settings['action'], $options->nonce_save_media_library_settings['name']) ?>
      
  </form>   

</div>

<a id="save-media-library-settings" href="#" class="button button-primary"><?php _e('Save Settings', 'maxgalleria') ?></a>
<!--<a id="revert-media-library-defaults" href="#" class="button" style="margin-left: 10px;"><?php _e('Revert Defaults', 'maxgalleria') ?></a>-->
