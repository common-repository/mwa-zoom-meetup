<?php
	/**
	 * The plugin page view - the Tutorial of create meeting" page of the plugin.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();	

?>
<div class="mwa_tutmngr">
	<section id="tutnmngr">
		<div class="container">
			<div class="row">
				<div class="col shadow p-3 mb-2 rounded mt-2 mwa_zoom_head">
					<h4><?php esc_html_e( 'Tutorial', MWA_WP_ZOOM ); ?></h4>
				</div>				
			</div>
			<div class="row divtut">
				<div class="col-md-6">
					</br>
					<h4 class="tut_head">Video Conferencing – Zoom Meetings</h4>
					</br>
					Today’s workforce prioritizes mobility, flexibility and modern forms of communication over private offices, corporates, organizations, startups and seclusion. Video conferencing is a choice and need of today and future. Zoom is the leader of modern video communications with a reliable cloud platform for audio and video conferencing. MyWebApp Team develops a plugin which makes you capable to use all Zoom Video Conference functions and features from your WordPress Dashboard.
				</div>
				<div class="col-md-6">
					<iframe class="framevc" width="560" height="315" src="https://www.youtube.com/embed/MmIDFLR5Jv4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</section>
</div>			
<style type="text/css">
	.tut_head{
		color: #2f4f4f;
	}

	.framevc{		
		border: 5px solid #2f4f4f;
	}
</style>