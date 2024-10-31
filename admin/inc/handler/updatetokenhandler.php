<?php 
	/**
	 * The plugin "Update Token and details" For Token Genrator Page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	global $wpdb;	

	/* First, check nonce */
    $table_name = $wpdb->prefix . "mwa_zoom_auth";

    if( check_ajax_referer( 'update-token-nonce', 'nonce_upddata' ) ) {	
		
		$authid     = isset($_POST['get_authid']) ? sanitize_text_field( $_POST['get_authid'] ) : "" ;
		$apiid      = isset($_POST['get_apiid']) ? sanitize_text_field( $_POST['get_apiid'] ) : "" ;	
		$uac    	= isset($_POST['get_uac']) ? sanitize_text_field( $_POST['get_uac'] ) : "" ;
		$usermail   = isset($_POST['get_usermail']) ? sanitize_text_field( $_POST['get_usermail'] ) : "" ;
		$secret     = isset($_POST['get_apikey']) ? sanitize_text_field( $_POST['get_apikey'] ) : "" ;
		$stat       = isset($_POST['get_stat']) ? sanitize_text_field( $_POST['get_stat'] ) : "" ;
		$authdate   = date("Y-m-d");

		$data_update = array( 
			'uac' 			=> $uac,
			'usermail'		=> $usermail,
			'client_id' 	=> $apiid,
			'client_secret' => $secret,
			'auth_date' 	=> $authdate,
			'status'	 	=> $stat
		);		

		$data_where 				= array('id' => $authid);
		$data_updata_format 		= array('%s','%s','%s','%s','%s','%s');
		$data_updata_format_where 	= array('%d');
		$wpdb->update($table_name, $data_update, $data_where,$data_updata_format,$data_updata_format_where);
		$send_json_array = array( "success_upd" => "Updated Successfully" );
		wp_send_json($send_json_array);				
	}
	else {
		die("not found");
	}
	die();
?>
	