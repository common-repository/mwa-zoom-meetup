<?php 
	/**
	 * The plugin "Delete Zoom Meeting" Meeting List Page.
	 *
	 * @package mwa-zoom-meetup
	 */
	defined( 'ABSPATH' )  or die();
	global $wpdb;

	require_once( MWA_WP_ZOOM_DIR_PATH . 'libs/zoom/vendor/autoload.php' );	
	
	if( !check_ajax_referer( 'delmeet-nonce', 'nonce_delmeetdata' ) ) {	
		die("not found");
	}
	else {
			$table_name     	= $wpdb->prefix . "mwa_zoom_token";
			$table_meetdata		= $wpdb->prefix . "mwa_zoom_meetdata";
			$result 			= $wpdb->get_row( "SELECT `access_token` FROM `$table_name`", OBJECT );
			$accessToken 		= $result->access_token;
			$data 				= json_decode($accessToken, TRUE);
            $only_accessToken   = $data['access_token'];
	        $meet_did  			= isset($_POST['meet_did']) ? sanitize_text_field($_POST['meet_did']) : "";	
	        $tbl_host 	     	= $wpdb->prefix . "mwa_zoom_host";
			$result_host		= $wpdb->get_row( $wpdb->prepare( "SELECT `meetingid` FROM `$tbl_host` WHERE `id`=%d",$meet_did ), OBJECT );
			$meetingid	 		= $result_host->meetingid;

			try{
				$client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);		
			 
				$response = $client->request('DELETE', '/v2/meetings/'.$meetingid, [
				    "headers" => [
				        "Authorization" => "Bearer $only_accessToken"
				    ]
				]);
				$wpdb->query( $wpdb->prepare( "DELETE FROM `$tbl_host` WHERE `id` = %d",$meet_did ) );

				/* Delete attachment files corresponding with meeting*/
				$res_getmeetdata = $wpdb->get_row( $wpdb->prepare( "SELECT `meetarrdata` FROM `$table_meetdata` WHERE `meeting_id`=%s",$meet_did ), OBJECT );

				if($res_getmeetdata){
					$meetdata_arr  	= unserialize($res_getmeetdata->meetarrdata);
					$meet_docx 		= $meetdata_arr['document'];
					unlink($meet_docx);										
				}				
				
				$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_meetdata` WHERE `meeting_id` = %d",$meet_did ) );
				
				$send_json_array_meetdel = array( "success_meet_del" => 1 );	
				wp_send_json($send_json_array_meetdel);	
				//$json = json_decode($response, TRUE);		
			} catch(Exception $e) {	 
				$tbl_tok 	= $wpdb->prefix."mwa_zoom_token";
				$result_tok	= $wpdb->get_row( "SELECT `authid` FROM `$tbl_tok`", OBJECT );
				$authid		= $result_tok->authid;

				$table_name_auth = $wpdb->prefix."mwa_zoom_auth";		

       			$wpdb->update($table_name_auth, array( 'auth_date' => $date_today, 'status' => "deactive", 'tok_id' => 0 ), array( 'id' => $authid ) , array( '%s','%s','%d' ), array('%d')  );

       			$qry_delexp = "DELETE FROM `$tbl_tok`";	 
       			$wpdb->get_results($qry_delexp);

				$home_url = esc_url(get_site_url()."/wp-admin/admin.php?page=token_manage");
				$send_json_array_meetdel = array( "success_meet_del" => 0 ,"failed_url" => $home_url);			 		
	 			wp_send_json($send_json_array_meetdel);
			}			
			
	}// else close	
die();