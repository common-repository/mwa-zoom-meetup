<?php
	/**
	 * The plugin page view - the "createmeeting" page of the plugin.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();
	global $wpdb;
	$showmeet_url  = esc_url(get_site_url()."/wp-admin/admin.php?page=manage_meeting");
	$table_ui 	   = $wpdb->prefix . "mwa_zoom_userinfo";	
	$result_ui	   = $wpdb->get_row( $wpdb->prepare( "SELECT `acc_type` FROM `$table_ui` WHERE `authid`=%d", 1 ), OBJECT );
	if(isset($result_ui->acc_type)){ 
		$get_acc_type = $result_ui->acc_type; 
	}else{
		$get_acc_type = "1";
	}	
?>

<div id="loader" style="display: none;">
</div>

<div class="mwa_crtmeet">
		<section id="addmeet">
			<div class="container-fluid">
				<div class="row">
					<div class="col shadow p-3 mb-4 rounded mt-2 mwa_zoom_head">
						<h4 class="addmeet_head"><?php esc_html_e( 'Create Meeting', MWA_WP_ZOOM ); ?></h4>
					</div>
				</div>

				<form id="addmeetingform" method="post" enctype="multipart/form-data">
					<div class="alert alert-success alert-dismissible fade show  mb-5" role="alert">
					  <?php esc_html_e( 'Help', MWA_WP_ZOOM ); ?> ! <?php esc_html_e( 'For Create Meeting, First create zoom api id and secret key?', MWA_WP_ZOOM ); ?> <strong><a target="_blank" href="https://mywebapp.in/how-to-create-zoom-app-id-and-secret-key/"><?php esc_html_e( 'Click Here', MWA_WP_ZOOM ); ?></a></strong>
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>
					<?php wp_nonce_field( 'meeting_form_submit', 'meetingform_generate_nonce' );?>
					<div class="form-row">
						<div class="col-md-4 form-group">
							<label class="vc_lbl" for="topicname"><?php esc_html_e( 'Meeting Topic Name', MWA_WP_ZOOM ); ?> *</label>
							<input placeholder="Topic Name" type="text" id="topicname" name="topicname" class="form-control" required="required">
						</div>	         	
						<div class="col-md-4 form-group">
							<label class="vc_lbl" for="lvdate"><?php esc_html_e( 'Meeting Date', MWA_WP_ZOOM ); ?> *</label>
							<input type="date" id="lvdate" name="lvdate" class="form-control" value="<?php echo esc_attr(date('Y-m-d'));?>" required="required">
						</div>
						<div class="col-md-4 form-group">
							<label class="vc_lbl" for="lvdate"><?php esc_html_e( 'Time', MWA_WP_ZOOM ); ?> * ( <?php esc_html_e( 'Based on 24 Hour Format', MWA_WP_ZOOM ); ?> )</label>             
							<input placeholder="<?php esc_html_e( 'Click here for clock picker', MWA_WP_ZOOM ); ?>" id="lvtime_zoom" name="lvtime_zoom" class="form-control" value="" data-default="10:10" required="required">						
					  </div>

						 <div class="col-md-4 form-group">
		                 	<label class="vc_lbl" for="timezone"><?php esc_html_e( 'Time Zone', MWA_WP_ZOOM ); ?> *</label>	                 
		                 	<select id="timezone" name="timezone" class="form-control" required="required">
		                 		<?php

		                 			$table_tz 	= $wpdb->prefix."mwa_timezone";	                 			
		                 			$result_tz	=  $wpdb->get_results( "SELECT `timezone`,`timezone_val` FROM `$table_tz`", OBJECT );   
		                 			foreach ($result_tz as $sing_tz ) {
		                 				?>
		                 					<option value="<?php echo esc_attr($sing_tz->timezone); ?>"><?php echo esc_html($sing_tz->timezone_val); ?></option>
		                 				<?php
		                 			}
		                 		?>  	                 			                 		
		                 	</select>
		             	 </div>
						 <div class="col-md-4 form-group">	                 	
		                 	<?php 
		                 		if($get_acc_type!=1 && $get_acc_type!=""){
		                 			?>
		                 			<label class="vc_lbl" for="duration"><?php esc_html_e( 'Duration', MWA_WP_ZOOM ); ?></label><br>
		                 			<input type="text" id="duration_pro" data-format="HH:mm" data-template="HH : mm" name="duration_pro" class="form-control pro_dura" required="required">
		                 			<?php
		                 		}else{
		                 	?>                
			                 	<label class="vc_lbl" for="duration"><?php esc_html_e( 'Duration', MWA_WP_ZOOM ); ?> ( <?php esc_html_e( 'Max Time = 40 Minute', MWA_WP_ZOOM ); ?> )</label>
			                 	<select id="duration" name="duration" class="form-control">
			                 		<?php
			                 			for($min_var=1;$min_var<=40;$min_var++){
			                 		?>
			                 			<option <?php if($min_var==30){ echo esc_attr('selected="selected"'); } ?> value="<?php echo esc_attr($min_var); ?>"><?php if($min_var<=9 ){ echo "0".esc_html($min_var); }else { echo esc_html($min_var); }  ?> - <?php if($min_var==1 ){ esc_html_e( 'Minute', MWA_WP_ZOOM ); }else { esc_html_e( 'Minutes', MWA_WP_ZOOM ); }  ?></option>
			                 		<?php } ?>	
			                 	</select>
			                 <?php } ?>
		             	 </div>
		             	  <div class="col-md-4 form-group">
		                 	<label class="vc_lbl" for="meetpassword"><?php esc_html_e( 'Meeting Password', MWA_WP_ZOOM ); ?> * ( <?php esc_html_e( 'Max. Length = 10', MWA_WP_ZOOM ); ?> )</label>
		                 	<input placeholder="Enter Password allowed , [a-z A-Z 0-9 @ - _ *]" type="text" maxlength="10" id="meetpassword" name="meetpassword" class="form-control" required="required">
		             	 </div>
		             	 <div class="col-md-4 form-group">
							<label class="vc_lbl" for="desc"><?php esc_html_e( 'Meeting Description', MWA_WP_ZOOM ); ?></label>						
							<textarea rows="3" id="desc" name="desc" class="form-control" placeholder="Enter Description"></textarea>
						</div>
						 <div class="col-md-4 form-group">
							<label class="vc_lbl" for="mwa_meet_file"><?php esc_html_e( 'Upload Document', MWA_WP_ZOOM ); ?></label>						
							<input for="mwa_meet_file" type='file' name="mwa_meet_file" id="mwa_meet_file">
							<p id="para_field"><?php esc_html_e( 'The uploaded document will be seen only on the meeting information page.', MWA_WP_ZOOM ); ?></p>
						</div>	
						 <div class="col-md-4 form-group">	
		             	 	<label class="vc_lbl"><?php esc_html_e( 'Meeting Join Before Host', MWA_WP_ZOOM ); ?></label></br>     
		             	 	<label class='switch vc_lbl'><input name="jbh" id="jbh" type='checkbox' class='jbh_state' value='true'><span class='slider round slide_dis'></span></label>
		             	 	<p id="para_field"><?php esc_html_e( 'Allow participants to join the meeting before the host starts the meeting', MWA_WP_ZOOM ); ?></p>
		             	 </div>
		             	  <div class="col-md-4 form-group">
		             	 	<label class="vc_lbl"><?php esc_html_e( 'Join Before Host Time', MWA_WP_ZOOM ); ?></label>
		             	 	<select class="form-control" id="jbh_time" name="jbh_time">
		             	 		<option value="0">0 <?php esc_html_e( 'Minute', MWA_WP_ZOOM ); ?></option>
		             	 		<option value="5">5 <?php esc_html_e( 'Minute', MWA_WP_ZOOM ); ?></option>
		             	 		<option value="10">10 <?php esc_html_e( 'Minute', MWA_WP_ZOOM ); ?></option>
		             	 	</select>
		             	 	<p id="para_field"><?php esc_html_e( 'If Join Before Host setting off, Then leave this setting', MWA_WP_ZOOM ); ?></p>
		             	 </div>
		             	  <div class="col-md-4 form-group">	
		             	 	<label class="vc_lbl"><?php esc_html_e( 'Mute Upon Entry', MWA_WP_ZOOM ); ?></label></br>     
		             	 	<label class='switch vc_lbl'><input name="mute_entry" id="mute_entry" type='checkbox' class='jbh_state' value='true'><span class='slider round slide_dis'></span></label>
		             	 	<p id="para_field"><?php esc_html_e( 'Mute participants upon entry', MWA_WP_ZOOM ); ?></p>
		             	 </div> 
		             	 <div class="col-md-4 form-group">
		             	 	<button type="submit" style="margin-top: 30px;" class="btn btn-warning rounded-pill" id="create_meeting" name="create_meeting"><?php esc_html_e( 'Create Meeting', MWA_WP_ZOOM ); ?> <i class="fas fa-video"></i></button>
		             	 	<a href="<?php echo $showmeet_url; ?>" class="btn btn-success show_allmeet rounded-pill"><?php esc_html_e( 'Show All Meetings', MWA_WP_ZOOM ); ?></a>
		             	 </div>	
		             </div> 
		        </form>
		    </div>
		</section>
	</div>
<style type="text/css">
	select.minute, select.hour {
	   width: 173px !important;
	}
	#para_field{
		color: #86ded7;
		margin-top: 5px;
	}
	form#addmeetingform {
		background: #2f4f4f;
		padding: 10px;
	}
	.addmeet_head{
		font-family: 'Roboto';	
	}	
</style>	         	      
<script type="text/javascript">
	<?php
	    if($get_acc_type!=1 && $get_acc_type!=""){
	    	?>
	    	jQuery(document).ready(function(){
				jQuery('#duration_pro').combodate({
			        firstItem: 'name', //show 'hour' and 'minute' string at first item of dropdown
			        minuteStep: 1
			    });

			    jQuery('.hour').find('option').get(0).remove();
			    jQuery('.hour').first().prepend(`<option selected="selected" value="00">hour</option>`);

			    jQuery('.minute').find('option').get(0).remove();
			    jQuery('.minute').first().prepend(`<option selected="selected" value="00">minute</option>`); 
			});	
	    	<?php
	    }
	?>	
</script>	         	      