<?php
	/**
	 * The plugin page view - the "upgrade to pro" page of the plugin.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();
		
?>
<div class="mwa_userlist">
	<section id="addmeet">
		<div class="container">
			<div class="row">
				<div class="col shadow p-3 mb-2 rounded mt-2 mwa_zoom_head">
					<h4 class="upgprohead"><?php esc_html_e( 'Upgrade To Pro', MWA_WP_ZOOM ); ?></h4>
				</div>				
			<div class="row">
				<div class="col-md-6">
					<p>	
						<h3 class="text-danger mt-2 mb-3"><?php esc_html_e( 'All Premium Features', MWA_WP_ZOOM ); ?></h3>
						<ul>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Compatible with WordPress', MWA_WP_ZOOM ); ?></li>		
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Provides integration of Zoom on WordPress', MWA_WP_ZOOM ); ?></li>	
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Compatible with Zoom API', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Has admins area to manage the meetings', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Display your Zoom meeting & link them on WordPress posts & page using shortcode', MWA_WP_ZOOM ); ?></li>	
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Can create schedule meeting & recurring meeting', MWA_WP_ZOOM ); ?></li>	
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Export meeting to Google Calendar, Outlook & Yahoo', MWA_WP_ZOOM ); ?></li>	
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Display Zoom meeting with calendar view for admin', MWA_WP_ZOOM ); ?></li>	
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Display Zoom meeting events with calendar view for admin', MWA_WP_ZOOM ); ?></li>		
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Display your Zoom meeting using widget', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Display upcoming meeting via countdown timer', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Can save recording of meeting', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'JOIN DIRECTLY VIA WEB BROWSER FROM FRONTEND', MWA_WP_ZOOM ); ?> !</li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'JOIN meeting on your site via Meetarea feature', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Add dynamic page/post url for specific meeting when send mail of meeting details to selected users', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Add multiple token for meetings For create meeting at same time', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Meeting is protected with end to end encryption setting', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Multiple google account can manage for add meeting to google calendar', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Add meeting to google calendar event', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Add and manage users for sending mail to send meeting url', MWA_WP_ZOOM ); ?></li>
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Start Meeting Recording as local or cloud with recording setting', MWA_WP_ZOOM ); ?></li>	
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Add & manage webinar coming soon', MWA_WP_ZOOM ); ?></li>	
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Can view all recorded meeting list at your local system', MWA_WP_ZOOM ); ?></li>	
							<li><i class="fa fa-check-square-o thumb_zoom" aria-hidden="true"></i> <?php esc_html_e( 'Admin has full control for check the status of live meeting, past meeting, total meeting, registered meetings, purchased meeting & all users using Dashboard MENU', MWA_WP_ZOOM ); ?></li>
						</ul>
					</p>
				</div>				
				<div class="col-md-6">
					<img class="img-responsive" width="100%" src="<?php echo MWA_WP_ZOOM_URL.'assets/images/zoom_meeting.png'; ?>"/>					
					<div class="row mt-3">
						<div class="col-md-3" style="display: contents">
							&nbsp;&nbsp;&nbsp;<a class="btn btn-success rounded-pill" target="_blank" href="https://mywebapp.in/zoom-meetings/"><?php esc_html_e( 'Go Pro', MWA_WP_ZOOM ); ?></a>&nbsp;
							<a class="btn btn-danger rounded-pill" target="_blank" href="https://www.youtube.com/watch?v=MmIDFLR5Jv4"><?php esc_html_e( 'Demo', MWA_WP_ZOOM ); ?></a>&nbsp;
							<a class="btn btn-warning rounded-pill" target="_blank" href="https://mywebapp.in/how-to-create-zoom-app-id-and-secret-key/"><?php esc_html_e( 'Documentation', MWA_WP_ZOOM ); ?></a>
						</div>
					</div>					
				</div>				
			</div>
			</div>		
		</div>
	</section>				
</div>	