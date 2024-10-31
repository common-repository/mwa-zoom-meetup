<?php 
	/**
	 * The plugin "Edit Token" For Add Token Page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	global $wpdb;	

	/* First, check nonce */
    if( check_ajax_referer( 'edit-token-nonce', 'nonce_data' ) ) {	
		
		$authid    = isset($_POST['authid']) ? sanitize_text_field( $_POST['authid'] ) : "" ;
		$client    = isset($_POST['client']) ? sanitize_text_field( $_POST['client'] ) : "" ;
		$secret    = isset($_POST['secret']) ? sanitize_text_field( $_POST['secret'] ) : "" ;	
		$uac       = isset($_POST['uac']) ? sanitize_text_field( $_POST['uac'] ) : "";
		$usermail  = isset($_POST['usermail']) ? sanitize_text_field( $_POST['usermail'] ) : "";
		$stat      = isset($_POST['stat']) ? sanitize_text_field( $_POST['stat'] ) : "";

		if(isset($_POST['client']) && isset($_POST['secret'])) {
			$tokenarr_send_json = array( "edittoken_authid" => $authid , "edittoken_client" => $client , "edittoken_secret" => $secret ,"edittoken_uac" => $uac ,"edittoken_usermail" => $usermail, "edittoken_stat" => $stat );			
		 	wp_send_json($tokenarr_send_json);
		}	
	}
	else {
		die("not found");
	}		
	die();
?>