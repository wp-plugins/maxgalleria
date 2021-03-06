<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery("#maxgalleria-tabs").tabs();
	});
</script>

<div class="maxgalleria-meta">
	<div id="maxgalleria-tabs">
		<ul>
			<li><a href="#maxgalleria-tabs-media"><?php _e('Images', 'maxgalleria') ?></a></li>
			<li><a href="#maxgalleria-tabs-template"><?php _e('Template', 'maxgalleria') ?></a></li>
			<li><a href="#maxgalleria-tabs-options"><?php _e('Options', 'maxgalleria') ?></a></li>
			<li><a href="#maxgalleria-tabs-description"><?php _e('Description', 'maxgalleria') ?></a></li>
			<li><a href="#maxgalleria-tabs-advanced"><?php _e('Advanced', 'maxgalleria') ?></a></li>
		</ul>
		
		<div id="maxgalleria-tabs-media">
			<?php include_once 'meta-images.php' ?>
		</div>
		<div id="maxgalleria-tabs-template">
			<?php include_once 'meta-template.php' ?>
		</div>
		<div id="maxgalleria-tabs-options">
			<?php include_once 'meta-options.php' ?>
		</div>
		<div id="maxgalleria-tabs-description">
			<?php include_once 'meta-description.php' ?>
		</div>
		<div id="maxgalleria-tabs-advanced">
			<?php include_once 'meta-advanced.php' ?>
		</div>
	</div>
</div>