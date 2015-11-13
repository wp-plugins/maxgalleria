<?php
/*
Add On: Media Library Plus
Description: Gives you the ability to adds folders and move files in the WordPress Media Library.
Author: Max Foundry
Author URI: http://maxfoundry.com

Copyright 2015 Max Foundry, LLC (http://maxfoundry.com)
*/

class MaxGalleriaMediaLib {

  public $upload_dir;
  public $wp_version;
  //public $customdir;
	public $addon_key;
	public $addon_name;
	public $addon_type;
	public $addon_subtype;
	public $addon_settings;
	public $addon_image;
	public $addon_output;
  public $theme_mods;
  public $uploads_folder_name;
	public $uploads_folder_name_length;
	public $uploads_folder_ID;
  

  public function __construct() {
    
		$this->addon_key = 'media-library';
		$this->addon_name = __('Media Library', 'maxgalleria');
		$this->addon_type = 'library';
		$this->addon_subtype = 'image';
		$this->addon_settings = MAXGALLERIA_PLUGIN_DIR . '/addons/media-library/media-library-settings.php';
		//$this->addon_image = MAXGALLERIA_PLUGIN_URL . '/addons/templates/video-tiles/images/video-tiles.png';
		$this->addon_image = '';
		$this->addon_output = array($this, 'get_output');
    
    
		$this->set_global_constants();
		//$this->check_for_update();
		//$this->set_activation_hooks();
		$this->initialize_properties();
		$this->setup_hooks();       
		$this->upload_dir = wp_upload_dir();  
    $this->wp_version = get_bloginfo('version'); 
    
    //convert theme mods into an array
    $theme_mods = get_theme_mods();
    $this->theme_mods = json_decode(json_encode($theme_mods), true);    
        
    add_option( MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER, '0' );    
	}

	public function set_global_constants() {	
		define('MAXGALLERIA_MEDIA_LIBRARY_VERSION_KEY', 'maxgalleria_media_library_version');
		define('MAXGALLERIA_MEDIA_LIBRARY_VERSION_NUM', '0.0.1');
		define('MAXGALLERIA_MEDIA_LIBRARY_IGNORE_NOTICE', 'maxgalleria_media_library_ignore_notice');
		//define('MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
		define('MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_DIR', MAXGALLERIA_PLUGIN_DIR . '/addons/media-library' );
		define('MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_URL', MAXGALLERIA_PLUGIN_URL . '/addons/media-library');
    define("MAXGALLERIA_MEDIA_LIBRARY_NONCE", "mgmlp_nonce");
    define("MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE", "mgmlp_media_folder");
    define("MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME", "mgmlp_upload_folder_name");
    define("MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID", "mgmlp_upload_folder_id");
    define("MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE", "mgmlp_folders");
    define("MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER", "mgmlp_sort_order");
    define("NEW_MEDIA_LIBRARY_VERSION", "4.0.0");
    
		
		// Bring in all the actions and filters
		require_once MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_DIR . '/media-library-hooks.php';
	}
  
 	public function set_activation_hooks() {
    //register_activation_hook(__FILE__, array($this,'add_folder_table'));    
		//register_activation_hook(__FILE__, array($this, 'do_activation'));
		//register_deactivation_hook(__FILE__, array($this, 'do_deactivation'));
	}
  
//  public function do_activation($network_wide) {
//		if ($network_wide) {
//			$this->call_function_for_each_site(array($this, 'activate'));
//		}
//		else {
//			$this->activate();
//		}
//	}
	
//	public function do_deactivation($network_wide) {	
//		if ($network_wide) {
//			$this->call_function_for_each_site(array($this, 'deactivate'));
//		}
//		else {
//			$this->deactivate();
//		}
//	}
  
	public function activate($clear_status) {
		//update_option(MAXGALLERIA_MEDIA_LIBRARY_VERSION_KEY, MAXGALLERIA_MEDIA_LIBRARY_VERSION_NUM);
    update_option('uploads_use_yearmonth_folders', 1);    
    $this->add_folder_table();
    if ( 'impossible_default_value_1234' === get_option( MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, 'impossible_default_value_1234' ) ) {
      $this->scan_attachments(false);
      $this->admin_check_for_new_folders(true);
    } else {
      if($clear_status) {
        $this->scan_attachments($clear_status);
        $this->admin_check_for_new_folders(true);
      }  
    }  
        
    if ( ! wp_next_scheduled( 'new_folder_check' ) )
      wp_schedule_event( time(), 'daily', 'new_folder_check' );
    
	}
  
  public function deactivate() {
    wp_clear_scheduled_hook('new_folder_check');
	}
    
  public function enqueue_admin_print_styles() {		
    if(isset($_REQUEST['page'])) {
        if($_REQUEST['page'] === 'media-library' || $_REQUEST['page'] === 'search-library') {
          wp_enqueue_style('thickbox');
          wp_enqueue_style('maxgalleria-media-library', MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_URL . '/media-library.css');
          wp_enqueue_style('foundation', MAXGALLERIA_PLUGIN_URL . '/libs/foundation/foundation.min.css');
      }
    }
	}
  
