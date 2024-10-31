<?php
	/**
	 * The plugin "Token Save to databse" For Token Generator Page.
	 *
	 * @package mwa-zoom-meetup
	 */

defined( 'ABSPATH' ) or die();
global $wpdb;

$table_name     	= $wpdb->prefix . "mwa_zoom_auth";
$rowcount 			= $wpdb->get_var( "SELECT COUNT(*) FROM `$table_name`");

if( ! wp_verify_nonce( $_POST['tokenmanageform_generate_nonce'],'tokmang_form_submit' ) ) {
	die("not found");
}
else {	

	$aura_factory = MWA_WP_ZOOM_DIR_PATH . 'libs/aura/vendor/autoload.php';
	include($aura_factory);

	$session_factory = new \Aura\Session\SessionFactory;
	$session = $session_factory->newInstance($_COOKIE);	

	/*get a _Segment_ object*/
	$segment = $session->getSegment('Vendor\Package\ClassName');
	
	/*Empty the session segment*/
	$segment->set('client', "");
	$segment->set('secret', "");
	$segment->set('authid', "");
	$segment->set('reduri', "");

	$uac       = isset($_POST['uac']) ? sanitize_text_field( $_POST['uac'] ) : "" ;
	$usermail  = isset($_POST['usermail']) ? sanitize_text_field( $_POST['usermail'] ) : "" ;
	$apiid     = isset($_POST['apiid']) ? sanitize_text_field( $_POST['apiid'] ) : "" ;
	$apikey    = isset($_POST['apikey']) ? sanitize_text_field( $_POST['apikey'] ) : "" ;	
	$red_uri   = isset($_POST['urifortok']) ? sanitize_text_field( $_POST['urifortok'] ) : "";
	$authdate  = date( 'Y-m-d');
	$status    = "deactive";
	
	if($rowcount==0){			
		$data_arr = array(		 
			'id' 			=> '',		 
			'uac' 			=> $uac,
			'usermail'		=> $usermail,		 
			'client_id' 	=> $apiid,
			'client_secret' => $apikey,
			'redirect_uri' 	=> $red_uri,
			'auth_date' 	=> $authdate,
			'status' 		=> $status,
			'remark' 		=> '',
			'tch_id' 		=> 0,
			'tok_id' 		=> 0		 
		);	

		/*Specifier Array*/
		$format_arr = array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%d','%d');

		$wpdb->insert($table_name,$data_arr,$format_arr);

		$last_auth_id = $wpdb->insert_id;

		$table_userinfo = $wpdb->prefix . "mwa_zoom_userinfo";
		$rowcount_info 		= $wpdb->get_var( "SELECT COUNT(*) FROM `$table_userinfo` WHERE `authid`='$last_auth_id'");		

		if($rowcount_info >0){
			$wpdb->insert($table_userinfo,array( 'id' => "", 'authid' => $last_auth_id, 'hostid' => "", 'acc_type' => "", 'first_name' => "", 'last_name' => "", 'email' => "", 'account_id' => "", 'role_name' => "", 'personal_meeting_url' => "", 'timezone' => "", 'host_key' => "" ), array('%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'));
		}   	

		$send_json_array = array( "success_msg" => "1" );
	 	wp_send_json($send_json_array);
	}
	else {		
		$send_json_array = array( "success_msg" => "0" );
	 	wp_send_json($send_json_array);
	}	
	die();
}