<?php 
	/**
	 * The plugin "Meeting View Type Handler" For Meeting list Page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	global $wpdb;	
	
	 if( !check_ajax_referer( 'mvtnonce-nonce', 'get_mvtnonce' ) ) {	
		die("not found");
	}
	else {
	        $get_mvtid    = isset($_POST['get_mvtid']) ? sanitize_text_field($_POST['get_mvtid']) : "";	
	        $get_mvtval	  = isset($_POST['get_mvtval']) ? sanitize_text_field($_POST['get_mvtval']) : "";
			$table_name   = $wpdb->prefix . "mwa_zoom_host";			

			$update_mvt = array(
								'view_type' => $get_mvtval
							);
			$update_mvt_where = array(
								'id' => $get_mvtid
							);
			$update_mvt_format = array('%d');			
			$update_mvt_where_format = array('%d');

			$res = $wpdb->update($table_name, $update_mvt, $update_mvt_where,$update_mvt_format,$update_mvt_where_format);

			if($res){
				$send_json_array_mvt = array("success_mvt"=>"1");
		 		wp_send_json($send_json_array_mvt);
			}else{
				$send_json_array_mvt = array("success_mvt"=>"0");
		 		wp_send_json($send_json_array_mvt);
			}
		}// else close	
die();