  public function enqueue_admin_print_scripts() {
    if(isset($_REQUEST['page'])) {
      if($_REQUEST['page'] === 'media-library') {
        wp_register_script( 'loader-folders', MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_URL . '/js/mgmlp-loader.js', array( 'jquery' ), '', true );

        wp_localize_script( 'loader-folders', 'mgmlp_ajax', 
              array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
                     'nonce'=> wp_create_nonce(MAXGALLERIA_MEDIA_LIBRARY_NONCE))
                   ); 

        wp_enqueue_script('loader-folders');
      }
    }
  }
 
	public function initialize_properties() {
		require_once 'media-library-options.php';
	}
  
  public function setup_hooks() {
		//add_action('init', array($this, 'load_textdomain'));
	  add_action('init', array($this, 'register_mgmlp_post_type'));
	  add_action('admin_init', array($this, 'ignore_notice'));
		add_action('admin_notices', array($this, 'show_admin_notice'));
		add_action('admin_print_styles', array($this, 'enqueue_admin_print_styles'));
		add_action('admin_print_scripts', array($this, 'enqueue_admin_print_scripts'));
    add_action('admin_menu', array($this, 'setup_mg_media_plus'));
    
		add_action('wp_ajax_save_media_library_settings', array($this, 'save_media_library_settings'));
		add_action('wp_ajax_nopriv_save_media_library_settings', array($this, 'save_media_library_settings'));
            
    add_action('wp_ajax_nopriv_create_new_folder', array($this, 'create_new_folder'));
    add_action('wp_ajax_create_new_folder', array($this, 'create_new_folder'));
    
    add_action('wp_ajax_nopriv_delete_maxgalleria_media', array($this, 'delete_maxgalleria_media'));
    add_action('wp_ajax_delete_maxgalleria_media', array($this, 'delete_maxgalleria_media'));
    
    add_action('wp_ajax_nopriv_upload_attachment', array($this, 'upload_attachment'));
    add_action('wp_ajax_upload_attachment', array($this, 'upload_attachment'));
    
    add_action('wp_ajax_nopriv_copy_media', array($this, 'copy_media'));
    add_action('wp_ajax_copy_media', array($this, 'copy_media'));
        
    add_action('wp_ajax_nopriv_move_media', array($this, 'move_media'));
    add_action('wp_ajax_move_media', array($this, 'move_media'));
    
    add_action('wp_ajax_nopriv_add_to_max_gallery', array($this, 'add_to_max_gallery'));
    add_action('wp_ajax_add_to_max_gallery', array($this, 'add_to_max_gallery'));
    
    add_action('wp_ajax_nopriv_maxgalleria_rename_image', array($this, 'maxgalleria_rename_image'));
    add_action('wp_ajax_maxgalleria_rename_image', array($this, 'maxgalleria_rename_image'));
        
    add_action('wp_ajax_nopriv_sort_contents', array($this, 'sort_contents'));
    add_action('wp_ajax_sort_contents', array($this, 'sort_contents'));
        
    add_action( 'new_folder_check', array($this,'admin_check_for_new_folders'));
    
    add_action( 'add_attachment', array($this,'add_attachment_to_folder'));
    
    add_action( 'delete_attachment', array($this,'delete_folder_attachment'));
    
    //register_deactivation_hook( __FILE__, array($this, 'run_on_deactivate') );
        
    //register_activation_hook(__FILE__, array($this,'add_folder_table'));    
                                  
  }
  
  public function save_media_library_settings() {
		$options = new MaxGalleriaMediaLibraryOptions();
		
		if (isset($_POST) && check_admin_referer($options->nonce_save_media_library_settings['action'], $options->nonce_save_media_library_settings['name'])) {
			global $maxgalleria;
			$message = '';
      $activate_status = false;
      $clear_status = false;
            
			foreach ($_POST as $key => $value) {
				if ($maxgalleria->common->string_starts_with($key, 'maxgallery_')) {
					update_option($key, $value);
          if($key === 'maxgallery_media_library_default') {
            if($value === 'on')
              $activate_status = true;
          }          
          if($key === 'maxgallery_media_librarys_clear') {
            if($value === 'on')
              $clear_status = true;
          }          
				}
			}
      if($activate_status === true) 
        $this->activate($clear_status);
      else  
        $this->deactivate();            
			
			$message = 'success';
			
			echo $message;
			die();
		}
    
  }

  public function delete_folder_attachment ($postid) {    
    global $wpdb;
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    $where = array( 'post_id' => $postid );
    $wpdb->delete( $table, $where );    
  }

    // in case an image is uploaded in the WP media library we
  // need to add a record to the mgmlp_folders table
  public function add_attachment_to_folder ($post_id) {
    
    $folder_id = $this->get_default_folder();
    if($folder_id !== false) {
      $this->add_new_folder_parent($post_id, $folder_id);
    }  
  }
  
  public function get_default_folder() {
    global $wpdb;

		
		if( get_option('uploads_use_yearmonth_folders') === false)
			return $this->uploads_folder_ID;
		
    $base_url = $this->upload_dir['baseurl'];
    $year_month = date("m");    
    $year = date("Y");    
    $guid = $base_url . '/' . $year . '/' . $year_month;
    
    if($this->is_windows())
      $guid = str_replace('\\', '/', $guid);      
    
    $sql = "select ID from $wpdb->prefix" . "posts where guid = '$guid'";
    
    $row = $wpdb->get_row($sql);
    if($row) {
      return $row->ID;
    }
    else {
      return false;
    }
    
  }

  public function register_mgmlp_post_type() {
    
		$args = apply_filters(MGMLP_FILTER_POST_TYPE_ARGS, array(
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => false,
      'show_in_nav_menus' => false,
      'show_in_admin_bar' => false,
			'show_in_menu' => false,
			'query_var' => true,
			'hierarchical' => true,
			'supports' => false,
			'exclude_from_search' => true
		));
		
		register_post_type(MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE, $args);
    
  }
    
  public function add_folder_table () {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    $sql = "CREATE TABLE IF NOT EXISTS " . $table . " ( 
  `post_id` bigint(20) NOT NULL,
  `folder_id` bigint(20) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";	
 
    dbDelta($sql);
    
  }
    
  public function upload_attachment () {
                  
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_NONCE)) {
      exit(__('missing nonce!','maxgalleria'));
    }
    
    $uploads_path = wp_upload_dir();
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = 0;
    
    $destination = $this->get_folder_path($folder_id);
    
    if ( 0 < $_FILES['file']['error'] ) {
      echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
      
      // insure it has a unique name
      $new_filename = wp_unique_filename( $destination, $_FILES['file']['name'], null );
      
      $filename = $destination . DIRECTORY_SEPARATOR . $new_filename;
      if( move_uploaded_file($_FILES['file']['tmp_name'], $filename) ) {
        
        // Set correct file permissions.
	      $stat = stat( dirname( $filename ));
        $perms = $stat['mode'] & 0000666;
        @ chmod( $filename, $perms );
        
        $this->add_new_attachment($filename, $folder_id);

        $this->display_folder_contents ($folder_id);
        
      }
    }
        
    die();
  }
      
  public function add_new_attachment($filename, $folder_id) {

    $parent_post_id = 0;
    remove_action( 'add_attachment', array($this,'add_attachment_to_folder'));

    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $filename ), null );

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();
    
    $file_url = $this->get_file_url_for_copy($filename);
            
    // Prepare an array of post data for the attachment.
    $attachment = array(
      'guid'           => $file_url, 
      'post_mime_type' => $filetype['type'],
      'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
  		'post_parent'    => 0,
      'post_content'   => '',
      'post_status'    => 'inherit'
    );
    
    // Insert the attachment.
    $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );    

    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        
    wp_update_attachment_metadata( $attach_id, $attach_data );

    if($this->is_windows()) {
      
      // get the uploads dir name
      $basedir = $this->upload_dir['baseurl'];
      $uploads_dir_name_pos = strrpos($basedir, '/');
      $uploads_dir_name = substr($basedir, $uploads_dir_name_pos+1);
    
      //find the name and cut off the part with the uploads path
      $string_position = strpos($filename, $uploads_dir_name);
      $uploads_dir_length = strlen($uploads_dir_name) + 1;
      $uploads_location = substr($filename, $string_position+$uploads_dir_length);
      $uploads_location = str_replace('\\','/', $uploads_location);      
      
      // put the short path into postmeta
	    $media_file = get_post_meta( $attach_id, '_wp_attached_file', true );
    
      if($media_file !== $uploads_location )
        update_post_meta( $attach_id, '_wp_attached_file', $uploads_location );
    }

    $this->add_new_folder_parent($attach_id, $folder_id );
    add_action( 'add_attachment', array($this,'add_attachment_to_folder'));
    
    return $attach_id;
    
  }
  
  public function scan_attachments ($clear_status) {
    
    global $wpdb;
    
    if($clear_status) {  
      $sql = "TRUNCATE TABLE $wpdb->prefix" . "mgmlp_folders";
      $wpdb->query($sql);
      $sql = "delete from $wpdb->prefix" . "posts where post_type = 'mgmlp_media_folder'";
      $wpdb->query($sql);
    }
    
    $uploads_path = wp_upload_dir();
    
    if(!$uploads_path['error']) {
            
      //find the uploads folder
      $base_url = $uploads_path['baseurl'];
      $last_slash = strrpos($base_url, '/');
      $uploads_dir = substr($base_url, $last_slash+1);
			$this->uploads_folder_name = $uploads_dir;
			$this->uploads_folder_name_length = strlen($uploads_dir);
      
      update_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, $uploads_dir);
                              
      //create uploads parent media folder      
      $uploads_parent_id = $this->add_media_folder($uploads_dir, 0, $base_url);
      update_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID, $uploads_parent_id);
      
      $sql = "select ID, guid from $wpdb->prefix" . "posts where post_type = 'attachment' order by guid";

      $rows = $wpdb->get_results($sql);
      
      $current_folder = "";
            
      $parent_id = $uploads_parent_id;
     
      if($rows) {
        foreach($rows as $row) {
					
					$image_location = $this->check_for_attachment_id($row->guid, $row->ID);
					          
          if(strpos($image_location, $uploads_dir)) {
										                    
            $sub_folders = $this->get_folders($image_location); //$row->guid
            $attachment_file = array_pop($sub_folders);  

            $uploads_length = strlen($uploads_dir);
            $new_folder_pos = strpos($image_location, $uploads_dir ); //$row->guid
            $folder_path = substr($image_location, 0, $new_folder_pos+$uploads_length ); //$row->guid

            foreach($sub_folders as $sub_folder) {
              
              // check for URL path in database
              $folder_path = $folder_path . '/' . $sub_folder;
              
              $new_parent_id = $this->folder_exist($folder_path);
              if($new_parent_id === false) {
                if($this->is_new_top_level_folder($uploads_dir, $sub_folder, $folder_path)) {
                  $parent_id = $this->add_media_folder($sub_folder, $uploads_parent_id, $folder_path); 
                }  
                else {
                  $parent_id = $this->add_media_folder($sub_folder, $parent_id, $folder_path); 
                }  
              }  
              else
                $parent_id = $new_parent_id;
            }  
						
            $this->add_new_folder_parent($row->ID, $parent_id );
					 
          } //test for ?
          
        } //foreach         
        
      } //rows  
            
    }
        
  }
     
  private function is_new_top_level_folder($uploads_dir, $folder_name, $folder_path) {
    
    $needle = $uploads_dir . '/' . $folder_name;
    if(strpos($folder_path, $needle))
      return true;
    else
      return false;   
  }

  private function get_folders($path) {
    $sub_folders = explode('/', $path);
    while( $sub_folders[0] !== $this->uploads_folder_name )
      array_shift($sub_folders);
    
    if($sub_folders[0] === $this->uploads_folder_name) 
      array_shift($sub_folders);
      
    return $sub_folders;
  }
  
  private function folder_exist($folder_path) {
    
    global $wpdb;    
                
    $sql = "select ID from " . $wpdb->prefix . "posts where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE . "' and guid =  '$folder_path'";
    
    $row = $wpdb->get_row($sql);            
    if($row === null)
      return false;
    else
      return $row->ID;
             
  }
  
  private function add_media_folder($folder_name, $parent_folder, $base_path ) {
    
    global $wpdb;    
    $table = $wpdb->prefix . "posts";	    
        
    $new_folder_id = $this->mpmlp_insert_post(MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE, 
            $folder_name, $base_path, 'publish' );
        
    $this->add_new_folder_parent($new_folder_id, $parent_folder);
        
    return $new_folder_id;
        
  }
  
  private function add_new_folder_parent($record_id, $parent_folder) {
    
    global $wpdb;    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    
      $new_record = array( 
			  'post_id'   => $record_id, 
			  'folder_id' => $parent_folder 
			);
      
      $wpdb->insert( $table, $new_record );
          
  }
    
  public function setup_mg_media_plus() {
    
    if(get_option('maxgallery_media_library_default') === 'on') {
      remove_menu_page( 'upload.php' );
      add_menu_page(__('Media Library +','maxgalleria'), __('Media Library +','maxgalleria'), 'upload_files', 'media-library', array($this, 'media_library'), MAXGALLERIA_PLUGIN_URL . '/images/maxgalleria-icon-16.png', 10 );
      add_submenu_page(null, 'Check For New Folders', 'Check For New Folders', 'upload_files', 'check-for-new-folders', array($this, 'check_for_new_folders'));
      add_submenu_page(null, 'Search Library', 'Search Library', 'upload_files', 'search-library', array($this, 'search_library'));
      add_submenu_page('media-library', __('Add New Folders','maxgalleria'), __('Add New Folders','maxgalleria'), 'upload_files', 'admin-check-for-new-folders', array($this, 'admin_check_for_new_folders'));
      add_submenu_page('media-library', __('WP Media Library','maxgalleria'), __('WP Media Library','maxgalleria'), 'upload_files', 'upload.php');
      //add_submenu_page('media-library', __('Scan','maxgalleria'), __('Scan','maxgalleria'), 'upload_files', 'scan-attachments', array($this, 'scan_attachments'));
    }  
  }
  
