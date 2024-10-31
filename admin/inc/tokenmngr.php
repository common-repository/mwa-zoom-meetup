<?php
	/**
	 * The plugin page view - the "tokenmanager" page of the plugin.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();
	global $wpdb;

	require_once( MWA_WP_ZOOM_DIR_PATH . 'libs/zoom/vendor/autoload.php' );
	require_once( MWA_WP_ZOOM_DIR_PATH . 'libs/aura/vendor/autoload.php' );	

	$session_factory = new \Aura\Session\SessionFactory;
	$session = $session_factory->newInstance($_COOKIE);

	/*get a _Segment_ object*/
	$segment = $session->getSegment('Vendor\Package\ClassName');

	if(!empty($segment->get('client'))){
	    $clientid 	= esc_attr($segment->get('client'));
	    $secret 	= esc_attr($segment->get('secret'));	
	    $authid 	= esc_attr($segment->get('authid'));  
	    $reduri 	= esc_url($segment->get('reduri')); 
	}

	$table_name_auth 	= $wpdb->prefix . 'mwa_zoom_auth';
	$query 				= "SELECT `id`,`uac`,`usermail`,`client_id`,`client_secret`,`redirect_uri`,`tok_id`,`status`,`auth_date` FROM `$table_name_auth`";	
	$result_token_mngr 	= $wpdb->get_results($query, OBJECT );
	$rowcount 			= $wpdb->get_var( "SELECT COUNT(*) FROM `$table_name_auth`");
	 
  if(isset($_REQUEST['code'])){   
   try {  
        
        $client = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);
   		
          $response = $client->request('POST', '/oauth/token', [
              "headers" => [
                  "Authorization" => "Basic ". base64_encode($clientid.':'.$secret)
              ],
              'form_params' => [ 
                  "grant_type" => "authorization_code",
                  "code" => sanitize_text_field($_REQUEST['code']),
                  "redirect_uri" => $reduri
              ],
          ]);
       
        	$token = $response->getBody()->getContents();

            $table_name_token = $wpdb->prefix . 'mwa_zoom_token';           
			$timezn 	= wp_timezone_string(); // get time zone
			$tok_date 	= get_date_from_gmt($timezn,date("Y-m-d"));
			$tok_time 	= current_time( "H:i:s", $gmt = 0 );            
     
            $wpdb->insert($table_name_token,array( 'id' => "", 'access_token' => $token, 'tok_date' => $tok_date, 'tok_time' => $tok_time, 'tch_id' => 0, 'authid' => $authid ), array( '%d','%s','%s','%s','%d','%d' ));
            
            $last_tok_id = $wpdb->insert_id;
            
            $upd_qt_arr = array(            				
							'auth_date'	=> $tok_date,
							'status'	=> "active",
							'tok_id' 	=> $last_tok_id							
            			);
            $data_where = array('id' => $authid);
            $data_update_format = array('%s','%s','%d');
            $data_updata_format_where = array('%d');

            $wpdb->update($table_name_auth, $upd_qt_arr, $data_where,$data_update_format,$data_updata_format_where);

            /*Check User info data exist or not*/
			$chk_usrinfo_qry 	  = $wpdb->prepare( "SELECT `usermail` FROM `$table_name_auth` WHERE `id`=%d",$authid ); 
			$result_usrinfo	   	  =  $wpdb->get_row( $chk_usrinfo_qry, OBJECT );
			$get_usrinfo_email 	  = $result_usrinfo->usermail;

			$data_ui_full_tok = json_decode($token, TRUE);
			$ui_accessToken   =  $data_ui_full_tok['access_token'];
			
			$response_ui = $client->request('GET', '/v2/users/'.$get_usrinfo_email.'?login_type=100', [
	            "headers" => [
	                "Authorization" => "Bearer $ui_accessToken"
	            ]],
	        );

			$data_ui 		=  json_decode($response_ui->getBody());
			$ui_hostid  	=  $data_ui->id;
			$ui_acc_type   	=  $data_ui->type;
			$ui_first_name 	=  $data_ui->first_name;
			$ui_last_name  	=  $data_ui->last_name;
			$ui_email  		=  $data_ui->email;
			$ui_account_id 	=  $data_ui->account_id;
			$ui_role_name 	=  $data_ui->role_name;
			$ui_pmurl  		=  $data_ui->personal_meeting_url;
			$ui_timezone  	=  $data_ui->timezone;
			$ui_host_key  	=  $data_ui->host_key;

			$table_name_userinfo = $wpdb->prefix . 'mwa_zoom_userinfo';  			
			$result_ui	   =  $wpdb->get_row( $wpdb->prepare( "SELECT `authid` FROM `$table_name_userinfo` WHERE `authid`=%d",$authid ), OBJECT );				
			if($result_ui){
				//update query				
				$upd_ui = array( 
					'hostid' 		=> $ui_hostid,
					'acc_type' 		=> $ui_acc_type,
					'first_name'	=> $ui_first_name,
					'last_name' 	=> $ui_last_name,
					'email'	 		=> $ui_email,
					'account_id'	=> $ui_account_id,
					'role_name'	    => $ui_role_name,
					'personal_meeting_url'	=> $ui_pmurl,
					'timezone'	 	=> $ui_timezone,
					'host_key'		=> $ui_host_key
				);	
				$data_upd_ui_where = array('authid' => $authid);
				$data_upd_ui_format = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
				$data_upd_ui_where_format = array('%d');
				$wpdb->update($table_name_userinfo, $upd_ui, $data_upd_ui_where,$data_upd_ui_format,$data_upd_ui_where_format);
			}else{	
				$data_arr_ins_ui = array(
    				'id' 		=> "",
    				'authid' 	=> $authid,
    				'hostid'	=> "",
    				'acc_type'	=> "",
    				'first_name'=> "",
    				'last_name' => "",
    				'email'		=> "",
    				'account_id'=> "",
    				'role_name' => "",
    				'personal_meeting_url' => "",
    				'timezone'  => "", 
    				'host_key'  => ""
    			);
    			$format_arr_ins_ui = array('%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');

				$wpdb->insert($table_name_userinfo,$data_arr_ins_ui,$format_arr_ins_ui); 
				$wpdb->update($table_name_userinfo, $upd_ui, $data_upd_ui_where,$data_upd_ui_format,$data_upd_ui_where_format);		
			}            
           	$home_url = esc_url(get_site_url()."/wp-admin/admin.php?page=token_manage");
           	?>
                <script>window.location.replace('<?php echo $home_url; ?>');</script>
            <?php          
         
      } catch(Exception $e) {
          echo $e->getMessage();
     }
	}// if close -  Get request code from zoom
