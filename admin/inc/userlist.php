<?php
	/**
	 * The plugin page view - the "user-list" page of the plugin.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();	
	global $wpdb;
	$status1 = "1";
	$status2 = "0";
	$remark  = "";
	$user_email_listtbl = $wpdb->prefix . "mwa_zoom_email_users";
	$sql 		= $wpdb->prepare( "SELECT `id`,`username`,`email`,`status` FROM `$user_email_listtbl` WHERE `status`=%s OR `status`=%s",$status1,$status2);
	$results 	= $wpdb->get_results( $sql,OBJECT );	
?>
<div class="mwa_userlist">
	<section id="addzoomuser">
		<div class="container">
			<div class="row">
				<div class="col shadow p-3 mb-2 rounded mt-2 mwa_zoom_head">
					<h4 class="aul_head"><?php esc_html_e( 'Add User & List', MWA_WP_ZOOM ); ?>&nbsp;<sub><kbd class="kbdcls"><?php esc_html_e( 'Manage Yourself', MWA_WP_ZOOM ); ?></kbd></sub></h4>
				</div>
			</div>
		</div>	
	</section>	

	<section id="addzoomuserform" class="container">
		<div class="tblform">
			<form id="user_add_form" method="post">
				<?php wp_nonce_field( 'adduser_form_submit', 'adduserform_generate_nonce' );?>
				<div class="form-row">
					<div class="col-md-4 form-group">
						<label class="vc_lbl" for="username"><?php esc_html_e( 'User Name', MWA_WP_ZOOM ); ?></label>
						<input placeholder="Add User Name" type="text" id="username" name="username" class="form-control">
					</div>	         	
					<div class="col-md-4 form-group">
						<label class="vc_lbl" for="usermail"><?php esc_html_e( 'User Email', MWA_WP_ZOOM ); ?></label>
						<input type="email" id="usermail" name="usermail" class="form-control" value=""  placeholder="Enter Email">
					</div>
					<div class="col-md-4 form-group">
	             	 	<button type="submit" style="margin-top: 30px;" class="btn btn-primary" id="save_user" name="save_user"><?php esc_html_e( 'Save', MWA_WP_ZOOM ); ?> <i class="fa fa-floppy-o"></i></button>
	             	 </div>	
				</div>
			</form>

			<table id="wp_zoom_useremail_list" class="table vc_scrollbar">
				<thead class="wp_zoom_tc">
					<tr class="tc">					
						<th><input type="checkbox" name="chkuserAL" id="chkuserAL" class="form-control chkuserAL"></th>
						<th><?php esc_html_e( 'User Name', MWA_WP_ZOOM ); ?></th>					
						<th><?php esc_html_e( 'Email', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Status', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Edit', MWA_WP_ZOOM ); ?></th>
						<th><?php esc_html_e( 'Delete', MWA_WP_ZOOM ); ?></th>					
					</tr>
				</thead>
				<tbody>
					<?php
						$nonce 			= wp_create_nonce( 'deluser-token-nonce' );
						$edituser_nonce = wp_create_nonce( 'edituser-email-nonce' ); 					
						
						foreach ($results as $res) { 						
							?>					
							<tr>
								<td><input type="checkbox" name="chk_user[]" value="<?php echo esc_attr($res->id); ?>" id="chk_user" class="form-control chk_user"></th></td>								
								<td><span id="field_span_user_<?php echo esc_attr($res->id); ?>"><?php echo esc_html($res->username); ?></span><input class="field_user form-control" id="field_user_<?php echo esc_attr($res->id); ?>" type="text" name="" value="<?php echo esc_attr($res->username); ?>"></td>

								<td><span id="field_span_email_<?php echo esc_attr($res->id); ?>"><?php echo esc_html($res->email); ?></span>
								<input class="field_user form-control" id="field_email_<?php echo esc_attr($res->id); ?>" type="text" name="" value="<?php echo esc_attr($res->email); ?>"></td>

								<td>								
									<?php 
										if($res->status==1){
											?>
												<i id="stat_user_<?php echo esc_attr($res->id); ?>" class="fa fa-check-circle-o fa-2x stat"></i>
											<?php
											
										}else{
											?>
												<i id="stat_user_<?php echo esc_attr($res->id); ?>"  class="fa fa-times-circle-o fa-2x stat"></i>
											<?php
										}// else close
									 ?>	
									 <select id="stat_edituser_<?php echo esc_attr($res->id); ?>" class="stat_edituser form-control">
									 	<option <?php if($res->status=="1"){ echo esc_html("selected=selected"); } ?> value="<?php echo esc_attr("1"); ?>"><?php esc_html_e( 'Active', MWA_WP_ZOOM ); ?></option>
									 	<option <?php if($res->status=="0"){ echo esc_html("selected=selected"); } ?> value="<?php echo esc_attr("0"); ?>"><?php esc_html_e( 'Deactive', MWA_WP_ZOOM ); ?></option>
									 </select>							
								</td>
								
								<td>
									<button id="edit_userbtn_<?php echo esc_attr($res->id); ?>"  type="button" data-edituid="<?php echo esc_attr($res->id); ?>" class="btn btn-primary edit_userbtn"><i class="fa fa-edit"></i></button>
									<button data-editusernonce="<?php echo esc_attr($edituser_nonce); ?>" type="button" id="save_userbtn_<?php echo esc_attr($res->id); ?>" data-saveuid="<?php echo esc_attr($res->id); ?>" class="btn btn-success save_userbtn"><i class="fa fa-floppy-o"></i></button>
									<button type="button" id="cancel_userbtn_<?php echo esc_attr($res->id); ?>" data-canuid="<?php echo esc_attr($res->id); ?>" class="btn btn-warning cancel_userbtn"><i class="fa fa-times"></i></button>
								</td>	
								<td><button type="button" data-delnonce="<?php echo esc_attr( $nonce ); ?>" data-delid="<?php echo esc_attr($res->id); ?>" class="btn btn-danger del_userbtn"><i class="fa fa-trash"></i></button></td>
							</tr>
					<?php } // end foreach loop ?>
				</tbody>
			</table>
			<button id="del_all_user" data-noncedel="<?php echo esc_attr( $nonce ); ?>" class="btn btn-danger mt-2"><?php esc_html_e( 'Delete All', MWA_WP_ZOOM ); ?></i></button>
		</div>
	</section>		
</div>	
<style type="text/css">	
	.stat{
		color:#fff;
		margin-left: 15px;
	}
	.kbdcls{
		color:Yellow;
	}
	.mt_fld{
		margin-top: 30px;
	}
	.tblform{
		background: #2f4f4f;
		padding: 10px;
	}
	div#wp_zoom_useremail_list_wrapper{
		margin-top: 30px;
	}	
	.aul_head{
		font-family: Roboto;
	}
</style>