//	public function load_textdomain() {
//		load_plugin_textdomain('maxgalleria', false, dirname(plugin_basename(__FILE__)) . '/languages/');
//	}
  
	public function ignore_notice() {
		if (current_user_can('install_plugins')) {
			global $current_user;
			
			if (isset($_GET['maxgalleria-media-library-ignore-notice']) && $_GET['maxgalleria-media-library-ignore-notice'] == 1) {
				add_user_meta($current_user->ID, MAXGALLERIA_MEDIA_LIBRARY_IGNORE_NOTICE, true, true);
			}
		}
	}

	public function show_admin_notice() {
		if (!class_exists('MaxGalleria')) {
			if (current_user_can('install_plugins')) {
				global $current_user;
				
				if (!get_user_meta($current_user->ID, MAXGALLERIA_MEDIA_LIBRARY_IGNORE_NOTICE)) {
					echo '<div class="error">';
					echo '	<p>';
					printf(__('MaxGalleria is not installed/activated; therefore, the Media Library addon will not function properly until it is. | <a href="%1$s">Hide Notice</a>', 'maxgalleria'), '?maxgalleria-media-library-ignore-notice=1');
					echo '	</p>';
					echo '</div>';
				}
			}
		}
	}
    
  public function media_library () {
    
    global $wpdb;
    
    ?>      
<!-- code for fb share buttons
      <div id="fb-root"></div>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=636262096435499";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>-->
    <?php
    
    $sort_order = get_option( MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER );    
        
    if ((isset($_GET['media-folder'])) && (strlen(trim($_GET['media-folder'])) > 0)) {
      $current_folder_id = trim(stripslashes(strip_tags($_GET['media-folder'])));
      if(!is_numeric($current_folder_id)) {
        $current_folder = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
        $current_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );        
	      $this->uploads_folder_name = $current_folder;
	      $this->uploads_folder_name_length = strlen($current_folder);
	      $this->uploads_folder_ID = $current_folder_id;				
      }
      else
        $current_folder = $this->get_folder_name($current_folder_id);
    } else {             
      $current_folder = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
      $current_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
			$this->uploads_folder_name = $current_folder;
			$this->uploads_folder_name_length = strlen($current_folder);
			$this->uploads_folder_ID = $current_folder_id;				
    }  
            
    ?>


      <div id="wp-media-grid" class="wrap">
        <!--empty h2 for where WP notices will appear--> 
        <h2></h2>
        <div class="media-plus-toolbar"><div class="media-toolbar-secondary">  
            
        <div id='mgmlp-title-area'>
          <h2 class='mgmlp-title'><?php _e('Media Library Plus', 'maxgalleria-media-library' ); ?> </h2>    
          <div class="mgmlp-title" id='mg-prono-top'>
            <div><?php _e('Brought to you by', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxfoundry.com"> <img alt="Max Foundry" src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_URL ?>/images/max-foundry-new.png"></a> <?php _e('makers of', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxbuttons.com/?ref=mbpro">MaxButtons</a> <?php _e('and', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxinbound.com/?ref=mbpro">MaxInbound</a></div>
            <!--<div class="fb-like" data-href="https://www.facebook.com/maxfoundry" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>-->              
          </div>      
        </div>    
            
        <div class="clearfix"></div>  
                        
          <!--<a id="mgmlp-scan_folders">Scan Folders</a>-->  
          
          <div id="mgmlp-library-container">
            <div id="alwrap">
              <div style="display:none" id="ajaxloader"></div>
            </div>
            <?php 

            $folder_location = $this->get_folder_path($current_folder_id);

            $folders_path = "";
            $parents = $this->get_parents($current_folder_id);

            $folder_count = count($parents);
            $folder_counter = 0;        
            $current_folder_string = site_url() . "/wp-content";
            foreach( $parents as $key => $obj) { 
              $folder_counter++;
              if($folder_counter === $folder_count)
                $folders_path .= $obj['name'];      
              else
                $folders_path .= '<a folder="' . $obj['id'] . '" class="media-link">' . $obj['name'] . '</a>/';      
              $current_folder_string .= '/' . $obj['name'];
            }
            
            $sql = "select ID, guid from $wpdb->prefix" . "posts where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE . "' order by guid";
            //echo $sql;
            $folder_list = "";
            $rows = $wpdb->get_results($sql);
            
            if($rows) {
              foreach ($rows as $row) {
																
					      $image_location = $this->check_for_attachment_id($row->guid, $row->ID);
								
                if($current_folder_string !== $image_location)
                  $folder_list .='<option value="' . $row->ID . '">' . $image_location . '</option>' . PHP_EOL;
              }
            }
            
            echo "<h3 id='mgmlp-breadcrumbs'>" . __('Location:','maxgalleria') . " $folders_path</h3>";
            
            echo '<div id="mgmlp-toolbar">' . PHP_EOL;
            
            echo '  <a id="add-new_attachment" help="' . __('Upload new files.','maxgalleria') . '" class="gray-blue-link" href="javascript:slideonlyone(\'add-new-area\');">' . __('Add New','maxgalleria') . '</a>' . PHP_EOL;
            
            echo '  <a id="add-new-folder" help="' . __('Create a new folder. Type in a folder name (do not use spaces) and click Create Folder.','maxgalleria') . '"  class="gray-blue-link" href="javascript:slideonlyone(\'new-folder-area\');">' .  __('New Folder','maxgalleria') . '</a>' . PHP_EOL;
            
            echo '  <a id="rename-file" help="' . __('Rename a file; select only one file. Folders cannot be renamed. Type in a new name with no spaces and without the extention and click Rename.','maxgalleria') . '" class="gray-blue-link" href="javascript:slideonlyone(\'rename-area\');">' .  __('Rename','maxgalleria') . '</a>' . PHP_EOL;
            
            echo '  <a id="copy-files" help="' . __('Copy selected files to another folder.','maxgalleria') . '" class="gray-blue-link" href="javascript:slideonlyone(\'copy-area\');">' .  __('Copy','maxgalleria') . '</a>' . PHP_EOL;
            
            echo '  <a id="move-files" help="' . __('Move selected files to a different folder. <span class=\'mlp-warning\'>Images already in existing pages or blog posts will not display if they are moved from their current location unless you deleted and reinsert them after they have been moved.</span>','maxgalleria-media-library') . '" class="gray-blue-link" href="javascript:slideonlyone(\'move-area\');">' .  __('Move','maxgalleria') . '</a>' . PHP_EOL;
            
            echo '  <a id="add-images-to-gallery" help="' . __('Add image files to a Maxgalleria image gallery. Folders can not be added to a gallery. Images already in the gallery will not be added. ','maxgalleria') . '" class="gray-blue-link" href="javascript:slideonlyone(\'gallery-area\');">' .  __('Add to Gallery','maxgalleria') . '</a>' . PHP_EOL;
                        
            echo '  <a id="delete-media" help="' . __('Delete selected files or selected folders if the folders are empty.','maxgalleria') . '" class="gray-blue-link" >' .  __('Delete','maxgalleria') . '</a>' . PHP_EOL;
                        
            echo '  <a id="select-media" help="' . __('Select or unselect all items in the folder.','maxgalleria') . '" class="gray-blue-link" >' .  __('Select/Unselect','maxgalleria') . '</a>' . PHP_EOL;
            
            echo '  <div id="sort-wrap"><select id="mgmlp-sort-order">' . PHP_EOL;
            echo '    <option value="0" ' . ($sort_order === '0' ? 'selected="selected"' : ''  ). '>' . __('Sort by Date','maxgalleria') . '</option>' . PHP_EOL;
            echo '    <option value="1" ' . ($sort_order === '1' ? 'selected="selected"' : ''  ). '>' . __('Sort by Name','maxgalleria') . '</option>' . PHP_EOL;
            echo '  </select></div>' . PHP_EOL;
                                    
            echo '  <div id="search-wrap"><input type="search" placeholder="Search" id="mgmlp-media-search-input" class="search"></div>' . PHP_EOL;            
                        
            echo '</div>' . PHP_EOL;           
            
            echo '  <div id="folder-message">' . PHP_EOL;
            echo '  </div>' . PHP_EOL;
            
            echo '<div id="add-new-area" class="input-area">' . PHP_EOL;
            echo '  <div id="dragandrophandler">' . PHP_EOL;
            echo '    <div>Drag & Drop Files Here</div>' . PHP_EOL;
            echo '    <div id="upload-text">or select an image to upload:</div>' . PHP_EOL;
            echo '    <input type="file" name="fileToUpload" id="fileToUpload">' . PHP_EOL;  
            echo '    <input type="hidden" name="folder_id" id="folder_id" value="' . $current_folder_id . '">' . PHP_EOL;
            echo '    <input type="button" value="Upload Image" id="mgmlp_ajax_upload" name="submit_image">' . PHP_EOL;
            echo '  </div>' . PHP_EOL;
            echo '</div>' . PHP_EOL;
            echo '<div class="clearfix"></div>' . PHP_EOL;
            
            echo '<div id="rename-area" class="input-area">' . PHP_EOL;
            echo '  <div id="rename-box">' . PHP_EOL;
            echo __('File Name: ','maxgalleria') . '<input type="text" name="new-file-name" id="new-file-name" value="" />' . PHP_EOL;
            echo '<div class="btn-wrap"><a id="mgmlp-rename-file" class="gray-blue-link" >'. __('Rename','maxgalleria') .'</a></div>' . PHP_EOL;
            echo '  </div>' . PHP_EOL;
            echo '</div>' . PHP_EOL;
            echo '<div class="clearfix"></div>' . PHP_EOL;
            
            
            echo '<div id="copy-area" class="input-area">' . PHP_EOL;
            echo '  <div id="copy-box">' . PHP_EOL;
            echo __('Copy files to: ','maxgalleria') . PHP_EOL;
            echo '    <select id="copy-select">' . PHP_EOL;
            echo        $folder_list;
            echo '    </select>' . PHP_EOL;
            echo '<div class="btn-wrap"><a id="copy-media" class="gray-blue-link" >'. __('Copy files','maxgalleria') .'</a></div><br>' . PHP_EOL;
            echo '  </div>' . PHP_EOL;
            echo '</div>' . PHP_EOL;
            echo '<div class="clearfix"></div>' . PHP_EOL;
            
            
            echo '<div id="move-area" class="input-area">' . PHP_EOL;
            echo '  <div id="move-box">' . PHP_EOL;
            echo __('Move files to: ','maxgalleria') . '<select id="move-select">' . PHP_EOL;
            echo        $folder_list;
            echo '    </select>' . PHP_EOL;
            echo '<div class="btn-wrap"><a id="move-media" class="gray-blue-link" >'. __('Move files','maxgalleria') .'</a></div>' . PHP_EOL;                                  
            echo '  </div>' . PHP_EOL;
            echo '</div>' . PHP_EOL;
            echo '<div class="clearfix"></div>' . PHP_EOL;            
           
            echo '<div id="gallery-area" class="input-area">' . PHP_EOL;
            echo '  <div id="gallery-box">' . PHP_EOL;
            $sql = "select ID, post_title 
from $wpdb->prefix" . "posts 
LEFT JOIN $wpdb->prefix" . "postmeta ON($wpdb->prefix" . "posts.ID = $wpdb->prefix" . "postmeta.post_id)
where post_type = 'maxgallery' 
and $wpdb->prefix" . "postmeta.meta_key = 'maxgallery_type'
and $wpdb->prefix" . "postmeta.meta_value = 'image'
order by post_name";
            //echo $sql;
            $gallery_list = "";
            $rows = $wpdb->get_results($sql);
            
            if($rows) {
              foreach ($rows as $row) {
                $gallery_list .='<option value="' . $row->ID . '">' . $row->post_title . '</option>' . PHP_EOL;
              }
            }
            echo '    <select id="gallery-select">' . PHP_EOL;
            echo        $gallery_list;
            echo '    </select>' . PHP_EOL;
            echo '<div class="btn-wrap"><a id="add-to-gallery" class="gray-blue-link" >'. __('Add Images','maxgalleria') .'</a></div>' . PHP_EOL;
                       
            
            echo '  </div>' . PHP_EOL;
            echo '</div>' . PHP_EOL;
            echo '<div class="clearfix"></div>' . PHP_EOL;            
                        
            echo '<div id="new-folder-area" class="input-area">' . PHP_EOL;
            echo '  <div id="new-folder-box">' . PHP_EOL;
            echo '<input type="hidden" id="current-folder-id" value="' . $current_folder_id . '" />' . PHP_EOL;
            echo __('Folder Name: ','maxgalleria') . '<input type="text" name="new-folder-name" id="new-folder-name" value="" />' . PHP_EOL;
            echo '<div class="btn-wrap"><a id="mgmlp-create-new-folder" class="gray-blue-link" >'. __('Create Folder','maxgalleria') .'</a></div>' . PHP_EOL;
            echo '  </div>' . PHP_EOL;                        
            echo '</div>' . PHP_EOL;
            echo '<div class="clearfix"></div>' . PHP_EOL;
                        
            echo '<div id="mgmlp-file-container">' . PHP_EOL;
              $this->display_folder_contents ($current_folder_id);
            echo '</div>' . PHP_EOL;
                        
            ?>
            <script>

            jQuery(document).on("click", ".media-link", function () {

              var folder = jQuery(this).attr('folder');

              var home_url = "<?php echo site_url(); ?>"; 

              window.location.href = home_url + '/wp-admin/admin.php?page=media-library&' + 'media-folder=' + folder;

            });
            
            
            jQuery('#mgmlp-media-search-input').keydown(function (e){
              if(e.keyCode == 13){
                
                var search_value = jQuery('#mgmlp-media-search-input').val();
                
                var home_url = "<?php echo site_url(); ?>"; 

                window.location.href = home_url + '/wp-admin/admin.php?page=search-library&' + 's=' + search_value;
                
              }  
            })    
            
            </script>  

          </div>  
          
          <div class="clearfix"></div>

          <div class="large-12">
            <div class="mg-promo">
            <p class="mg-promo-title"><a target="_blank" href="http://maxgalleria.com/shop/category/addons/?utm_source=mlefree&utm_medium=tout&utm_campaign=tout ">Try these terrific MaxGalleria Addons<br>Every Addon for $49 or any single Addon for $29 for 1 site</a></p>
            <div class="small-6 medium-6 large-6 columns sources">
            <p class="section-title"><span>Layout Addons</span></p>
            <div class="row top-margin">
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-image-carousel/?utm_source=mlefree&amp;utm_medium=image-carousel&amp;utm_campaign=image-carousel"><img width="200" height="200" title="MaxGalleria Image Carousel Addon" alt="MaxGalleria Image Carousel Addon" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-image-carousel-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-image-carousel/?utm_source=mlefree&amp;utm_medium=image-carousel&amp;utm_campaign=image-carousel">Image Carousel</a></h3><p>Turn your galleries into carousels</p>
              </div>
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-albums/?utm_source=mlefree&amp;utm_medium=albums&amp;utm_campaign=albums"><img width="200" height="200" title="MaxGalleria Albums Addon" alt="MaxGalleria Albums Addon" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-albums-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-image-carousel/?utm_source=mlefree&amp;utm_medium=albums&amp;utm_campaign=albums">Albums</a></h3><p>Organize your galleries into albums</p>
              </div>
            </div>
            <div class="row top-margin">
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-image-showcase/?utm_source=mlefree&utm_medium=imageshowcase&utm_campaign=imageshowcase"><img width="200" height="200" title="MaxGalleria Image Showcase Addon" alt="MaxGalleria Image Showcase Addon" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-image-showcase-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-image-showcase/?utm_source=mlefree&utm_medium=imageshowcase&utm_campaign=imageshowcase">Image Showcase</a></h3><p>Showcase image with thumbnails</p>
              </div>
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-video-showcase/?utm_source=mlefree&utm_medium=videoshowcase&utm_campaign=videoshowcase"><img width="200" height="200" title="" alt="" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-video-showcase-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-video-showcase/?utm_source=mlefree&utm_medium=videoshowcase&utm_campaign=videoshowcase">Video Showcase</a></h3><p>Showcase video with thumbnails</p>
              </div>
            </div>
            <div class="row top-margin">
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-masonry/?utm_source=mlefree&utm_medium=masonry&utm_campaign=masonry"><img width="200" height="200" title="Maxgalleria Masonry" alt="Maxgalleria Masonry" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-masonry-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-masonry/?utm_source=mlefree&utm_medium=masonry&utm_campaign=masonry">Masonry</a></h3><p>Display Images in a Masonry Grid</p>
              </div>
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-image-slider/?utm_source=mlefree&utm_medium=imageslider&utm_campaign=imageslider"><img width="200" height="200" title="MaxGalleria Image Slider Addon" alt="MaxGalleria Image Slider Addon" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-image-slider-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-image-slider/?utm_source=mlefree&utm_medium=imageslider&utm_campaign=imageslider">Image Slider</a></h3><p>Turn your galleries into sliders</p>
              </div>
            </div>
           </div>
           <div class="small-6 medium-6 large-6 columns sources">
            <p class="section-title"><span>Media Sources</span></p>
            <div class="row top-margin">
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-facebook/?utm_source=mlefree&utm_medium=facebook&utm_campaign=facebook"><img width="200" height="200" title="MaxGalleria Facebook Addon" alt="MaxGalleria Facebook Addon" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-facebook-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-facebook/?utm_source=mlefree&utm_medium=facebook&utm_campaign=facebook">Facebook</a></h3><p>Add Facebook photos to galleries</p>
              </div>
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-slick-for-wordpress/?utm_source=mlefree&utm_medium=slick&utm_campaign=slick"><img width="200" height="200" title="Slick for WordPress" alt="Slick for WordPress" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-slick-for-wordpress-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-slick-for-wordpress/?utm_source=mlefree&utm_medium=slick&utm_campaign=slick">Slick for WordPress</a></h3><p>The Last Carousel You'll ever need!</p>
              </div>
            </div>
            <div class="row top-margin">
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-instagram/?utm_source=mlefree&utm_medium=instagram&utm_campaign=instagram"><img width="200" height="200" title="MaxGalleria Instagram Addon" alt="MaxGalleria Instagram Addon" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-instagram-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-instagram/?utm_source=mlefree&utm_medium=instagram&utm_campaign=instagram">Instagram</a></h3><p>Add Instagram images to galleries</p>
              </div>
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-flickr/?utm_source=mlefree&utm_medium=flickr&utm_campaign=flickr"><img width="200" height="200" title="MaxGalleria Flickr Addon" alt="MaxGalleria Flickr Addon" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-flickr-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-flickr/?utm_source=mlefree&utm_medium=flickr&utm_campaign=flickr">Flickr</a></h3><p>Pull In Images from your Flickr stream</p>
              </div>
            </div>
            <div class="row top-margin">
              <div class="medium-6 large-6 columns addon-item">
                <a href="http://maxgalleria.com/shop/maxgalleria-vimeo/?utm_source=mlefree&utm_medium=vimeo&utm_campaign=vimeo"><img width="200" height="200" title="MaxGalleria Vimeo Addon" alt="MaxGalleria Vimeo Addon" src="http://cdn.maxgalleria.com//wp-content/themes/maxgalleriaplatform/images/catalog/maxgalleria-vimeo-cover.png"></a><h3><a href="http://maxgalleria.com/shop/maxgalleria-vimeo/?utm_source=mlefree&utm_medium=vimeo&utm_campaign=vimeo">Vimeo</a></h3><p>Use Vimeo videos in your galleries</p>
              </div>
            </div>
           </div>
           </div>
          </div>          
          
          
        </div>
          <div class="clearfix"></div>  
      </div>          
    <?php
  }
  
  public function display_folder_contents ($current_folder_id) {
    
    global $wpdb;
    
    $folders_found = false;
    $images_found = false;
    
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER);
    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = 'post_date DESC';
        break;
      
      case '1': //order by name
        $order_by = 'post_title';
        break;      
    }
        
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
            
            $sql = "select ID, guid, post_title, $folder_table.folder_id
