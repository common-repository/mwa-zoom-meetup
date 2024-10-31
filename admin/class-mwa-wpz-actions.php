<?php
defined( 'ABSPATH' ) or die();

if( ! class_exists('MWA_WPZ_Actions') ) {
	class MWA_WPZ_Actions {
		
		/*Action for manage token data save*/
		public static function add_manage_token() {
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/tokensave.php' );
		}		

		public static function save_tokensession(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/saveajaxtosession.php' );			
		}

		public static function edit_tokenaction(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/edittokenhandler.php' );			
		}

		public static function update_tokenaction(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/updatetokenhandler.php' );			
		}

		public static function add_meeting(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/addmeeting.php' );			
		}

		public static function add_user_for_email(){			
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/adduserformail.php' );
		}

		public static function delete_user_for_email(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/deluserformail.php' );			
		}

		public static function update_user_for_email(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/updateuserformail.php' );			
		}

		public static function open_mail(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/openmail.php' );			
		}

		public static function send_mail(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/sendmail.php' );			
		}

		public static function delete_meeting(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/deletemeeting.php' );			
		}

		public static function meeting_viewtype(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/addmeetviewtype.php' );			
		}		
		
		/*Check -> Session Start*/
		public static function mwazoom_session_manager_start_session()
		{
		    if (session_status() !== PHP_SESSION_ACTIVE) {
		        session_start();
		    }
		}

		public static function delete_authtoken(){
			include( MWA_WP_ZOOM_DIR_PATH . 'admin/inc/handler/delauthtoken.php' );			
		}

		/**
	     * Add Custom Links to All Plugins list page for this plugin
	     * @param $mwa_links
	     * @return mixed
	     */
	    public static function mwa_wp_zoom_plugin_actions_settings_links($mwa_links)
	    {
	        $mwa_settings_link = sprintf( '<a href="%1$s">%2$s</a>', esc_url(admin_url( 'admin.php?page=token_manage' )), esc_html__( 'Settings', 'MWA_WP_ZOOM' ) );
	        array_unshift( $mwa_links, $mwa_settings_link );

	        $mwa_links['go_pro'] = sprintf( '<a href="%1$s" style="color:red;font-weight:800;" target="_blank" class="elementor-plugins-gopro">%2$s</a>', esc_url('https://mywebapp.in/zoom-meetings/'), esc_html__( 'Upgrade To Pro', 'MWA_WP_ZOOM' ) );
	        	        
	        return $mwa_links;
	    }	           
	}// end class MWA_WPZ_Actions
}// end if exist class MWA_WPZ_Actions