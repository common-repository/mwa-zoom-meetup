<?php
	/**
	 * The plugin all action handler page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();

	if(!class_exists('MWA_Zoom_Actions')){

		class MWA_Zoom_Actions{
			public static function mwazoom_shortcode_resource() {
				/* 	SYNTAX				
					wp_register_script( $handle, $src, $deps, $ver, $in_footer );
				*/	

				wp_register_script("mwa-zoom-custom-script", MWA_WP_ZOOM_URL.'public/libs/js/mwa-zoom-script.js');
				wp_register_script("mwa-zoom-custom-style", MWA_WP_ZOOM_URL.'public/libs/css/mwa-zoom-style.css');	
				wp_register_script( 'bootstrap_js', MWA_WP_ZOOM_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), true, true );
				wp_register_script( 'bootstrap_css', MWA_WP_ZOOM_URL . 'assets/css/bootstrap.min.css'  );	
				wp_register_script( 'datatable-css', MWA_WP_ZOOM_URL . 'admin/libs/css/datatables.min.css' );
				wp_register_script( 'datatable-js', MWA_WP_ZOOM_URL . 'admin/libs/js/datatables.min.js', array( 'jquery' ) );		
				wp_enqueue_style('fontawesome_css', MWA_WP_ZOOM_URL . 'libs/fontawsome/css/font-awesome.min.css');
				wp_register_script( 'clipboard-js', MWA_WP_ZOOM_URL . 'assets/js/clipboard.min.js', array( 'jquery' ) );
			}// end function mwazoom_shortcode_resource
		}// end class MWA_Zoom_Actions		
	}// end if exist class MWA_Zoom_Actions
?>