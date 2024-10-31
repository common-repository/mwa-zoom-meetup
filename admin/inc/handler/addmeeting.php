<?php 
	/**
	 * The plugin create meeting handler page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined( 'ABSPATH' )  or die();
	global $wpdb;

	require_once( MWA_WP_ZOOM_DIR_PATH . 'libs/zoom/vendor/autoload.php' );
	
	 if( ! wp_verify_nonce( $_POST['meetingform_generate_nonce'],'meeting_form_submit' ) ) {
		die("not found");
	}
	else {				 
	        $meeting_date  	  = isset($_POST['lvdate']) ? sanitize_text_field($_POST['lvdate']) : "";
			$meeting_topic	  = isset($_POST['topicname']) ? sanitize_text_field($_POST['topicname']) : "";
			$meeting_desc	  = isset($_POST['desc']) ? sanitize_text_field($_POST['desc']) : "";
			$meeting_time 	  = isset($_POST['lvtime_zoom']) ? sanitize_text_field($_POST['lvtime_zoom']) : "";
			$meeting_time.= ":00";			
			$meeting_password = isset($_POST['meetpassword']) ? sanitize_text_field($_POST['meetpassword']) : "";
			$timezone 		  = isset($_POST['timezone']) ? sanitize_text_field($_POST['timezone']) : "";
			$jbh 		  	  = isset($_POST['jbh']) ? sanitize_text_field($_POST['jbh']) : "false";
			$jbh_time 		  = isset($_POST['jbh_time']) ? sanitize_text_field($_POST['jbh_time']) : "0";
			$mute_entry 	  = isset($_POST['mute_entry']) ? sanitize_text_field($_POST['mute_entry']) : "false";

			$table_name     	= $wpdb->prefix . "mwa_zoom_token";
			$result 			= $wpdb->get_row( "SELECT `access_token`,`authid` FROM `$table_name`", OBJECT );			
			$accessToken 		= $result->access_token;
			$authid 			= $result->authid;	

			/*Get client id and secret*/
			$table_auth 	   =  $wpdb->prefix . "mwa_zoom_auth";
			$result_auth	   =  $wpdb->get_row( $wpdb->prepare( "SELECT `client_id`,`client_secret` FROM `$table_auth` WHERE `id`=%d",$authid ), OBJECT );
			$get_client_id 	   = $result->client_id;	
			$get_client_secret = $result->clinet_secret;	
	
	        $data = json_decode($accessToken, TRUE);

            $new_accessToken   =  $data['access_token'];
            $new_refresh_token =  $data['refresh_token'];

            /*Get host id and acc type of zoom user*/
			$table_userinfo 	   =  $wpdb->prefix . "mwa_zoom_userinfo";	
			$result_userinfo	   =  $wpdb->get_row( $wpdb->prepare( "SELECT `hostid`,`acc_type` FROM `$table_userinfo` WHERE `authid`=%d",$authid ), OBJECT );
			$get_hostid  = $result_userinfo->hostid;	
			$get_acctype = $result_userinfo->acc_type;	

			if($get_acctype==1){
				$meeting_userid = "me";
				$meeting_duration = isset($_POST['duration']) ? sanitize_text_field($_POST['duration']) : "";
			}else if($get_acctype==2 || $get_acctype==3){
				$meeting_userid = $get_hostid; 
				$meeting_duration = isset($_POST['duration_pro']) ? sanitize_text_field($_POST['duration_pro']) : "";
					$meeting_duration_arr = str_split($meeting_duration);
					$hour = $meeting_duration_arr[0].$meeting_duration_arr[1];
					$min  = $meeting_duration_arr[3].$meeting_duration_arr[4];
			}else{
				$meeting_userid = "me";
				$meeting_duration = isset($_POST['duration']) ? sanitize_text_field($_POST['duration']) : "";
			}

			if($hour==00 && $get_acctype==2 || $get_acctype==3){
				$meeting_duration = ltrim($min,0);
			}else if($hour!=00 && $get_acctype==2 || $get_acctype==3){
				$meeting_duration = ltrim($hour*60+$min,0);
			}else{
				
			}
                
            try {
                 
		 		$client = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);		 		
	            $response = $client->request('POST', "/v2/users/$meeting_userid/meetings", [
	                "headers" => [
	                    "Authorization" => "Bearer $new_accessToken"
	                ],
	                'json' => [
	                    "topic" => $meeting_topic,	                    
	                    "type" => 2,
	                    /*Meeting Type:1 -> Instant meeting. , 2 -> Scheduled meeting. , 3 -> Recurring meeting with no fixed time. ,8 -> Recurring meeting with fixed time.*/
	                    "start_time" => $meeting_date."T".$meeting_time.":00",
	                    "duration" => $meeting_duration,
	                    /*40 minutes time limit on meetings with 3 - 100 people*/
	                    /* "schedule_for" => "",*/
	                    "timezone" => $timezone,
	                    "password" => $meeting_password,
	                    /*Password to join the meeting. By default, password may only contain the following characters: [a-z A-Z 0-9 @ - _ *] and can have a maximum of 10 characters.*/
	                    'agenda' => $meeting_desc,//meeting description
	                    'settings' => [
	                    	"encryption_type" => "e2ee",
	                        "join_before_host" => $jbh,
	                        "jbh_time" => $jbh_time, /*Join before host time of participant like parameter can be - [0, 5, 10] minutes*/
	                        "mute_upon_entry" => $mute_entry,
	                        "watermark" => true,
	                        /*Add watermark when viewing a shared screen.*/
	                        "approval_type" => 0,
	                        /*Automatically approve = 0 , Manually approve = 1 , No registration required = 2*/
	                        "audio"=> "both",
	                        /*Both Telephony and VoIP = both , Telephony only = telephony , VoIP only = voip*/
						    "auto_recording"=> "none",
						    /*Record on local = local , Record on cloud =  cloud , Disabled = none*/
						    "cn_meeting"=> false,
						    /*meeting host in china*/
						    "enforce_login"=> false,
						    "enforce_login_domains"=> "",
	                    ]
	                ],

	            ]);     

	            $data_val   = json_decode($response->getBody());  // Get Response	            
	            $host_id    = $data_val->host_id;
	            $meeting_id = $data_val->id;
	            $join_url   = $data_val->join_url;
	            $meet_pass  = $data_val->password;
	            $topic      = $data_val->topic;

	            /* Insert meeting data created by host */

	            $host_tbl = $wpdb->prefix . "mwa_zoom_host";
	            $data_arr = array(		 
					'id' 				=> '',		 
					'hostid' 			=> $host_id,		 
					'meetingid' 		=> $meeting_id,
					'meeting_password' 	=> $meet_pass,
					'timezone' 			=> $timezone,
					'duration' 			=> $meeting_duration,
					'meeting_date' 		=> $meeting_date,
					'start_time' 		=> $meeting_time,
					'joinurl' 			=> $join_url,
					'topicname' 		=> $topic,
					'desc' 				=> $meeting_desc,
					'view_type' 		=> 0,	
					'batch'				=> 0,	 
					'teacherid' 		=> 0
				);

				/*Specifier Array*/
				$format_arr = array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%d','%d');
				$wpdb->insert($host_tbl,$data_arr,$format_arr);

				/*Attach document with meeting -- code start*/
				if($_FILES['mwa_meet_file']['name'] != ''){
					$meetdata_tbl = $wpdb->prefix . "mwa_zoom_meetdata";
				    $uploadedfile = $_FILES['mwa_meet_file'];
				    $upload_overrides = array( 'test_form' => false );

				    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				    $imageurl = "";
				    if ( $movefile && ! isset( $movefile['error'] ) ) {
				       $imageurl = $movefile['url'];

				       $zip_meetarr = serialize(array(	
				       		'document'		=> $imageurl
				       ));

				       $data_meet_arr = array(
				       		'id' 				=> '',	
				       		'meeting_id'		=> $meeting_id,
				       		'meetarrdata'		=> $zip_meetarr	
				       );

				       /*Specifier Array*/
					$format_meetdata_arr = array('%d','%s','%s');
					$wpdb->insert($meetdata_tbl,$data_meet_arr,$format_meetdata_arr);				       
				    } else {
				       //echo $movefile['error'];
				    }
				 }
				/*Attach document with meeting -- code end*/

				$showmeeting_list_url = esc_url(get_site_url()."/wp-admin/admin.php?page=manage_meeting");
				$send_json_array = array( "success_msg" => "1","showmeeting_list_url" => $showmeeting_list_url );
 				wp_send_json($send_json_array);	           
	        }
	        catch(Exception $e) {	            
	            /*Delete token first and update auth table*/
	            $del_toktblname  = $wpdb->prefix."mwa_zoom_token";
	            $wpdb->delete($del_toktblname, array('authid' => $authid), array('%d'));

				$upd_authblname  = $wpdb->prefix."mwa_zoom_auth";
				
				$updesc_html_exp = array( 					
					'status' 	=> "deactive",
					'tok_id' 	=> 0						
				);

				$updesc_format = array('%s','%d');

				$updesc_where = array( 
					'id' => $authid						
				);	

				$updesc_where_format = array('%d');

				$wpdb->update($upd_authblname, $updesc_html_exp, $updesc_where,$updesc_format,$updesc_where_format);

	           	$home_url = esc_url(get_site_url()."/wp-admin/admin.php?page=token_manage");
	            $send_json_array = array( "success_msg" => "0","home_url" => $home_url );	
	            wp_send_json($send_json_array);
	    }// catch close 	 
	}// else close 	
die();	