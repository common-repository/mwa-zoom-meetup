<?php
	/**
	 * The plugin page view - the "dashboard" page of the plugin.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();
	global $wpdb;
	$meetingmanage_url = esc_url(get_site_url()."/wp-admin/admin.php?page=manage_meeting");

	$tbl_users = $wpdb->prefix."mwa_zoom_email_users";
	$tbl_host  = $wpdb->prefix."mwa_zoom_host";

	$rowcount_users		= $wpdb->get_var( "SELECT COUNT(*) FROM `$tbl_users`");
	$rowcount_host		= $wpdb->get_var( "SELECT COUNT(*) FROM `$tbl_host`");
	$rowcount_acj		= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `$tbl_host` WHERE `view_type`=%d",0) );
	$rowcount_regiuser	= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `$tbl_host` WHERE `view_type`=%d",1) );
	$rowcount_todmeet	= $wpdb->get_var( "SELECT COUNT(*) FROM `$tbl_host` WHERE DATE(meeting_date) = CURDATE()");
	$rowcount_pastmeet	= $wpdb->get_var( "SELECT COUNT(*) FROM `$tbl_host` WHERE DATE(meeting_date) != CURDATE()");
	?>
	<div class="mwa_meetdashboard">
		<section id="manage_dashboard">
			<div class="container">				
				<div class="row"> 
					<div class="col-md-12 shadow p-3 mb-2 rounded mt-2 mwa_zoom_head">
						<h4 class="dash_head"><?php esc_html_e( 'MWA Zoom Dashboard', MWA_WP_ZOOM ); ?></h4>
					</div>
					<div class="card-deck">
					  <div class="card bg-primary text-white">
					    <div class="card-header">
					    	<i class="fa fa-globe" aria-hidden="true"></i> <?php esc_html_e( 'Total Meetings', MWA_WP_ZOOM ); ?>
					    </div>
					    <div class="card-body">					     
					      <div class="row">	
					      	<div class="col-md-12">
					      		<center><h1 class="text-white"><?php echo esc_html($rowcount_host); ?></h1></center>
					      	</div>
					      	<p class="card-text mt-5"><?php esc_html_e( 'Here we will count the total meetings', MWA_WP_ZOOM ); ?></p>
					  	  </div>					      
					    </div>
					  </div>

					  <div class="card bg-secondary text-white">
					    <div class="card-header">
					    	<i class="fa fa-thumb-tack" aria-hidden="true"></i> <?php esc_html_e( 'Today Meetings', MWA_WP_ZOOM ); ?>
					    </div>
					    <div class="card-body">					     
					      <div class="row">	
					      	<div class="col-md-12">
					      		<center><h1 class="text-white"><?php echo esc_html($rowcount_todmeet); ?></h1></center>
					      	</div>
					      	<p class="card-text mt-5"><?php esc_html_e( 'Here we will count the today meetings', MWA_WP_ZOOM ); ?></p>
					  	  </div>					      
					    </div>
					  </div>

					  <div class="card bg-success text-white">
					    <div class="card-header">
					    	<i class="fa fa-chevron-circle-left" aria-hidden="true"></i> <?php esc_html_e( 'Past Meetings', MWA_WP_ZOOM ); ?></p>
					    </div>
					    <div class="card-body">					     
					      <div class="row">	
					      	<div class="col-md-12">
					      		<center><h1 class="text-white"><?php echo esc_html($rowcount_pastmeet); ?></h1></center>
					      	</div>
					      	<p class="card-text mt-5"><?php esc_html_e( 'Here we will count the past meetings', MWA_WP_ZOOM ); ?></p>
					  	  </div>					      
					    </div>
					  </div>		
					</div>

					<div class="card-deck" style="margin-right: 33px;">
						<div class="card bg-warning text-black">
						    <div class="card-header">
						    	<i class="fa fa-registered" aria-hidden="true"></i> <?php esc_html_e( 'Registered Meetings', MWA_WP_ZOOM ); ?>
						    </div>
						    <div class="card-body">					     
						      <div class="row">	
						      	<div class="col-md-12">
						      		<center><h1 class="text-black"><?php echo esc_html($rowcount_regiuser); ?></h1></center>
						      	</div>
						      	<p class="card-text mt-5"><?php esc_html_e( 'Here we will count the meetings with the settings of view type -  only for registered users', MWA_WP_ZOOM ); ?></p>
						  	  </div>					      
						    </div>
					  	</div>

					  	<div class="card bg-danger text-white">
						    <div class="card-header">
						    	<i class="fa fa-check-circle-o" aria-hidden="true"></i> <?php esc_html_e( 'Anyone can join Meetings', MWA_WP_ZOOM ); ?>
						    </div>
						    <div class="card-body">					     
						      <div class="row">	
						      	<div class="col-md-12">
						      		<center><h1 class="text-white"><?php echo esc_html($rowcount_acj); ?></h1></center>
						      	</div>
						      	<p class="card-text mt-5"><?php esc_html_e( 'Here we will count the meetings with the settings of view type -  all users', MWA_WP_ZOOM ); ?></p>
						  	  </div>					      
						    </div>
					  	</div>

					  	<div class="card bg-info text-white">
						    <div class="card-header">
						    	<i class="fa fa-users" aria-hidden="true"></i> <?php esc_html_e( 'Total Users', MWA_WP_ZOOM ); ?>
						    </div>
						    <div class="card-body">					     
						      <div class="row">	
						      	<div class="col-md-12">
						      		<center><h1 class="text-white"><?php echo esc_html($rowcount_users); ?></h1></center>
						      	</div>
						      	<p class="card-text mt-5"><?php esc_html_e( 'Here we will count users which is saved by admin', MWA_WP_ZOOM ); ?></p>
						  	  </div>					      
						    </div>
					  	</div>	
					</div>
				</div>
			</div>
		</section>
	</div>	
	<style type="text/css">
	.dash_head{
		font-family: Roboto;
	}
</style>