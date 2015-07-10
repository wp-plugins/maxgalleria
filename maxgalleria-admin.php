<?php
class MaxGalleriaAdmin {
	public function __construct() {
		add_action('admin_menu', array($this, 'add_menu_pages'));
	}
	
	public function add_menu_pages() {
		$edit_page = 'edit.php?post_type=' . MAXGALLERIA_POST_TYPE;
		
		do_action(MAXGALLERIA_ACTION_BEFORE_ADMIN_MENU_PAGES, $edit_page);
		
		$parent_slug = $edit_page;
		$page_title = __('MaxGalleria: NextGEN Importer', 'maxgalleria');
		$sub_menu_title = __('NextGEN Importer', 'maxgalleria');
		$capability = 'upload_files';
		$menu_slug = 'maxgalleria-nextgen-importer';
		$function = array($this, 'add_nextgen_importer_page');
		add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
		
		$parent_slug = $edit_page;
		$page_title = __('MaxGalleria: Settings', 'maxgalleria');
		$sub_menu_title = __('Settings', 'maxgalleria');
		$capability = 'manage_options';
		$menu_slug = 'maxgalleria-settings';
		$function = array($this, 'add_settings_page');
		add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
        
		$parent_slug = $edit_page;
		$page_title = __('MaxGalleria: Support', 'maxgalleria');
		$sub_menu_title = __('Support', 'maxgalleria');
		$capability = 'manage_options';
		$menu_slug = 'maxgalleria-support';
		$function = array($this, 'add_support_page');
		add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
    		
		$parent_slug = $edit_page;
		$capability = 'manage_options';
		$menu_slug = 'mg-admin-notice';
		$function = array($this, 'set_admin_notice_true');    
		add_submenu_page($parent_slug, '', '', $capability, $menu_slug, $function);
    		
		do_action(MAXGALLERIA_ACTION_AFTER_ADMIN_MENU_PAGES, $edit_page);
	}

	public function add_nextgen_importer_page() {
		require_once 'admin/nextgen-importer.php';
	}

	public function add_settings_page() {
		require_once 'admin/settings.php';
	}
	
	public function add_support_page() {
		require_once 'admin/support.php';
	}
  
	public function set_admin_notice_true() {
    
    $current_user_id = get_current_user_id(); 
    
    update_user_meta( $current_user_id, MAXGALLERIA_ADMIN_NOTICE, "off" );
    
    $request = $_SERVER["HTTP_REFERER"];
    
    echo "<script>window.location.href = '" . $request . "'</script>";             
    
	}
  
}
?>