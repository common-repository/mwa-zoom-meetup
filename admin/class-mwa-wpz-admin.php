<?php
defined( 'ABSPATH' ) or die();

if (! class_exists( 'MWAWPZoomAdmin' )){
	class MWAWPZoomAdmin
	{
		/**
	     * @return MWAWPZoomAdmin constructor.
	     */

		 function __construct(){

		 	require_once( MWA_WP_ZOOM_DIR_PATH . 'admin/class-mwa-wpz-menu.php' );
			require_once( MWA_WP_ZOOM_DIR_PATH . 'admin/class-mwa-wpz-actions.php' );
			require_once( MWA_WP_ZOOM_DIR_PATH . 'admin/widget/class-mwa-wpz-widget.php' );
			require_once( MWA_WP_ZOOM_DIR_PATH . 'admin/widget/class-mwa-wpz-widgetbtn.php' );
			new MWAWPZoomWidget;
			new MWAWPZoomWidgetBtn;			

		 	add_action( 'admin_menu', array( 'MWA_WPZ_Menu', 'wpz_create_menu' ) );			

			add_filter( 'plugin_action_links_' . plugin_basename(MWA_WP_ZOOM_FILE), array( 'MWA_WPZ_Actions', 'mwa_wp_zoom_plugin_actions_settings_links' ) );

			/*Session Action*/
			add_action('plugins_loaded', array('MWA_WPZ_Actions','mwazoom_session_manager_start_session'));

			add_action( 'wp_ajax_addmanagetoken', array( 'MWA_WPZ_Actions', 'add_manage_token' ) );

			/*calling ajax & save ajax request to session library - token param*/
			add_action("wp_ajax_savetokentosession", array( 'MWA_WPZ_Actions', 'save_tokensession' ) );

			add_action("wp_ajax_edittokenajax", array( 'MWA_WPZ_Actions', 'edit_tokenaction' ) );

			add_action("wp_ajax_updatetokenajax", array( 'MWA_WPZ_Actions', 'update_tokenaction' ) );

			add_action( 'wp_ajax_addmeeting', array( 'MWA_WPZ_Actions', 'add_meeting' ) );

			add_action( 'wp_ajax_adduser', array( 'MWA_WPZ_Actions', 'add_user_for_email' ) );

			add_action( 'wp_ajax_deluser', array( 'MWA_WPZ_Actions', 'delete_user_for_email' ) );

			add_action( 'wp_ajax_edituser', array( 'MWA_WPZ_Actions', 'update_user_for_email' ) );

			add_action( 'wp_ajax_openmail', array( 'MWA_WPZ_Actions', 'open_mail' ) );

			add_action( 'wp_ajax_sendmail', array( 'MWA_WPZ_Actions', 'send_mail' ) );		

			add_action( 'wp_ajax_deletemeeting', array( 'MWA_WPZ_Actions', 'delete_meeting' ) );

			add_action( 'wp_ajax_addmeetviewtype', array( 'MWA_WPZ_Actions', 'meeting_viewtype' ) );
			
			add_action( 'wp_ajax_delauthtokenajax', array( 'MWA_WPZ_Actions', 'delete_authtoken' ) );
		 } // end constructor
	} //  end class MWAWPZoomAdmin
} // end if exist class MWAWPZoomAdmin 