<?php
	/**
	 * The plugin send mail handler page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	global $wpdb;	
	
	 if( !check_ajax_referer( 'send-useremail-nonce', 'nonce_sendmail' ) ) {	
		die("not found");
	}
	else{						
			$multi_uid  	= isset($_POST['multi_uid']) ? sanitize_text_field($_POST['multi_uid']) : "";
			$multi_uid_arr  = json_decode(stripslashes($multi_uid));
			$meet_id  		= isset($_POST['meet_id']) ? sanitize_text_field($_POST['meet_id']) : "";
			$pageurl_mail   = isset($_POST['pageurl_mail']) ? sanitize_email($_POST['pageurl_mail']) : "";
			
			/*Get Meeting Details*/
			$tbl_meet 	 	= $wpdb->prefix . "mwa_zoom_host";			
			$result_meet 	= $wpdb->get_row( $wpdb->prepare( "SELECT `meetingid`,`meeting_password`,`topicname`,`meeting_date`,`start_time`,`joinurl`,`view_type` FROM `$tbl_meet` WHERE `meetingid`=%s",$meet_id ), OBJECT );
			$meetingid 		= $result_meet->meetingid;
			$meet_pass 		= $result_meet->meeting_password;
			$meet_topic 	= $result_meet->topicname;
			$meet_dttime	= $result_meet->meeting_date." , ".$result_meet->start_time;
			$meet_jourl 	= $result_meet->joinurl;
			$meet_vtype 	= $result_meet->view_type;

        	$table_name     = $wpdb->prefix . "mwa_zoom_email_users";
        	/* Multiple User email send By Admin*/
			foreach($multi_uid_arr as $sing_multi_uid){
				$result 		= $wpdb->get_row( $wpdb->prepare( "SELECT `username`,`email` FROM `$table_name` WHERE `id`=%d AND `status`=%s",$sing_multi_uid,"1" ), OBJECT );

				$meet_username 	= sanitize_user($result->username);
				$meet_mail     	= $result->email;

				$to 	 		= sanitize_email($meet_mail);
				$subject 		= 'Your Upcoming Meeting - ';

				if($meet_vtype==0){ // for any one can join meeting
					 $message = "Hi $meet_username , Your New Meeting\r\n Topic : $meet_topic\r\n Meeeting ID : $meetingid\r\n Meeting Password: $meet_pass\r\n Time : $meet_dttime \r\n Join : $meet_jourl\r\n - Thank You.";
				}else{ // for only registered user join meeting
					 $message = "Hi $meet_username , Your New Meeting\r\n Topic : $meet_topic\r\n Meeeting ID : $meetingid\r\n Meeting Password: $meet_pass\r\n Time : $meet_dttime \r\n Join : $pageurl_mail\r\n - Thank You.";
				}		

				/* SYNTAX
					wp_mail( $to, $subject, strip_tags($message), $headers );
				*/	
				wp_mail( $to, $subject, strip_tags($message), "");
				$send_json_array_mail = array( "success_msg_email" => 1 );
				wp_send_json($send_json_array_mail);	        		
			}// end foreach loop
		}			
die();