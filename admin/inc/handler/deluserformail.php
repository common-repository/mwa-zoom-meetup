<?php 
	/**
	 * The plugin "Delete User" For Send Mail Page.
	 *
	 * @package mwa-zoom-meetup
	 */
	defined( 'ABSPATH' )  or die();
	global $wpdb;	

	
	 if( !check_ajax_referer( 'deluser-token-nonce', 'nonce_deluserdata' ) ) {	
		die("not found");
	}
	else {
	       	$table_name   = $wpdb->prefix . "mwa_zoom_email_users";
	       	$del_type = isset($_POST['del_type']) ? sanitize_text_field($_POST['del_type']) : "";

	       	if($del_type=="multi"){
	       		 $multi_delid_comp 		= isset($_POST['multi_delid']) ? sanitize_text_field($_POST['multi_delid']) : "";
	        	
		        $remove_stripslash_str 	= stripslashes($multi_delid_comp);
		        $remove_other_char_str 	= str_replace( array('[',']','"') , ''  , $remove_stripslash_str );
		        $final_multi_delid_arr 	= explode (",", $remove_other_char_str);          	

		        foreach($final_multi_delid_arr as $multi_delid){
	        		if( $multi_delid != "on" ){						
						$res_multi =  $wpdb->delete($table_name, array('id' => $multi_delid ), array('%d'));
	        		}
				}
	       	}else if($del_type=="single"){
	       		$delid 		= isset($_POST['delid']) ? sanitize_text_field($_POST['delid']) : "";
	       		$res_multi =  $wpdb->delete($table_name, array('id' => $delid ), array('%d'));
	       	}	       

			if($res_multi){
				$send_json_array_del = array( "success_msg_del" => 1 );			 		
		 		wp_send_json($send_json_array_del);
			}else{
				$send_json_array_del = array( "success_msg_del" => 0 );			 		
		 		wp_send_json($send_json_array_del);
			}		
		}	
die();