?>

<div class="mwa_tokengen container">
	<div class="col shadow p-3 rounded mwa_zoom_head">
		<h4 class="tokenmanage_head"><?php esc_html_e( 'Token Manager', MWA_WP_ZOOM ); ?></h4>
	</div>
	<header>
		<div id="material-tabs">
			<a id="tab1-tab" href="#tab1" class="active"><?php esc_html_e( 'Add Token', MWA_WP_ZOOM ); ?></a>
			<a id="tab2-tab" href="#tab2"><?php esc_html_e( 'Manage Token', MWA_WP_ZOOM ); ?></a>			
			<span class="yellow-bar"></span>
		</div>
	</header>
	<div id="loader" style="display: none;">
	</div>
	<div class="tab-content" id="mwa_tokenmanage_div">
		<div id="tab1">
			<form id="addtokenform" method="post">
				<div class="alert alert-success alert-dismissible fade show  mb-5" role="alert">
				  <?php esc_html_e( 'Help', MWA_WP_ZOOM ); ?> ! <strong><a target="_blank" href="https://mywebapp.in/how-to-create-zoom-app-id-and-secret-key/"><?php esc_html_e( 'Click Here', MWA_WP_ZOOM ); ?></a></strong>  <?php esc_html_e( 'How to create zoom api id and secret key?', MWA_WP_ZOOM ); ?>
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>				
				<?php wp_nonce_field( 'tokmang_form_submit', 'tokenmanageform_generate_nonce' );?>
				<div class="form-row">
					<?php							
						$url = esc_url(get_site_url()."/wp-admin/admin.php?page=token_manage");
					?>
					 <div class="col-md-4 form-group">
	                 <label class="vc_lbl" for="uac"><?php esc_html_e( 'Account Name', MWA_WP_ZOOM ); ?></label>
	                 <input type="text" name="uac" id="uac" class="form-control" placeholder="<?php esc_html_e( 'Enter User Name of Account', MWA_WP_ZOOM ); ?>">
	              </div> 
	               <div class="col-md-4 form-group">
	                 <label class="vc_lbl" for="usermail"><?php esc_html_e( 'Zoom Account Email', MWA_WP_ZOOM ); ?> *</label>
	                 <input type="email" name="usermail" id="usermail" class="form-control" placeholder="<?php esc_html_e( 'Enter Email of Zoom Account', MWA_WP_ZOOM ); ?>" required="required">
	              </div>
	              <div class="col-md-4 form-group">
	                 <label class="vc_lbl" for="apiid"><?php esc_html_e( 'Zoom Client ID', MWA_WP_ZOOM ); ?> *</label>
	                 <input required="required" type="text" name="apiid" id="apiid" class="form-control" placeholder="<?php esc_html_e( 'Enter Zoom Client ID', MWA_WP_ZOOM ); ?>">
	              </div>
	              <div class="col-md-4 form-group">
	                 <label class="vc_lbl" for="apikey"><?php esc_html_e( 'Zoom Client Secret Key', MWA_WP_ZOOM ); ?> *</label>
	                 <input required="required" type="text" name="apikey" id="apikey" class="form-control" placeholder="<?php esc_html_e( 'Enter Zoom Client Secret Key', MWA_WP_ZOOM ); ?>">
	              </div>                           
	              <div class="col-md-8 form-group">
	                 <label class="vc_lbl" for="redirect_uri"><?php esc_html_e( 'Redirect URL ( Save this redirect url into your zoom app )', MWA_WP_ZOOM ); ?></label>
	                 <input readonly="readonly" type="text" name="redirect_uri_cc" id="redirect_uri_cc" class="form-control" value="<?php echo esc_url($url); ?>">
	                 <input type="hidden" name="redirect_uri" id="redirect_uri" class="form-control" value="<?php echo esc_url($url); ?>">
	                  <input hidden type="text" name="urifortok" id="urifortok" class="form-control" value="<?php echo esc_url($url); ?>">
	              </div>
	              <div id="snackbar_toast"><?php esc_html_e('Copied',MWA_WP_ZOOM); ?> <i class="fa fa-clipboard" style="font-size: 20px;"></i></div>
	                <div class="col-md-1 form-group">
	                  <button data-clipboard-target="#redirect_uri_cc" name="copy_clip_btn" type="button" id="copy_clip_btn" class="btn_vc"><span><?php esc_html_e('Copy',MWA_WP_ZOOM); ?></span></button>
	                </div>  
	                <div class="col-md-1 form-group">  
	                  <button id="save_zoom_auth" name="save_zoom_auth" type="submit" class="btn_vc"><span><?php esc_html_e('Save',MWA_WP_ZOOM); ?></span></button>
	             	</div>
				</div>
			</form>
		</div>
		<div id="tab2">
			<div class="mt-5 table-responsive vc_scrollbar">
				<table id="wp_zoom_toktbl" class="table mt-2">
					<thead class="wp_zoom_tc">
						<th><?php esc_html_e( 'S.N', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Owner', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Email', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Zoom API ID', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Zoom Secret Key', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Token', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Status', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Edit', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Delete', MWA_WP_ZOOM ); ?></th>						
					</thead>
					<tbody>
						<?php
							/* Create Nonce */
							$nonce = wp_create_nonce( 'edit-token-nonce' ); 
							$delnonce = wp_create_nonce( 'del-token-nonce' ); 
							if($rowcount>0){								
								$sn=1;
								foreach ($result_token_mngr as $res) {								
									if(isset($res->usermail)){ 
										$usermail = $res->usermail; 
									}else{
										$usermail = "";
									}
									$dbauthid 		=  $res->id;
									$uac 		 	=  $res->uac;									
							        $client_id   	=  $res->client_id;
							        $client_key  	=  $res->client_secret;
							   		$auth_date   	=  $res->auth_date;
							   		$status      	=  $res->status;
							   		$redirect_uri	=  $res->redirect_uri;
							   		$token_id       =  $res->tok_id;	
							   		
							   		//$tokesc_html_exp_date = 
							    	$expdate=date_create($auth_date);
                                    date_add($expdate,date_interval_create_from_date_string("1 day"));
                                    $exp_date = date_format($expdate,"Y-m-d");
                                    $date_today = date("Y-m-d");
                                    
                                    /*Remove token from db if expiry date is complete*/
                                    if($exp_date==$date_today){
                                    	$qryesc_html_exp_upd = "";
                                    	 $updesc_html_exp = "UPDATE `$table_name_auth` SET `auth_date`='$date_today',`status`='deactive',`tok_id`='0' WHERE `id`='$dbauthid'";
           									 $wpdb->get_results($updesc_html_exp);
           								 $table_name_token = $wpdb->prefix . 'mwa_zoom_token';
           								 $qry_delexp = "DELETE FROM `$table_name_token`";	 
           								 $wpdb->get_results($qry_delexp);
                                    }// if close                                    
							   		?>
							   			<tr>
							   				<td><?php echo esc_html($sn); ?></td>
							   				<td><?php echo esc_html($uac); ?></td>
							   				<td><?php echo $usermail; ?></td>
							   				<td>
							   					<?php 
							   						$cli_id = wordwrap(substr($client_id, 0, 8));
							   						echo esc_html($cli_id.'....'); 
							   					?>
							   				</td>
							   				<td>
							   					<?php 
							   						$cli_key = wordwrap(substr($client_key, 0, 8));
							   						echo esc_html($cli_key.'....');	
							   					?>
							   				</td>
							   				<td><?php
							   						if($status == 'deactive'){ ?>
							   						   <button title="Create Token" data-id="<?php echo esc_attr($dbauthid); ?>" data-client="<?php echo esc_attr($client_id); ?>" data-umail="<?php echo $usermail; ?>" data-secret="<?php echo esc_attr($client_key); ?>" data-reduri="<?php echo esc_url($redirect_uri); ?>" class="btn btn-warning save_tok_btn"><i class="fa fa-plus-circle" style="font-size: 17px;"></i></button>			
							   						<?php
							   						}else{	
							   							 esc_html_e( 'Generated', MWA_WP_ZOOM );
							   						} 
							   					?>								   					
							   				</td>
							   				<td><?php echo ($status == 'deactive') ? '<i class="stat fa fa-times-circle-o fa-2x"></i>' : '<i class="fa fa-check-circle-o fa-2x stat"></i>'; ?></td>	
							   				<td><button id="edittknbtn_<?php echo $dbauthid; ?>" data-id="<?php echo esc_attr($dbauthid); ?>" data-client="<?php echo esc_attr($client_id); ?>" data-secret="<?php echo esc_attr($client_key); ?>" data-uac="<?php echo $uac; ?>" data-usermail="<?php echo $usermail; ?>" data-stat="<?php echo esc_attr($status); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>" class="btn btn-info edit_tok_databtn" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil-square-o"></i></button></td>
							   				<td><button data-delauthnonce="<?php echo esc_attr( $delnonce ); ?>" data-id="<?php echo $dbauthid; ?>" class="btn btn-danger del_tokenbtn"><i class="fa fa-trash-o"></i></button></td>	
							   			</tr>
							   		<?php
							   		$sn++;
							   	} //end foreach loop
							}
						?>	
					</tbody>
				</table>
				<!--Modal for edit data of token generator-->
				<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
				 <?php
				 	$upd_nonce = wp_create_nonce( 'update-token-nonce' );
				 ?>		
				  <div class="modal-dialog modal-lg" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="editModalLabel"><?php esc_html_e( 'Edit Information', MWA_WP_ZOOM ); ?></h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="row modal-body">
				        <div class="col-md-4 form-group">
			             <label class="vc_lbl" for="edit_uac"><?php esc_html_e( 'Account Name', MWA_WP_ZOOM ); ?></label>
			             <input type="text" name="edit_uac" id="edit_uac" class="form-control" placeholder="Enter User Name of Account">
				          </div> 
				          <div class="col-md-4 form-group">
				             <label class="vc_lbl" for="edit_apiid"><?php esc_html_e( 'Zoom Client ID', MWA_WP_ZOOM ); ?> *</label>
				             <input required="required" type="text" name="edit_apiid" id="edit_apiid" class="form-control" placeholder="Enter Zoom Client ID">
				          </div>
				          <div class="col-md-4 form-group">
				             <label class="vc_lbl" for="edit_usermail"><?php esc_html_e( 'Zoom Account Email', MWA_WP_ZOOM ); ?></label>
				             <input type="email" name="edit_usermail" id="edit_usermail" class="form-control" placeholder="Enter Email of Zoom Account">
				          </div>
				          <div class="col-md-4 form-group">
				             <label class="vc_lbl" for="edit_apikey"><?php esc_html_e( 'Zoom Client Secret Key', MWA_WP_ZOOM ); ?> *</label>
				             <input required="required" type="text" name="edit_apikey" id="edit_apikey" class="form-control" placeholder="Enter Zoom Client Secret Key">
				             <input type="hidden" name="edit_authid" id="edit_authid" value="">
				          </div> 
				          <div class="col-md-4 form-group">
				             <label class="vc_lbl" for="edit_apistat"><?php esc_html_e( 'Status', MWA_WP_ZOOM ); ?> *</label>
				             <select class="form-control" id="edit_apistat" name="edit_apistat">
				             	<option value="active"><?php esc_html_e( 'Active', MWA_WP_ZOOM ); ?></option>
				             	<option value="deactive"><?php esc_html_e( 'Deactive', MWA_WP_ZOOM ); ?></option>
				             </select>
				          </div> 					           
				      </div>
				      <div class="modal-footer">					       
				        <button data-updnonce="<?php echo esc_attr( $upd_nonce ); ?>" type="button" data-aid="" class="btn btn-warning edit_updatetok_btn"><?php esc_html_e( 'Update', MWA_WP_ZOOM ); ?></button>
				      </div>
				    </div>
				  </div>
				</div>
			</div>	
		</div>
	</div>	
</div>		
<style type="text/css">
	
	.stat{
		color:#fff;
		margin-left: 15px;
	}

	.container{	
		padding:0;
		margin:10px;
		border-radius:5px;
		box-shadow: 0 2px 3px rgba(0,0,0,.3);
		background: darkslategrey;	
	}
	.tokenmanage_head{
		font-family: 'Roboto';
	}	
</style>