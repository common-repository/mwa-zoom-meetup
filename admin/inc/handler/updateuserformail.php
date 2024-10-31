<?php 
	/**
	 * The plugin "Update User" For Send Mail Page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	global $wpdb;	
	
	 if( !check_ajax_referer( 'edituser-email-nonce', 'get_editusernonce' ) ) {	
		die("not found");
	}
	else {
	        $get_saveuid  	  = isset($_POST['get_saveuid']) ? sanitize_text_field($_POST['get_saveuid']) : "";	
	        $get_field_user	  = isset($_POST['get_field_user']) ? sanitize_text_field($_POST['get_field_user']) : "";	
	        $get_field_email  = isset($_POST['get_field_email']) ? sanitize_text_field($_POST['get_field_email']) : "";
	         $get_field_stat  = isset($_POST['get_field_stat']) ? sanitize_text_field($_POST['get_field_stat']) : "";	        
			
			$table_name   	 = $wpdb->prefix . "mwa_zoom_email_users";			
			$res			 = $wpdb->update($table_name, array( 'username' => $get_field_user, 'email' => $get_field_email, 'status' => $get_field_stat ), array( 'id' => $get_saveuid), array( '%s','%s','%s'), array( '%d' ) );
			
			if($res){
				$send_json_array_upd = array("success_msg_update"=>"1");
		 		wp_send_json($send_json_array_upd);
			}else{
				$send_json_array_upd = array("success_msg_update"=>"0");
		 		wp_send_json($send_json_array_upd);
			}
		}	
die();