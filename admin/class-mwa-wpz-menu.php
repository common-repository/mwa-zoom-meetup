<?php
defined('ABSPATH') or die();

if( !class_exists('MWA_WPZ_Menu')){

	class MWA_WPZ_Menu{
		
		public static function wpz_create_menu() {	
			global $submenu;		
			/* SYNTAX - Create Menu				
	 		    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
	 		*/ 

	 		/* Create Menu */
	 		$admin_dboard = add_menu_page( __('MWA ZOOM', MWA_WP_ZOOM ), __('MWA ZOOM', MWA_WP_ZOOM ), 'manage_options', 'mwa_wpz_plug', array( 'MWA_WPZ_Menu', 'dashboard_files' ), esc_url(MWA_WP_ZOOM_URL.'assets/images/mwa_wp-zoom.png'), '10');  

	 		add_action( 'admin_print_styles-' . $admin_dboard, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );

	 		/* SYNTAX - Create SUB Menu
	 			add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null ) 
	 		*/

	 		/* Create Sub Menu */
	 		$token_manager = add_submenu_page( 'mwa_wpz_plug', __('Token Manager', MWA_WP_ZOOM ) , __('Token Manager', MWA_WP_ZOOM ), 'manage_options', 'token_manage', array( 'MWA_WPZ_Menu', 'tokenmanage_submenu' ) );
			add_action( 'admin_print_styles-' . $token_manager, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );
			$submenu['mwa_wpz_plug'][0][0] = __('Zoom Dashboard', MWA_WP_ZOOM );
	 	
			$create_meeting = add_submenu_page( 'mwa_wpz_plug', __('Create Meeting', MWA_WP_ZOOM ) ,"<span style='color:#d8ff02;'>".__('Create Meeting', MWA_WP_ZOOM )."</span>", 'manage_options', 'create_meeting', array( 'MWA_WPZ_Menu', 'createmeet_submenu' ) );
			add_action( 'admin_print_styles-' . $create_meeting, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );			

			$meeting_list = add_submenu_page( 'mwa_wpz_plug', __('Manage Meeting', MWA_WP_ZOOM ) , __('Manage Meeting', MWA_WP_ZOOM ), 'manage_options', 'manage_meeting', array( 'MWA_WPZ_Menu', 'meetlist_submenu' ) );
			add_action( 'admin_print_styles-' . $meeting_list, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );

			$user_list = add_submenu_page( 'mwa_wpz_plug', __('Add User & List', MWA_WP_ZOOM ) , __('Add User & List', MWA_WP_ZOOM ), 'manage_options', 'add_users', array( 'MWA_WPZ_Menu', 'userlist_submenu' ) );
			add_action( 'admin_print_styles-' . $user_list, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );	

			$event_mngr = add_submenu_page( 'mwa_wpz_plug', __('GCal Event Manager', MWA_WP_ZOOM ) , __('GCal Event Manager', MWA_WP_ZOOM ), 'manage_options', 'manage_event', array( 'MWA_WPZ_Menu', 'eventmngr_submenu' ) );
			add_action( 'admin_print_styles-' . $event_mngr, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );

			$all_event = add_submenu_page( 'mwa_wpz_plug', __('All Events', MWA_WP_ZOOM ) , __('All Events', MWA_WP_ZOOM ), 'manage_options', 'all_events', array( 'MWA_WPZ_Menu', 'all_events_submenu' ) );
			add_action( 'admin_print_styles-' . $all_event, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );				

		   /* $add_webinar = add_submenu_page( 'mwa_wpz_plug', __('Add Webinar & List', MWA_WP_ZOOM ) , __('Add Webinar & List', MWA_WP_ZOOM ), 'manage_options', 'add_webinar', array( 'MWA_WPZ_Menu', 'addwebinar_submenu' ) );
			add_action( 'admin_print_styles-' . $add_webinar, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );*/	

			$tutorial = add_submenu_page( 'mwa_wpz_plug', __('Tutorial', MWA_WP_ZOOM ) , __('Tutorial', MWA_WP_ZOOM ), 'manage_options', 'tutorial_vc', array( 'MWA_WPZ_Menu', 'tuto_submenu' ) );
			add_action( 'admin_print_styles-' . $tutorial, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );

			$go_pro = add_submenu_page( 'mwa_wpz_plug', __('Upgrade To Pro', MWA_WP_ZOOM ) , __('Upgrade To Pro', MWA_WP_ZOOM ), 'manage_options', 'upg_to_pro', array( 'MWA_WPZ_Menu', 'upgpro_submenu' ) );
			add_action( 'admin_print_styles-' . $go_pro, array( 'MWA_WPZ_Menu', 'dboard_assets' ) );
						
	 	} /*end menu and submenu function -  wpz_create_menu*/	 			

	 	/* Enqueue libs */
		public static function dboard_assets() {
			self::enqueue_libs();
		}	

		public static function enqueue_libs() {
			wp_enqueue_style( 'bootstrap_css', MWA_WP_ZOOM_URL . 'assets/css/bootstrap.min.css'  );
			wp_enqueue_style( 'admin_css', MWA_WP_ZOOM_URL . 'admin/libs/css/admin-css.css'  );
			wp_enqueue_style('fontawesome_css', MWA_WP_ZOOM_URL . 'libs/fontawsome/css/font-awesome.min.css');
			wp_enqueue_style( 'clockpicker', MWA_WP_ZOOM_URL . 'admin/libs/css/clockpicker.css'  );
			wp_enqueue_style( 'standalone', MWA_WP_ZOOM_URL . 'admin/libs/css/standalone.css'  );

			/* css for searchable dropdown */
			wp_enqueue_style('select_css', MWA_WP_ZOOM_URL . 'admin/libs/css/select2.min.css');
			wp_enqueue_style( 'toastr_css', MWA_WP_ZOOM_URL . 'assets/toastr/toastr.min.css' );

			wp_enqueue_script( 'jquery' );		
		
			wp_enqueue_script( 'bootstrap_js', MWA_WP_ZOOM_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), true, true );
			wp_enqueue_style( 'datatable-css', MWA_WP_ZOOM_URL . 'admin/libs/css/datatables.min.css' );
			wp_enqueue_script( 'datatable-js', MWA_WP_ZOOM_URL . 'admin/libs/js/datatables.min.js', array( 'jquery' ) );

			/* js for searchable dropdown */
			wp_enqueue_script( 'select-js', MWA_WP_ZOOM_URL . 'admin/libs/js/select2.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'toastr-js', MWA_WP_ZOOM_URL . 'assets/toastr/toastr.min.js' );	
			/* js for colorpicker dropdown */
			wp_enqueue_script( 'clockpicker-js', MWA_WP_ZOOM_URL . 'admin/libs/js/clockpicker.js', array( 'jquery' ) );

			wp_enqueue_script( 'clipboard-js', MWA_WP_ZOOM_URL . 'assets/js/clipboard.min.js', array( 'jquery' ) );	
			wp_enqueue_script( 'custom-js', MWA_WP_ZOOM_URL . 'admin/libs/js/custom-js.js' );

			wp_localize_script( 'custom-js', 'tokengenAjax', array( 'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' ))));
			
			wp_enqueue_script( 'moment-min-js', MWA_WP_ZOOM_URL . 'admin/libs/js/moment.min.js' );
			wp_enqueue_script( 'combodate-js', MWA_WP_ZOOM_URL . 'admin/libs/js/combodate.js' );
		}	

		public static function dashboard_files(){
			require_once( 'inc/dashboard.php' );
		}

		public static function tokenmanage_submenu(){
			require_once( 'inc/tokenmngr.php' );
		}

		public static function createmeet_submenu(){
			require_once( 'inc/createmeeting.php' );
		}

		public static function meetlist_submenu(){
			require_once( 'inc/meetlist.php' );
		}

		public static function userlist_submenu(){		
			require_once( 'inc/userlist.php' );
		}	

		public static function upgpro_submenu(){
			require_once( 'inc/upgpro.php' );
		}

		public static function eventmngr_submenu(){
			require_once( 'inc/freeevntmngr.php' );
		}

		public static function all_events_submenu(){
			require_once( 'inc/freeeventsall.php' );
		}

		public static function tuto_submenu(){
			require_once( 'inc/vctutorial.php' );
		}

		/*public static function addwebinar_submenu(){
			require_once( 'inc/freewebiadd.php' );
		}*/
	} // end class MWA_WPZ_Menu
}// end if exist class MWA_WPZ_Menu