<?php
	/**
	 * The plugin shortcode handler page.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();	

	if(!class_exists('MWA_Zoom_Template')){

		class MWA_Zoom_Template{

			public static function enq_css_js(){			
				wp_enqueue_script( 'jquery' ); 
		    	wp_enqueue_script("mwa-zoom-custom-script", MWA_WP_ZOOM_URL.'public/libs/js/mwa-zoom-script.js');
				wp_enqueue_style("mwa-zoom-custom-style", MWA_WP_ZOOM_URL.'public/libs/css/mwa-zoom-style.css');	
		    	wp_enqueue_style( 'bootstrap_css', MWA_WP_ZOOM_URL . 'assets/css/bootstrap.min.css'  );
		    	wp_enqueue_script( 'bootstrap_js', MWA_WP_ZOOM_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), true, true );	
		    	wp_enqueue_style('fontawesome_css', MWA_WP_ZOOM_URL . 'libs/fontawsome/css/font-awesome.min.css');
		    	wp_enqueue_script( 'clipboard-js', MWA_WP_ZOOM_URL . 'assets/js/clipboard.min.js', array( 'jquery' ) );
			}			

			/* Display Meeting Information Shortcode Function */
			public static function show_meeting_shortcode($atts, $content = null, $tag){
				global $wpdb;
			    if($tag == "mwazoom_showmeeting"){
			    	self::enq_css_js();
			        $zoom_id 	= $atts['id'];
			        $table_name = $wpdb->prefix . "mwa_zoom_host";
		        	$result 	= $wpdb->get_row( $wpdb->prepare( "SELECT `meetingid`,`meeting_password`,`meeting_date`,`start_time`,`joinurl`,`topicname`,`view_type` FROM `$table_name` WHERE `id`=%d",$zoom_id ), OBJECT );

		        	$table_meetdata	 = $wpdb->prefix . "mwa_zoom_meetdata";
		        	$res_getmeetdata = $wpdb->get_row( $wpdb->prepare( "SELECT `meetarrdata` FROM `$table_meetdata` WHERE `meeting_id`=%s",$result->meetingid ), OBJECT );		        	

		        	if($result){
		        		$meet_arr = array(
			        		'meet_id'  		 => $result->meetingid,
							'meet_password'  => $result->meeting_password,
							'meet_date' 	 => $result->meeting_date,
							'meet_time' 	 => $result->start_time,
							'meet_join' 	 => $result->joinurl,
							'meet_topic' 	 => $result->topicname,
							'view_type' 	 => $result->view_type,			  
						);
						if($meet_arr['view_type']==0){
							####################################
				        	# view_type = 0 -> Anyone		   #	
				        	# view_type = 1 -> Registered User #
				        	#################################### 
							
						?>
						<div class='container'>							
							<?php
								$imgsrc = MWA_WP_ZOOM_URL."assets/images/mwa_wp-zoom.png";
							?>									
							<div class="col-md-12 alert alert-dark mwazoom_tbl_head">
							  <strong><img class="icon_scode" src='<?php echo esc_url($imgsrc); ?>'/><?php esc_html_e('Zoom Meeting Information',MWA_WP_ZOOM); ?></strong>
							</div>	

							<div class="table-responsive">	
								<table class="table table-dark table-striped table-hover mwazoom_meetinfotbl">
									<thead>
										<tr class="mwazoom_meetinfohead">
											<th><?php esc_html_e('Label',MWA_WP_ZOOM); ?></th>
											<th><?php esc_html_e('Meeting Info',MWA_WP_ZOOM); ?></th>
											<th><?php esc_html_e('Copy',MWA_WP_ZOOM); ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php esc_html_e('Meeting Topic',MWA_WP_ZOOM); ?></td>
											<td><?php echo esc_html($meet_arr['meet_topic']); ?></td>
											<td></td>
										</tr>
										<tr>
											<td><?php esc_html_e('Meeting ID',MWA_WP_ZOOM); ?></td>
											<td id="meet_id"><?php echo esc_html($meet_arr['meet_id']); ?></td>
											<td><button data-clipboard-target="#meet_id" name="meetid_btn" type="button" id="meetid_btn" class="btn btn-warning"><i class="fa fa-clipboard" style="font-size: 17px;"></i></button></td>
										</tr>
										<tr>
											<td><?php esc_html_e('Meeting Password',MWA_WP_ZOOM); ?></td>
											<td id="meet_pass"><?php echo esc_html($meet_arr['meet_password']); ?></td>
											<td><button data-clipboard-target="#meet_pass" name="meetpass_btn" type="button" id="meetpass_btn" class="btn btn-warning"><i class="fa fa-clipboard" style="font-size: 17px;"></i></button></td>
										</tr>	
										<tr>
											<td><?php esc_html_e('Date',MWA_WP_ZOOM); ?></td>
											<td><?php echo esc_html($meet_arr['meet_date']); ?></td>
											<td></td>
										</tr>	
										<tr>
											<td><?php esc_html_e('Time',MWA_WP_ZOOM); ?></td>
											<td><?php echo esc_html($meet_arr['meet_time']); ?></td>
											<td></td>
										</tr>	
										<tr>
											<td><?php esc_html_e('Join URL',MWA_WP_ZOOM); ?></td>
											<td><a class="mwazoom_tagmeet" target="_blank" href="<?php echo esc_url($meet_arr['meet_join']); ?>"><?php esc_html_e('Click here to join the meeting URL',MWA_WP_ZOOM); ?></a>
											</td>
											<td><button data-clipboard-text="<?php echo esc_attr($meet_arr['meet_join']); ?>" name="meetjoin_btn" type="button" id="meetjoin_btn" class="btn btn-warning"><i class="fa fa-clipboard" style="font-size: 17px;"></i></button></td>
										</tr>	
										<tr>
											<td><?php esc_html_e('Join via Web Browser',MWA_WP_ZOOM); ?></td>
											<?php
												$join_via_web = "https://zoom.us/wc/".$meet_arr['meet_id']."/join"
											?>
											<td><a target="_blank" href="<?php echo esc_url($join_via_web); ?>" class="btn btn-info"><?php esc_html_e('JOIN',MWA_WP_ZOOM); ?></a></td>
											<td></td>
										</tr>
										<?php
											if($res_getmeetdata){
												$meetdata_arr  	= unserialize($res_getmeetdata->meetarrdata);
												$meet_docx 		= $meetdata_arr['document'];
												$getmeetid 		= $meet_arr['meet_id'];
												?>
												<tr>
													<td><?php esc_html_e('Document',MWA_WP_ZOOM); ?></td>
													<td><a target="_blank" class="btn btn-warning" href="<?php echo esc_url($meet_docx); ?>" download><?php esc_html_e('Download',MWA_WP_ZOOM); ?> <i class="fa fa-download"></i></a></td>
												</tr>
												<?php
											}else{
												$meet_docx  = "";
											}
										?>							
									</tbody>	
								</table>
								 <div id="snackbar_toastmeet"><?php esc_html_e('Copied',MWA_WP_ZOOM); ?> <i class="far fa-copy" style="font-size: 20px;"></i></div>
							</div>
						</div>
						<?php

					}else{
						?>
							<div class='container'>	
								<?php
									$imgsrc = MWA_WP_ZOOM_URL."assets/images/mwa_wp-zoom.png";
								?>								
								<div class="col-md-12 alert alert-dark mwazoom_tbl_head">
							  		<strong><img class="icon_scode" src='<?php echo esc_url($imgsrc); ?>'/><?php esc_html_e('Zoom Meeting Information',MWA_WP_ZOOM); ?></strong>
								</div>							

								<?php
									if( ! is_user_logged_in() ){	
										$url = wp_login_url( get_permalink());
										?>
										<div class="col-md-12">
											<?php esc_html_e('For join the meeting please',MWA_WP_ZOOM); ?> <a class="btn_regijoin btn btn-warning btn-lg" href="<?php echo $url; ?>"><?php esc_html_e('Register and join',MWA_WP_ZOOM); ?></a>
										</div>
										<?php									 
									}else{
										?>
										<div class="table-responsive">	
											<table class="table table-dark table-striped table-hover mwazoom_meetinfotbl">
												<thead>
													<tr class="mwazoom_meetinfohead">
														<th><?php esc_html_e('Label',MWA_WP_ZOOM); ?></th>
														<th><?php esc_html_e('Meeting',MWA_WP_ZOOM); ?> Info</th>
														<th><?php esc_html_e('Copy',MWA_WP_ZOOM); ?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?php esc_html_e('Meeting Topic',MWA_WP_ZOOM); ?></td>
														<td><?php echo esc_html($meet_arr['meet_topic']); ?></td>
														<td></td>
													</tr>
													<tr>
														<td><?php esc_html_e('Meeting ID',MWA_WP_ZOOM); ?></td>
														<td id="meet_id"><?php echo esc_html($meet_arr['meet_id']); ?></td>
														<td><button data-clipboard-target="#meet_id" name="meetid_btn" type="button" id="meetid_btn" class="btn btn-warning"><i class="fa fa-clipboard" style="font-size: 17px;"></i></button></td>
													</tr>
													<tr>
														<td><?php esc_html_e('Meeting Password',MWA_WP_ZOOM); ?></td>
														<td id="meet_pass"><?php echo esc_html($meet_arr['meet_password']); ?></td>
														<td><button data-clipboard-target="#meet_pass" name="meetpass_btn" type="button" id="meetpass_btn" class="btn btn-warning"><i class="fa fa-clipboard" style="font-size: 17px;"></i></button></td>
													</tr>	
													<tr>
														<td><?php esc_html_e('Date',MWA_WP_ZOOM); ?></td>
														<td><?php echo esc_html($meet_arr['meet_date']); ?></td>
														<td></td>
													</tr>	
													<tr>
														<td><?php esc_html_e('Time',MWA_WP_ZOOM); ?></td>
														<td><?php echo esc_html($meet_arr['meet_time']); ?></td>
														<td></td>
													</tr>	
													<tr>
														<td><?php esc_html_e('Join URL',MWA_WP_ZOOM); ?></td>
														<td><a class="mwazoom_tagmeet" target="_blank" href="<?php echo esc_url($meet_arr['meet_join']); ?>"><?php esc_html_e('Click here to join the meeting',MWA_WP_ZOOM); ?></a>
														</td>
														<td><button data-clipboard-text="<?php echo esc_attr($meet_arr['meet_join']); ?>" name="meetjoin_btn" type="button" id="meetjoin_btn" class="btn btn-warning"><i class="fa fa-clipboard" style="font-size: 17px;"></i></button></td>
													</tr>	
													<tr>
														<td><?php esc_html_e('Join via Web Browser',MWA_WP_ZOOM); ?></td>
														<?php
															$join_via_web = "https://zoom.us/wc/".$meet_arr['meet_id']."/join"
														?>
														<td><a target="_blank" href="<?php echo esc_url($join_via_web); ?>" class="btn btn-info"><?php esc_html_e('JOIN',MWA_WP_ZOOM); ?></a></td>
													</tr>
													<?php
													if($res_getmeetdata){
														$meetdata_arr  	= unserialize($res_getmeetdata->meetarrdata);
														$meet_docx 		= $meetdata_arr['document'];
														$getmeetid 		= $meet_arr['meet_id'];
														?>
														<tr>
															<td><?php esc_html_e('Document',MWA_WP_ZOOM); ?></td>
															<td><a target="_blank" class="btn btn-warning" href="<?php echo esc_url($meet_docx); ?>" download><?php esc_html_e('Download',MWA_WP_ZOOM); ?> <i class="fa fa-download"></i></a></td>
														</tr>
														<?php
													}else{
														$meet_docx  = "";
													}
												?>													
												</tbody>	
											</table>
								 			<div id="snackbar_toastmeet"><?php esc_html_e('Copied',MWA_WP_ZOOM); ?> <i class="far fa-copy" style="font-size: 20px;"></i></div>
										</div>	
									</div>
										<?php
									}// else close - is user logged in
								?>								
							</div>		
						<?php
					} }else { echo "<div class='container'><strong>Sorry, No meeting found in the record.</strong></div>"; } // else close - is view type = 1 -> only registered user show meeting
			    }// if close - check is plugin shortcode start or not
			}// function close - show_meeting_shortcode			
		}// class close - MWA_Zoom_Template
	}// if class exist close - MWA_Zoom_Template
?>