<?php
defined( 'ABSPATH' ) or die();

if (! class_exists( 'MWAWPZoomPublic' )){
	class MWAWPZoomPublic
	{	
		/**
	     * @return MWAWPZoomPublic constructor.
	     */

		function __construct(){

			require_once( MWA_WP_ZOOM_DIR_PATH.'public/actions/class-mwa-zoom-actions.php' );
			require_once( MWA_WP_ZOOM_DIR_PATH.'public/template/class-mwa-zoom-template.php' );
			require_once( MWA_WP_ZOOM_DIR_PATH.'admin/widget/class-mwa-wpz-widget.php' );
			new MWAWPZoomWidget;

			/*First register resources with init */
			add_action( 'init', array('MWA_Zoom_Actions','mwazoom_shortcode_resource' ));
			/* Display Meeting Shortcode */
			add_shortcode('mwazoom_showmeeting', array('MWA_Zoom_Template','show_meeting_shortcode'));
		}// constructor close
	}// class MWAZoomPublic close
}// if class exist MWAZoomPublic close	