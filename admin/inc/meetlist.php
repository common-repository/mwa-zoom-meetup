<?php
	/**
	 * The plugin page view - the "meeting-list" page of the plugin.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();	
		
	global $wpdb;
	$table_meetdata	= $wpdb->prefix . "mwa_zoom_meetdata";
	$results   		= $wpdb->get_results( "SELECT `meetingid`,`meeting_password`,`topicname`,`duration`,`meeting_date`,`start_time`,`id`,`view_type`,`joinurl` FROM {$wpdb->prefix}mwa_zoom_host", OBJECT );	
	$resultesc_html_em = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mwa_zoom_email_users", OBJECT );	
?>
<div class="mwa_meetlist">
	<section id="manage_meet">
		<div class="container">
			<div class="row">
				<div class="col shadow p-3 mb-5 rounded mt-2 mwa_zoom_head">
					<h4 class="managemeet_head"><?php esc_html_e( 'Manage Meeting', MWA_WP_ZOOM ); ?></h4>
				</div>
			</div>
		</div>
	</section>

	<section id="manage_meetlist" class="table-responsive vc_scrollbar">
		<table id="wp_zoom_meetlist" class="table">
			<thead class="wp_zoom_tc">
				<tr class="tc">					
					<th>#</th>					
					<th><?php esc_html_e( 'Meeting ID', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'Password', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'Topic', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'Duration', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'DateTime', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'Shortcode', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'View Type', MWA_WP_ZOOM ); ?></th>					
					<th><?php esc_html_e( 'Mail', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'Host', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'Join', MWA_WP_ZOOM ); ?></th>
					<th><?php esc_html_e( 'File', MWA_WP_ZOOM ); ?></th>	
					<th><?php esc_html_e( 'Delete', MWA_WP_ZOOM ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$mailnonce 			= wp_create_nonce( 'openmail-nonce' );
					$deletemeetnonce 	= wp_create_nonce( 'delmeet-nonce' );
					$mvtnonce 			= wp_create_nonce( 'mvtnonce-nonce' );					
					$sn=1;
					foreach ($results as $res) { 	
						$dbmeetingid = $res->meetingid;
						$hostmeeturl = "https://zoom.us/s/".$res->meetingid;
						$res_getmeetdata = $wpdb->get_row( $wpdb->prepare( "SELECT `meetarrdata` FROM `$table_meetdata` WHERE `meeting_id`=%s",$dbmeetingid ), OBJECT );	
						?>					
						<tr>
							<td><?php echo esc_html($sn); ?></td>							
							<td><?php echo esc_html($dbmeetingid); ?></td>
							<td><?php echo esc_html($res->meeting_password); ?></td>
							<td><?php echo esc_html($res->topicname); ?></td>
							<td><?php echo esc_html($res->duration); ?> Min.</td>
							<td><?php echo esc_html($res->meeting_date."\n".$res->start_time); ?></td>
							<td><?php echo esc_html("[mwazoom_showmeeting id=".$res->id."]"); ?></td>
							<td>
								<select id="mvtid_<?php echo esc_attr($res->id); ?>" data-mvtid="<?php echo esc_attr($res->id); ?>" data-mvtnonce="<?php echo esc_attr($mvtnonce); ?>" name="meetview_type" class="form-control meetview_type">
									<option><?php echo esc_html("Select View"); ?></option>
									<option <?php if($res->view_type==0){ echo "style='background-color:grey;color:#fff;'"; } ?> <?php if($res->view_type==0){ echo esc_html("selected=selected"); } ?> value="<?php echo esc_attr("0"); ?>"><?php esc_html_e( 'Anyone can join', MWA_WP_ZOOM ); ?></option>
									<option <?php if($res->view_type==1){ echo "style='background-color:grey;color:#fff;'"; } ?> <?php if($res->view_type==1){ echo esc_html("selected=selected"); } ?> value="<?php echo esc_attr("1"); ?>"><?php esc_html_e( 'Registered Users', MWA_WP_ZOOM ); ?></option>
								</select>
							</td>							
							<td><button data-openmailnonce="<?php echo esc_attr( $mailnonce ); ?>" data-meetid="<?php echo esc_attr($res->id); ?>" id="open_mail_btn" class="btn btn-success open_mail_btn" data-toggle="modal" data-target="#email_modal"><i class="fa fa-envelope-o"></i></button></td>
							<td><a href="<?php echo esc_url($hostmeeturl); ?>" target="_blank" class="btn btn-primary"><i class="fa fa-user"></i></a></td>
							<td><a href="<?php echo esc_url($res->joinurl); ?>" target="_blank" class="btn btn-info"><i class="fa fa-video-camera"></i></a></td>
							<td>
								<?php
									if($res_getmeetdata){
										$meetdata_arr  	= unserialize($res_getmeetdata->meetarrdata);
										$meet_docx 		= $meetdata_arr['document'];
										?>
										<a target="_blank" class="btn btn-warning" href="<?php echo esc_url($meet_docx); ?>" download><i class="fa fa-download"></i></a>
										<?php
									}else{
										?>
										<a target="_blank" class="btn btn-danger" href="#"><i class="fa fa-times"></i></a>
										<?php
									}
								?>								
							</td>
							<td><button data-delmeetnonce="<?php echo esc_attr($deletemeetnonce); ?>" data-did="<?php echo esc_attr($res->id); ?>" class="btn btn-danger del_meet"><i class="fa fa-trash"></i></button></td>
						</tr>
				<?php $sn++; }// foreach loop close ?>
			</tbody>
		</table>
	</section>
</div>	
	
<!--Modal for get users-->
<div class="modal fade" id="email_modal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
 <?php
 	$email_nonce = wp_create_nonce( 'send-useremail-nonce' );
 ?>		
  <div class="modal-dialog modal-lg" role="dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel"><?php esc_html_e( 'All Users', MWA_WP_ZOOM ); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="container">
		<div class="row modal-body table-responsive">
			<div class="form-row">
				<div class="col-md-12 form-group">
					<label class="vc_lbl" for="topicname"><?php esc_html_e( 'Email Body', MWA_WP_ZOOM ); ?></label>
					<div class="jumbotron" id="mailbody_txt"></div>
				</div>	

				<div class="col-md-12 form-group">
					<table id="wp_zoom_useremail_list" class="table">
						<thead class="wp_zoom_tc">
							<tr class="tc">					
								<th><input type="checkbox" name="chkuserMAL" id="chkuserMAL" class="form-control chkuserMAL"></th>					
								<th><?php esc_html_e( 'User Name', MWA_WP_ZOOM ); ?></th>
								<th><?php esc_html_e( 'Email', MWA_WP_ZOOM ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$sn=1;
								foreach ($resultesc_html_em as $resem) {
									$block =  $resem->status;
									if($block!=0){ ?>					
										<tr>
											<td><input type="checkbox" name="chk_usermail[]" value="<?php echo esc_attr($resem->id); ?>" id="chk_usermail" class="form-control chk_usermail"></td>	
											<td><?php echo esc_html($resem->username); ?></td>
											<td><?php echo esc_html($resem->email); ?></td>
										</tr>
										<?php
											} else { ?>
											<tr class="disb_user"> 
												<td><i style="color:red;font-size: 20px;" class="fa fa-times-circle-o fa-2x"></i></td>
												<td><?php echo esc_html($resem->username); ?></td>
												<td><?php echo esc_html($resem->email); ?></td>
											</tr>
											<?php
										} // else close
										?>	
							<?php $sn++; } // foreach loop close ?>
						</tbody>
					</table>
				</div>       			            					           
      		</div>      	
      	</div>
      <div class="modal-footer">					       
        <button data-emailnonce="<?php echo esc_attr( $email_nonce ); ?>" type="button" data-aid="" class="btn btn-warning send_mail_btn rounded-pill"><?php esc_html_e('Send Mail',MWA_WP_ZOOM); ?> <i class="fa fa-envelope-o" style="font-size: 15px;"></i></button>
        <button id="loader_btn" type="button" class="btn btn-primary">
        	<span class="spinner-border spinner-border-sm"></span>
  			<?php esc_html_e( 'Sending', MWA_WP_ZOOM ); ?>
        </button>
        <div id="snackbar_toast_mail"><?php esc_html_e('Successfully Sent',MWA_WP_ZOOM); ?>&nbsp;<i class="fa fa-envelope-o" style="font-size: 15px;"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
section#manage_meetlist {
	 background: darkslategray;
	 padding: 10px;
}
p.meetlist{
	color: #cadcd2;
}
.managemeet_head{
	font-family: 'Roboto';	
}
</style>