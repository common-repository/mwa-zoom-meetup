<?php 
	/**
	 * The plugin "Save token to session" For Token Generator Page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	
	require_once( MWA_WP_ZOOM_DIR_PATH . 'libs/aura/vendor/autoload.php' );

	$session_factory = new \Aura\Session\SessionFactory;
	$session = $session_factory->newInstance($_COOKIE);	

	/*get a _Segment_ object*/
	$segment = $session->getSegment('Vendor\Package\ClassName');
	
	$authid  = isset($_POST['authid']) ? sanitize_text_field( $_POST['authid'] ) : "" ;
	$client  = isset($_POST['client']) ? sanitize_text_field( $_POST['client'] ) : "" ;
	$secret  = isset($_POST['secret']) ? sanitize_text_field( $_POST['secret'] ) : "" ;	
	$reduri  = isset($_POST['reduri']) ? sanitize_text_field( $_POST['reduri'] ) : "";
	$umail	 = isset($_POST['umail']) ? sanitize_text_field( $_POST['umail'] ) : "";
	
	/* Save value from Ajax Request to session variable */

	/*set some values on the segment*/
	$segment->set('client', $client);
	$segment->set('secret', $secret);
	$segment->set('authid', $authid);
	$segment->set('reduri', $reduri);


	if(isset($_POST['client']) && isset($_POST['secret']) && $umail!="") {
		$send_json_array = array( "lockopen" => "1" );
	 	wp_send_json($send_json_array);
	}else if($umail==""){
		$send_json_array = array( "lockopen" => "2" );
	 	wp_send_json($send_json_array);
	}
	else {
		$send_json_array = array( "lockopen" => "0" );
	 	wp_send_json($send_json_array);
	}	
	die();
?>	