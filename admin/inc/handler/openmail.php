<?php 
	/**
	 * The plugin "Open Mail" For Send Mail Page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	global $wpdb;	
	
	 if( !check_ajax_referer( 'openmail-nonce', 'nonce_openmail' ) ) {	
		die("not found");
	}
	else {	
			$meetid  		= isset($_POST['meetid']) ? sanitize_text_field($_POST['meetid']) : "";
        	$table_name     = $wpdb->prefix . "mwa_zoom_host";        	
        	$result 		= $wpdb->get_row( $wpdb->prepare( "SELECT `topicname`,`view_type`,`meetingid`,`meeting_password`,`meeting_date`,`start_time`,`joinurl` FROM `$table_name` WHERE `id`=%d",$meetid ), OBJECT );
        	$meet_arr = array(
        		'meet_id'  		 => $result->meetingid,
				'meet_password'  => $result->meeting_password,
				'meet_date' 	 => $result->meeting_date,
				'meet_time' 	 => $result->start_time,
				'meet_join' 	 => $result->joinurl,
				'meet_topic' 	 => $result->topicname,
				'view_type' 	 => $result->view_type,			  
			);	
		 	wp_send_json($meet_arr);	        		
		}	
die();