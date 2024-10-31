<?php
/**
 * @link              https://mywebapp.in/
 * @since             1.0
 * @package           mwa-zoom-meetup
 *
 * Plugin Name:		  Video Conferencing - Zoom Meetings
 * Plugin URI:        https://wordpress.org/plugins/mwa-zoom-meetup/
 * Description:       Video Conferencing - Zoom Meetings is capable to manage Zoom Meetings, Video Conferencing, Schedule Video Conferences and Record meetings directly from WordPress Dashboard
 * Version:           2.8
 * Author:            MyWebApp
 * Author URI:        https://mywebapp.in/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       MWA_WP_ZOOM
 * Requires PHP:      7.2  
 * Domain Path:       /languages
 * Requires at least: 5.2
**/

defined( 'ABSPATH' ) or die();

if ( ! defined( 'MWA_WP_ZOOM_URL' ) ) {
	define( "MWA_WP_ZOOM_URL", plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'MWA_WP_ZOOM_DIR_PATH' ) ) {
	define( 'MWA_WP_ZOOM_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'MWA_WP_ZOOM_FILE' ) ) {
	define( 'MWA_WP_ZOOM_FILE', __FILE__ );
}

if ( ! defined( 'MWA_WP_ZOOM' ) ) {
	define( 'MWA_WP_ZOOM', 'MWA_WP_ZOOM' );
}

/* Load text domain */
add_action( 'plugins_loaded', 'MWA_WP_ZoomTranslate' );
function MWA_WP_ZoomTranslate() {
	load_plugin_textdomain('MWA_WP_ZOOM', false, dirname( plugin_basename(__FILE__)).'/languages/' );
}

if (! class_exists( 'MWA_WP_ZOOM_CLS' )){
	final class MWA_WP_ZOOM_CLS{

		private static $mwawpzoom_instance = null;

		private function __construct()
		{
			$this->setup_hooks();
			$this->setup_mwawpzoom_db();		
		}

		private function setup_hooks() {		

			if ( is_admin() ) {
				require_once( 'admin/class-mwa-wpz-admin.php' );				
				new MWAWPZoomAdmin;	// Create Class object				
			}
			require_once( 'public/class-mwa-wpz-public.php' );
			new MWAWPZoomPublic; // Create Class object			
		}

		public static function instance_getter() {
			if ( is_null( self::$mwawpzoom_instance ) ) {
				self::$mwawpzoom_instance = new self();
			}
			return self::$mwawpzoom_instance;
		}

		/*Set up MWA WP Zoom Database*/
		private function setup_mwawpzoom_db() {
			require_once('admin/inc/db/class-mwa-wpz-dbsetup.php');
			register_activation_hook( __FILE__, array( 'MWA_WPZ_Dbsetup', 'setupdb' ) );
		}	
		
	}// class - MWA_WP_ZOOM_CLS close
}// if class - MWA_WP_ZOOM_CLS exist close
MWA_WP_ZOOM_CLS::instance_getter();