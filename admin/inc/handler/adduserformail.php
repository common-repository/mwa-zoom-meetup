<?php 
	/**
	 * The plugin "AddUser" For Send Mail Page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	global $wpdb;

	if( ! wp_verify_nonce( $_POST['adduserform_generate_nonce'],'adduser_form_submit' ) ) {
		die("not found");
	}
	else {
	        $username  	  = isset($_POST['username']) ? sanitize_user($_POST['username']) : "";
			$usermail	  = isset($_POST['usermail']) ? sanitize_email($_POST['usermail']) : "";
			$table_name   = $wpdb->prefix . "mwa_zoom_email_users";
			$status   	  = esc_attr("1");
			$data_arr = array(		 
				'id'		=> '',		 
				'username' 	=> $username,		 
				'email' 	=> $usermail,
				'status' 	=> $status				 
			);

			/*Specifier Array*/
			$format_arr = array('%d','%s','%s');
			if($wpdb->insert($table_name,$data_arr,$format_arr)){
				$send_json_array = array( "success_msg" => "1" );
		 		wp_send_json($send_json_array);
			}else{
				$send_json_array = array( "success_msg" => "0" );
		 		wp_send_json($send_json_array);
			}
		}// else close	
die();