from $wpdb->prefix" . "posts
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' 
and folder_id = $current_folder_id 
order by $order_by";

            $rows = $wpdb->get_results($sql);
            
            echo '<ul class="mg-media-list">' . PHP_EOL;              
            if($rows) {
              $folders_found = true;
              foreach($rows as $row) {
                
                $checkbox = sprintf("<input type='checkbox' class='mgmlp-folder' id='%s' value='%s' />", $row->ID, $row->ID );
                
                echo "<li>" . PHP_EOL;
                echo "  <a class='media-folder media-link' folder='$row->ID'></a>" . PHP_EOL;
                echo "  <div class='attachment-name'><span class='image_select'>$checkbox</span><a class='media-link' folder='$row->ID'>$row->post_title</a></div>" . PHP_EOL;
                echo "</li>" . PHP_EOL;        
              }
            }

            if($order_by === 'post_title')
              $order_by = 'guid';
            
            $sql = "select ID, guid, post_title, $folder_table.folder_id 
from $wpdb->prefix" . "posts 
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
where post_type = 'attachment' 
and folder_id = '$current_folder_id' 
order by $order_by";

            $rows = $wpdb->get_results($sql);
            
            if($rows) {
              $images_found = true;
              foreach($rows as $row) {
                $thumbnail = wp_get_attachment_thumb_url($row->ID);                
                if($thumbnail === false) {
                  $thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_URL . "/images/file.jpg";
                }  
                                
                $checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->ID, $row->ID );
                $class = "media-attachment"; 
                
                if($this->wp_version < NEW_MEDIA_LIBRARY_VERSION) 
                  $media_edit_link = "/wp-admin/post.php?post=" . $row->ID . "&action=edit";
                else
                  $media_edit_link = "/wp-admin/upload.php?item=" . $row->ID;
                
					      $image_location = $this->check_for_attachment_id($row->guid, $row->ID);
								                
                $filename = pathinfo($image_location, PATHINFO_BASENAME);
                                
                echo "<li>" . PHP_EOL;
                echo "   <a class='$class' href='" . site_url() . $media_edit_link . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
                echo "   <div class='attachment-name'><span class='image_select'>$checkbox</span>$filename</div>" . PHP_EOL;
                echo "</li>" . PHP_EOL;              
              }      
            }
            echo '</ul>' . PHP_EOL;
            
            if(!$images_found && !$folders_found)
              echo "<p style='text-align:center'>" . __('No images or folders were found.','maxgalleria')  . "</p>";
    
  }
  
  private function get_folder_path($folder_id) {
      
    global $wpdb;    
    $sql = "select guid from $wpdb->prefix" . "posts where ID = $folder_id";    
    $row = $wpdb->get_row($sql);
		
		$image_location = $this->check_for_attachment_id($row->guid, $folder_id);
				
    $absolute_path = $this->get_absolute_path($image_location);
    return $absolute_path;
      
  }
  
  private function get_subfolder_path($folder_id) {
      
    global $wpdb;    
    $sql = "select guid from $wpdb->prefix" . "posts where ID = $folder_id";    
    $row = $wpdb->get_row($sql);
		
		$image_location = $this->check_for_attachment_id($row->guid, $folder_id);
				
    $postion = strpos($image_location, $this->uploads_folder_name);
    $path = substr($image_location, $postion+$this->uploads_folder_name_length );
    return $path;
      
  }
  
  private function get_folder_name($folder_id) {
    global $wpdb;    
    $sql = "select post_title from $wpdb->prefix" . "posts where ID = $folder_id";    
    $row = $wpdb->get_row($sql);
    return $row->post_title;
  }
    
  private function get_parents($current_folder_id) {

    global $wpdb;    
    $folder_id = $current_folder_id;    
    $parents = array();
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    
    while($folder_id !== '0') {    
      
      $sql = "select post_title, ID, $folder_table.folder_id 
from $wpdb->prefix" . "posts 
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
where ID = $folder_id";    
      
      $row = $wpdb->get_row($sql);
      
      $folder_id = $row->folder_id;
      
      $new_folder = array();
      $new_folder['name'] = $row->post_title;
      $new_folder['id'] = $row->ID;
      
      $parents[] = $new_folder;      
                    
    }
    
    $parents = array_reverse($parents);
        
    return $parents;
    
  }  

  private function get_parent($folder_id) {
    
    global $wpdb;    
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    
    $sql = "select post_title, $folder_table.folder_id 
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
from $wpdb->prefix" . "posts 
where ID = $folder_id";    

    
    $row = $wpdb->get_row($sql);
        
    return $row->folder_id;
    
  }
  
  public function create_new_folder() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_NONCE)) {
      exit(__('missing nonce!','maxgalleria'));
    } 

    if ((isset($_POST['parent_folder'])) && (strlen(trim($_POST['parent_folder'])) > 0))
      $parent_folder_id = trim(stripslashes(strip_tags($_POST['parent_folder'])));
    
    
    if ((isset($_POST['new_folder_name'])) && (strlen(trim($_POST['new_folder_name'])) > 0))
      $new_folder_name = trim(stripslashes(strip_tags($_POST['new_folder_name'])));
    
    $sql = "select guid from $wpdb->prefix" . "posts where ID = $parent_folder_id";    
    
    $row = $wpdb->get_row($sql);
        
		$image_location = $this->check_for_attachment_id($row->guid, $parent_folder_id);
		        
    $absolute_path = $this->get_absolute_path($image_location);
                
    $new_folder_path = $absolute_path . DIRECTORY_SEPARATOR . $new_folder_name ;
    
    $new_folder_url = $this->get_file_url_for_copy($new_folder_path);
    
    if(!file_exists($new_folder_path)) {
      if(mkdir($new_folder_path)) {
        if($this->add_media_folder($new_folder_name, $parent_folder_id, $new_folder_url)){
          $location = 'window.location.href = "' . site_url() . '/wp-admin/admin.php?page=media-library&media-folder=' . $parent_folder_id .'";';
          echo __('The folder was created.','maxgalleria');
          echo "<script>$location</script>";
        }  
        else
          echo __('There was a problem creating the folder.','maxgalleria');
      }
    }
    else
      echo __('The folder already exists.','maxgalleria');
    die();
  }

  public function get_absolute_path($url) {
    $file_path = str_replace( $this->upload_dir['baseurl'], $this->upload_dir['basedir'], $url ); 
    
    // are we on windows?
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      $file_path = str_replace('/', '\\', $file_path);
    }
    return $file_path;
  }
  
  public function is_windows() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
      return true;
    else
      return false;      
  }
  
  public function get_file_url($path) {
    
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      
      $base_url = $this->upload_dir['baseurl'];
      // replace any slashes in the dir path when running windows
      $base_upload_dir1 = $this->upload_dir['basedir'];
      $base_upload_dir2 = str_replace('\\', '/', $base_upload_dir1);      
      $file_url = str_replace( $base_upload_dir2, $base_url, $path ); 
    }
    else {
      $file_url = str_replace( $this->upload_dir['basedir'], $this->upload_dir['baseurl'], $path );          
    }
    return $file_url;    
  }
  
  public function get_file_url_for_copy($path) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      
      $base_url = $this->upload_dir['baseurl'];
      
      // replace any slashes in the dir path when running windows
      $base_upload_dir1 = $this->upload_dir['basedir'];
      $base_upload_dir2 = str_replace('/','\\', $base_upload_dir1);      
      $file_url = str_replace( $base_upload_dir2, $base_url, $path ); 
      $file_url = str_replace('\\',   '/', $file_url);      
      
    }
    else {
      $file_url = str_replace( $this->upload_dir['basedir'], $this->upload_dir['baseurl'], $path );          
    }
    return $file_url;    
  
  }
  
  public function delete_maxgalleria_media() {
    global $wpdb;
    $delete_ids = array();
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_NONCE)) {
      exit(__('missing nonce!','maxgalleria'));
    } 
    
    if ((isset($_POST['serial_delete_ids'])) && (strlen(trim($_POST['serial_delete_ids'])) > 0)) {
      $delete_ids = trim(stripslashes(strip_tags($_POST['serial_delete_ids'])));
      $delete_ids = str_replace('"', '', $delete_ids);
      $delete_ids = explode(",",$delete_ids);
      //$output = print_r($delete_ids, true);
    }  
    else
      $delete_ids = '';
            
    foreach( $delete_ids as $delete_id) {
            
      $sql = "select guid, post_title, post_type from $wpdb->prefix" . "posts where ID = $delete_id";    
      
      $row = $wpdb->get_row($sql);

		  $image_location = $this->check_for_attachment_id($row->guid, $delete_id);
			
      $folder_path = $this->get_absolute_path($image_location);
      $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
      $del_post = array('post_id' => $delete_id);                        

      if($row->post_type === MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE) { //folder
        
        $sql = "SELECT COUNT(*) FROM $wpdb->prefix" . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE . " where folder_id = $delete_id";
        $row_count = $wpdb->get_var($sql);
        
        if($row_count > 1) {
          echo __('The folder, ','maxgalleria'). $row->post_title . __(', is not empty. Please delete or move files form the folder','maxgalleria') . PHP_EOL;      
          die();
        }  

        if(file_exists($folder_path)) {
          if(is_dir($folder_path)) {  //folder
            @chmod($folder_path, 0777);
            if(rmdir($folder_path)) {
              wp_delete_post($delete_id, true);
              $wpdb->delete( $table, $del_post );
            }  
          }          
        }                          
      }
      else {
        if( wp_delete_attachment( $delete_id, true ) !== false ) {
          $wpdb->delete( $table, $del_post );
        }  
      } 
    }
    echo "<script>location.reload(true);</script>";

    die();
  }  
    
  public function copy_media() {
    $this->modify_media(true);
  }
    
  public function move_media() {
    $this->modify_media(false);    
  }
  
  public function modify_media($copy=true) {
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_NONCE)) {
      exit(__('missing nonce!','maxgalleria'));
    } 
    
    if ((isset($_POST['serial_copy_ids'])) && (strlen(trim($_POST['serial_copy_ids'])) > 0))
      $serial_copy_ids = trim(stripslashes(strip_tags($_POST['serial_copy_ids'])));
    else
      $serial_copy_ids = "";
        
    $serial_copy_ids = str_replace('"', '', $serial_copy_ids);    
    
    $serial_copy_ids = explode(',', $serial_copy_ids);
        
    if ((isset($_POST['destination'])) && (strlen(trim($_POST['destination'])) > 0))
      $destination = trim(strip_tags($_POST['destination']));
    else
      $destination = '';
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = 0;
    
    if ((isset($_POST['current_folder'])) && (strlen(trim($_POST['current_folder'])) > 0))
      $current_folder = trim(stripslashes(strip_tags($_POST['current_folder'])));
    else
      $current_folder = 0;
            
    if($destination !== "" || $folder_id !== 0 ) {
      
			foreach( $serial_copy_ids as $copy_id) {
        
        $sql = "select guid from $wpdb->prefix" . "posts where ID = $copy_id";    

        $row = $wpdb->get_row($sql);
        
		    $image_location = $this->check_for_attachment_id($row->guid, $copy_id);
				        
        $image_path = $this->get_absolute_path($image_location);

        $destination_path = $this->get_absolute_path($destination);
                
        $destination_name = $destination_path . DIRECTORY_SEPARATOR . pathinfo($image_path, PATHINFO_BASENAME);
				
        $copy_status = true;
                                
        if(file_exists($image_path)) {
          if(!is_dir($image_path)) {
            if(file_exists($destination_path)) {
              if(is_dir($destination_path)) {
                
                if($copy) {                  
                  if(copy($image_path, $destination_name )) {                                          
                         
                    $destination_url = $this->get_file_url($destination_name);
                    $attach_id = $this->add_new_attachment($destination_name, $folder_id);
                    if($attach_id === false){
                      $copy_status = false; 
                    }  
                  }
                  else {
                    echo __('Unable to copy the file; please check the folder and file permissions.','maxgalleria') . PHP_EOL;
                    $copy_status = false; 
                    break;
                  }
                  //move
                } else {
                                    
                  if(rename($image_path, $destination_name )) {
                    
                    // check current theme customizer settings for the file
                    // and update if found
                    $update_theme_mods = false;
                    $move_image_url = $this->get_file_url_for_copy($image_path);
                    $move_destination_url = $this->get_file_url_for_copy($destination_name);
                    $key = array_search ($move_image_url, $this->theme_mods);
                    if($key !== false ) {
                      set_theme_mod( $key, $move_destination_url);
                      $update_theme_mods = true;                      
                    }
                    if($update_theme_mods) {
                      $theme_mods = get_theme_mods();
                      $this->theme_mods = json_decode(json_encode($theme_mods), true);
                      $update_theme_mods = false;
                    }
                                          
                    $image_path = str_replace('.', '*.', $image_path );

                    foreach (glob($image_path) as $source_path) {
                      $thumbnail_file = pathinfo($source_path, PATHINFO_BASENAME);
                      $thumbnail_destination = $destination_path . DIRECTORY_SEPARATOR . $thumbnail_file;
                      rename($source_path, $thumbnail_destination);
                      
                      // check current theme customizer settings for the fileg
                      // and update if found
                      $update_theme_mods = false;
                      $move_source_url = $this->get_file_url_for_copy($source_path);
                      $move_thumbnail_url = $this->get_file_url_for_copy($thumbnail_destination);
                      $key = array_search ($move_source_url, $this->theme_mods);
                      if($key !== false ) {
                        set_theme_mod( $key, $move_thumbnail_url);
                        $update_theme_mods = true;                      
                      }
                      if($update_theme_mods) {
                        $theme_mods = get_theme_mods();
                        $this->theme_mods = json_decode(json_encode($theme_mods), true);
                        $update_theme_mods = false;
                      }
                      
                    }                    
                      
                    $destination_url = $this->get_file_url($destination_name);
                    
                    // update posts table
                    $table = $wpdb->prefix . "posts";
                    $data = array('guid' => $destination_url );
                    $where = array('ID' => $copy_id);
                    $wpdb->update( $table, $data, $where);
                    
                    // update folder table
                    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
                    $data = array('folder_id' => $folder_id );
                    $where = array('post_id' => $copy_id);
                    $wpdb->update( $table, $data, $where);

                    // get the uploads dir name
                    $basedir = $this->upload_dir['baseurl'];
                    $uploads_dir_name_pos = strrpos($basedir, '/');
                    $uploads_dir_name = substr($basedir, $uploads_dir_name_pos+1);
                        
                    //find the name and cut off the part with the uploads path
                    $string_position = strpos($destination_name, $uploads_dir_name);
                    $uploads_dir_length = strlen($uploads_dir_name) + 1;
                    $uploads_location = substr($destination_name, $string_position+$uploads_dir_length);
                    if($this->is_windows()) 
                      $uploads_location = str_replace('\\','/', $uploads_location);      
                    
                    // update post meta
                    update_post_meta( $copy_id, '_wp_attached_file', $uploads_location );
                                                                             
                  }                                   
                  else {
                    echo __('Unable to move the file(s); please check the folder and file permissions.','maxgalleria') . PHP_EOL;
                    $copy_status = false; 
                    break;
                  }
                } 
              }
              else {
                echo __('The destination is not a folder: ','maxgalleria') . $destination_path . PHP_EOL;
                $copy_status = false; 
                break;
              }
            }
            else {
              echo __('Cannot find destination folder: ','maxgalleria') . $destination_path . PHP_EOL;
              $copy_status = false; 
              break;
            }
          }   
          else {
            echo __('Coping or moving a folder is not allowed.','maxgalleria') . PHP_EOL;
            $copy_status = false; 
            break;
          }
        }
        else {
          echo __('Cannot the find the file: ','maxgalleria') . $image_path  . PHP_EOL;
          $copy_status = false; 
        }        
      }
      if($copy) {
        if($copy_status)
          echo __('The file(s) were copied.','maxgalleria') . PHP_EOL;      
        else
          echo __('The file(s) were not copied.','maxgalleria') . PHP_EOL;      
      }
      else {
        if($copy_status)
          echo __('The file(s) were moved.','maxgalleria') . PHP_EOL;      
        else
          echo __('The file(s) were not moved.','maxgalleria') . PHP_EOL;              
      }
      
      if(!$copy) {
        $location = "window.location.href = '" . site_url() . "/wp-admin/admin.php?page=media-library&media-folder=" . $current_folder . "'";
        echo "<script>$location</script>";
      }
      
    }        
    die();
        
  }
  
  function get_image_sizes() {
    global $_wp_additional_image_sizes;
    $sizes = array();
    $rSizes = array();
    foreach (get_intermediate_image_sizes() as $s) {
      $sizes[$s] = array(0, 0);
      if (in_array($s, array('thumbnail', 'medium', 'large'))) {
        $sizes[$s][0] = get_option($s . '_size_w');
        $sizes[$s][1] = get_option($s . '_size_h');
      } else {
        if (isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$s]))
          $sizes[$s] = array($_wp_additional_image_sizes[$s]['width'], $_wp_additional_image_sizes[$s]['height'],);
      }
    }

    foreach ($sizes as $size => $atts) {
      $rSizes[] = implode('x', $atts);
    }

    return $rSizes;
  }  
  
  public function add_to_max_gallery () {
    
    global $wpdb, $maxgalleria;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_NONCE)) {
      exit(__('missing nonce!','maxgalleria'));
    } 
    
    if ((isset($_POST['serial_gallery_image_ids'])) && (strlen(trim($_POST['serial_gallery_image_ids'])) > 0))
      $serial_gallery_image_ids = trim(stripslashes(strip_tags($_POST['serial_gallery_image_ids'])));
    else
      $serial_gallery_image_ids = "";
    
    $serial_gallery_image_ids = str_replace('"', '', $serial_gallery_image_ids);    
    
    $serial_gallery_image_ids = explode(',', $serial_gallery_image_ids);
        
    if ((isset($_POST['gallery_id'])) && (strlen(trim($_POST['gallery_id'])) > 0))
      $gallery_id = trim(stripslashes(strip_tags($_POST['gallery_id'])));
    else
      $gallery_id = 0;
    
    foreach( $serial_gallery_image_ids as $attachment_id) {
      
      // check for image already in the gallery
      $sql = "SELECT ID FROM $wpdb->prefix" . "posts where post_parent = $gallery_id and post_type = 'attachment' and ID = $attachment_id";
      
      $row = $wpdb->get_row($sql);
      
      if($row === null) {

        $menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);      

        $attachment = get_post( $attachment_id, ARRAY_A );

        // assign a new value for menu_order
        //$menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);
        $attachment[ 'menu_order' ] = $menu_order;

        //If the attachment doesn't have a post parent, simply change it to the attachment we're working with and be done with it      
        // assign a new value for menu_order
        if( empty( $attachment[ 'post_parent' ] ) ) {
          wp_update_post(
            array(
              'ID' => $attachment[ 'ID' ],
              'post_parent' => $gallery_id,
              'menu_order' => $menu_order
            )
          );
          $result = $attachment[ 'ID' ];
        } else {
          //Else, unset the attachment ID, change the post parent and insert a new attachment
          unset( $attachment[ 'ID' ] );
          $attachment[ 'post_parent' ] = $gallery_id;
          $new_attachment_id = wp_insert_post( $attachment );
          //$new_attachment_id = $this->mpmlp_insert_post( $attachment );
          

          //Now, duplicate all the custom fields. (There's probably a better way to do this)
          $custom_fields = get_post_custom( $attachment_id );

          foreach( $custom_fields as $key => $value ) {
            //The attachment metadata wasn't duplicating correctly so we do that below instead
            if( $key != '_wp_attachment_metadata' )
              update_post_meta( $new_attachment_id, $key, $value[0] );
          }

          //Carry over the attachment metadata
          $data = wp_get_attachment_metadata( $attachment_id );
          wp_update_attachment_metadata( $new_attachment_id, $data );

          $result = $new_attachment_id;

        } 
      }
            
    }// foreach
        
    echo __('The images were added.','maxgalleria') . PHP_EOL;              
        
    die();
    
  }
  
  public function search_media () {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_NONCE)) {
      exit(__('missing nonce!','maxgalleria'));
    } 
    
    if ((isset($_POST['search_value'])) && (strlen(trim($_POST['search_value'])) > 0))
      $search_value = trim(stripslashes(strip_tags($_POST['search_value'])));
    else
      $search_value = "";
    
    $sql = $wpdb->prepare("select ID, post_title, post_name, guid from " . $wpdb->prefix . "posts 
      LEFT JOIN $wpdb->prefix" . "mgmlp_folders ON($wpdb->prefix" . "posts.ID = $wpdb->prefix" . "mgmlp_folders.post_id) 
      where post_type= 'attachment' and post_title like '%%%s%%'", $search_value);
    
    $rows = $wpdb->get_results($sql);
    
    if($rows) {
        foreach($rows as $row) {
          $thumbnail = wp_get_attachment_thumb_url($row->ID);
          if($thumbnail !== false)
            $ext = pathinfo($thumbnail, PATHINFO_EXTENSION);
          else {
						
		        $image_location = $this->check_for_attachment_id($row->guid, $row->ID);
												
            $ext_pos = strrpos($image_location, '.');
            $ext = substr($image_location, $ext_pos+1);
            $thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_URL . "/images/file.jpg";
          }

          $class = "media-attachment"; 
          echo "<li>" . PHP_EOL;
          echo "   <a class='$class' href='" . site_url() . "/wp-admin/upload.php?item=" . $row->ID . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
          echo "   <div class='attachment-name'>$row->post_title.$ext</div>" . PHP_EOL;
          echo "</li>" . PHP_EOL;              
        }      
      
    }
    else {
      echo __('No files were found matching that name.','maxgalleria') . PHP_EOL;                      
    }
    
    die();    
  }
  
  public function search_library() {
    
    global $wpdb;
            
    echo '<div id="wp-media-grid" class="wrap">' . PHP_EOL;
    //empty h2 for where WP notices will appear
    echo '  <h2></h2>' . PHP_EOL;
//    echo '  <div class="media-plus-toolbar wp-filter"><div class="media-toolbar-secondary">' . PHP_EOL;
    echo '  <div class="media-plus-toolbar wp-filter">' . PHP_EOL;
    echo '<div id="mgmlp-title-area">' . PHP_EOL;
    echo '  <h2 class="mgmlp-title">Media Library Plus Search Results</h2>' . PHP_EOL;
    echo '  <div id="back-wraper"><a href="' . site_url() . '/wp-admin/admin.php?page=media-library">Back to Media Library Plus Folders</a></div>' . PHP_EOL;
    echo '  <div id="search-wrap"><input type="search" placeholder="Search" id="mgmlp-media-search-input" class="search"></div>' . PHP_EOL;            
    echo '</div>' . PHP_EOL;
    echo "<p>Click on an image to go to its folder or a on folder to view its contents.</p>";
    if ((isset($_GET['s'])) && (strlen(trim($_GET['s'])) > 0)) {
      $search_string = trim(stripslashes(strip_tags($_GET['s'])));
      echo "<h4>Search results for: $search_string</h4>" . PHP_EOL;
      
      echo '<ul class="mg-media-list">' . PHP_EOL;
            
      $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
      $sql = $wpdb->prepare("select ID, post_title, $folder_table.folder_id
        from $wpdb->prefix" . "posts
        LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
        where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' and post_title like '%%%s%%'", $search_string);

      $rows = $wpdb->get_results($sql);

      $class = "media-folder"; 
      if($rows) {
        foreach($rows as $row) {
          
          echo "<li>" . PHP_EOL;
          echo "   <a class='$class' href='" . site_url() . "/wp-admin/admin.php?page=media-library&media-folder=" . $row->ID . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
          echo "   <div class='attachment-name'>$row->post_title</div>" . PHP_EOL;
          echo "</li>" . PHP_EOL;              
          
        }
      }

            
      $sql = $wpdb->prepare("select ID, post_title, guid, folder_id from " . $wpdb->prefix . "posts 
        LEFT JOIN $wpdb->prefix" . "mgmlp_folders ON($wpdb->prefix" . "posts.ID = $wpdb->prefix" . "mgmlp_folders.post_id) 
        where post_type= 'attachment' and post_title like '%%%s%%'", $search_string);

      $rows = $wpdb->get_results($sql);

      $class = "media-attachment"; 
      if($rows) {
        foreach($rows as $row) {
					
		      $image_location = $this->check_for_attachment_id($row->guid, $row->ID);
					
          $thumbnail = wp_get_attachment_thumb_url($row->ID);
          if($thumbnail !== false)
            $ext = pathinfo($thumbnail, PATHINFO_EXTENSION);
          else {
            $ext_pos = strrpos($image_location, '.');
            $ext = substr($image_location, $ext_pos+1);
            $thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PLUGIN_URL . "/images/file.jpg";
          }
          
          $filename =  pathinfo($row->guid, PATHINFO_BASENAME);
          
          echo "<li>" . PHP_EOL;
          echo "   <a class='$class' href='" . site_url() . "/wp-admin/admin.php?page=media-library&media-folder=" . $row->folder_id . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
          echo "   <div class='attachment-name'>$filename</div>" . PHP_EOL;
          echo "</li>" . PHP_EOL;              
        }      

      }
      else {
        echo __('No files were found matching that name.','maxgalleria') . PHP_EOL;                      
      }
      echo "</ul>" . PHP_EOL;
    }
    //echo '  </div>' . PHP_EOL;
    echo '</div>' . PHP_EOL;    
    
    ?>
        
      <script>                        
      jQuery('#mgmlp-media-search-input').keydown(function (e){
        if(e.keyCode == 13){

          var search_value = jQuery('#mgmlp-media-search-input').val();

          var home_url = "<?php echo site_url(); ?>"; 

          window.location.href = home_url + '/wp-admin/admin.php?page=search-library&' + 's=' + search_value;

        }  
      })    
      </script>          
    <?php
  }
  
  public function maxgalleria_rename_image() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_NONCE)) {
      exit(__('missing nonce!','maxgalleria'));
    } 
    
    if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
      $file_id = trim(stripslashes(strip_tags($_POST['image_id'])));
    else
      $file_id = "";
    
    if ((isset($_POST['new_file_name'])) && (strlen(trim($_POST['new_file_name'])) > 0))
      $new_file_name = trim(stripslashes(strip_tags($_POST['new_file_name'])));
    else
      $new_file_name = "";
    
    if($new_file_name === '') {
      echo "Invalid file name.";
      die();
    }
    
    $new_file_name = strtolower($new_file_name);
    if(preg_match('/^[a-z0-9-]+\.ext$/', $new_file_name)) {
      echo "Invalid file name.";
      die();      
    }
    
    if (preg_match("/\\s/", $new_file_name)) {
      echo "The file name cannot contain spaces or tabs.";
      die();            
    }
          
    $sql = "select ID, guid, post_title, post_name from $wpdb->prefix" . "posts where ID = $file_id";
    $row = $wpdb->get_row($sql);
    if($row) {
      
		  $image_location = $this->check_for_attachment_id($row->guid, $file_id);
			      
      $full_new_file_name = $new_file_name . '.' . pathinfo($image_location, PATHINFO_EXTENSION);
      $destination_path = $this->get_absolute_path(pathinfo($image_location, PATHINFO_DIRNAME));
      $new_file_name = wp_unique_filename( $destination_path, $full_new_file_name, null );
      
      $old_file_path = $this->get_absolute_path($image_location);
      $new_file_url = pathinfo($image_location, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $new_file_name;
      $new_file_path = $this->get_absolute_path($new_file_url);
                  
      if($this->is_windows()) {
        $old_file_path = str_replace('\\', '/', $old_file_path);      
        $new_file_path = str_replace('\\', '/', $new_file_path);      
      }
            
      if(rename($old_file_path, $new_file_path )) {

        $old_file_path = str_replace('.', '*.', $old_file_path );

        foreach (glob($old_file_path) as $source_path) {
          $thumbnail_file = pathinfo($source_path, PATHINFO_BASENAME);
          $thumbnail_destination = $destination_path . DIRECTORY_SEPARATOR . $thumbnail_file;
          unlink($source_path);
        }                    
              
        $table = $wpdb->prefix . "posts";
        $data = array('guid' => $new_file_url, 
                      'post_title' => $new_file_name,
                      'post_name' => $new_file_name                
                );
        $where = array('ID' => $file_id);
        $wpdb->update( $table, $data, $where);
        
        $table = $wpdb->prefix . "postmeta";
        $where = array('post_id' => $file_id);
        $wpdb->delete($table, $where);
                
        // get the uploads dir name
        $basedir = $this->upload_dir['baseurl'];
        $uploads_dir_name_pos = strrpos($basedir, '/');
        $uploads_dir_name = substr($basedir, $uploads_dir_name_pos+1);

        //find the name and cut off the part with the uploads path
        $string_position = strpos($new_file_url, $uploads_dir_name);
        $uploads_dir_length = strlen($uploads_dir_name) + 1;
        $uploads_location = substr($new_file_url, $string_position+$uploads_dir_length);
        if($this->is_windows()) 
          $uploads_location = str_replace('\\','/', $uploads_location);      

        update_post_meta( $file_id, '_wp_attached_file', $uploads_location );
        $attach_data = wp_generate_attachment_metadata( $file_id, $new_file_path );
        wp_update_attachment_metadata( $file_id, $attach_data );

        echo "<script>window.location.reload(true);</script>";
      }
    }
    
    die();
  }
  
  // saves the sort selection
  public function sort_contents() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_NONCE)) {
      exit(__('missing nonce!','maxgalleria'));
    } 
    
    if ((isset($_POST['sort_order'])) && (strlen(trim($_POST['sort_order'])) > 0))
      $sort_order = trim(stripslashes(strip_tags($_POST['sort_order'])));
    else
      $sort_order = "0";
    
    update_option( MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER, $sort_order );  
    
    switch ($sort_order) {
      case '0':
      $msg = __('Sorting by date.','maxgalleria');
      break;  
    
      case '1':
      $msg = __('Sorting by name.','maxgalleria');
      break;        
    }
    
    echo $msg;
            
    die();
  }
  
  public function run_on_deactivate() {
    wp_clear_scheduled_hook('new_folder_check');
  }
  
  public function admin_check_for_new_folders($noecho = null) {
    
    $uploads_path = wp_upload_dir();
    
    if(!$uploads_path['error']) {
      
      $uploads_folder = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
      $uploads_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
      $uploads_length = strlen($uploads_folder);
      
      //find the uploads folder
      $uploads_url = $uploads_path['baseurl'];
      $upload_path = $this->get_absolute_path($uploads_url);
      $folder_found = false;
      
      if(!$noecho)
        echo __('Scaning for new folders in ','maxgalleria') . " $upload_path<br>";      
      $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($upload_path), RecursiveIteratorIterator::SELF_FIRST);
      foreach($objects as $name => $object){
        if(is_dir($name)) {
          $dir_name = pathinfo($name, PATHINFO_BASENAME);
          if ($dir_name[0] !== '.') {            
            //$url = $this->get_file_url($name);
            
            $upload_pos = strpos($name, $uploads_folder);
            $url = $uploads_url . substr($name, ($upload_pos+$uploads_length));

            // fix slashes if running windows
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
              $url = str_replace('\\', '/', $url);      
            }
            
            if($this->folder_exist($url) === false) {
              $folder_found = true;
              if(!$noecho)
                echo __('Adding','maxgalleria') . " $url<br>";
              $parent_id = $this->find_parent_id($url);
              $this->add_media_folder($dir_name, $parent_id, $url);              
            }
          }  
        }  
      }      
      if(!$folder_found) {
        if(!$noecho)
          echo __('No new folders were found.','maxgalleria') . "<br>";
      }  
    } 
    else {
      if(!$noecho)
        echo "error: " . $uploads_path['error'];
    }
  }
  
  private function find_parent_id($base_url) {
    
    global $wpdb;    
    $last_slash = strrpos($base_url, '/');
    $parent_dir = substr($base_url, 0, $last_slash);
    $sql = "select ID from $wpdb->prefix" . "posts where guid = '$parent_dir'";
    $row = $wpdb->get_row($sql);
    if($row) {
      $parent_id = $row->ID;
    }
    else
      $parent_id = -1;

    return $parent_id;
  }
    
  private function mpmlp_insert_post( $post_type, $post_title, $guid, $post_status ) {
    global $wpdb;
    
    $user_id = get_current_user_id();
    $post_date = current_time('mysql');
    
    $post = array(
      'post_content'   => '',
      'post_name'      => $post_title, 
      'post_title'     => $post_title,
      'post_status'    => $post_status,
      'post_type'      => $post_type,
      'post_author'    => $user_id,
      'ping_status'    => 'closed',
      'post_parent'    => 0,
      'menu_order'     => 0,
      'to_ping'        => '',
      'pinged'         => '',
      'post_password'  => '',
      'guid'           => $guid,
      'post_content_filtered' => '',
      'post_excerpt'   => '',
      'post_date'      => $post_date,
      'post_date_gmt'  => $post_date,
      'comment_status' => 'closed'
    );      
        
    
    $table = $wpdb->prefix . "posts";	    
    $wpdb->insert( $table, $post );
    
    //write_log("added folder: $post_title");
    
    return $wpdb->insert_id;  
  }
	
  public function check_for_attachment_id($guid, $post_id) {	
		
		$attach_id_found = strpos($guid, 'attachment_id=');
		if($attach_id_found !== false) 
			return wp_get_attachment_url($post_id);
		else
			return $guid;
	}
	    
}

//$maxgalleria_media_library = new MaxGalleriaMediaLib();

?>