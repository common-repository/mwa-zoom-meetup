<?php
	/**
	 * The plugin page view - the "Google Calender Event Manger" page of the plugin.
	 *
	 * @package mwa-zoom-meetup
	 */

	defined('ABSPATH') or die();	

?>
<div class="mwa_eventnmngr">
	<section id="eventnmngr">
		<div class="container">
			<div class="row">
				<div class="col shadow p-3 mb-2 rounded mt-2 mwa_zoom_head">
					<h4 class="allevents"><?php esc_html_e( 'All Events', MWA_WP_ZOOM ); ?>&nbsp;<sub><kbd style="color:Yellow;"><?php esc_html_e( 'Google Calender', MWA_WP_ZOOM ); ?></kbd></sub></h4>
				</div>			
			</div>
			<a href="<?php echo esc_attr( 'https://mywebapp.in/zoom-meetings/' ); ?>" target="_blank">
				<img src="<?php echo esc_url( MWA_WP_ZOOM_URL."/assets/images/gcalist.png" ); ?>" alt="Upgrade To Pro">
			</a>	
		 	<a href="<?php echo esc_attr('https://mywebapp.in/zoom-meetings/'); ?>" target="_blank" class="btn"><span class="blink"><?php esc_html_e( 'Upgrade To Pro', MWA_WP_ZOOM ); ?></span></a>
		</div>
	</section>

</div>			

<style type="text/css">
	/* Make the image responsive */
.container img {
  width: 100%;
  height: auto;
}

/* Style the button and place it in the middle of the container/image */
.container .btn {
  position: absolute;
  top: 76%;
  left: 20%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  background-color: #ffbc00;
  color: #000;
  font-size: 16px;
  font-weight: 700;
  padding: 12px 24px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

.container .btn:hover {
  background-color: black;
  color:#fff;
}
@-webkit-keyframes blinker {
  from {opacity: 1.0;}
  to {opacity: 0.0;}
}
.blink{
	text-decoration: blink;
	-webkit-animation-name: blinker;
	-webkit-animation-duration: 0.9s;
	-webkit-animation-iteration-count:infinite;
	-webkit-animation-timing-function:ease-in-out;
	-webkit-animation-direction: alternate;
}
.allevents{
	font-family: Roboto;
}
</style>