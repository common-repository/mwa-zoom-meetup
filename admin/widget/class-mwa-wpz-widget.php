<?php
/**
 * The plugin Widget handler page.
 *
 * @package mwa-zoom-meetup
 */
defined( 'ABSPATH' ) or die();

if (! class_exists( 'MWAWPZoomWidget' )){
	class MWAWPZoomWidget extends WP_Widget {	

	/**
	* @return MWAWPZoomWidget constructor.
	*/	

	public function __construct() {		
		parent::__construct(
			'mwa_wp_zoom_widget',// Base ID
			__( 'Video Conferencing - Zoom Meetings', 'MWA_WP_ZOOM' ),// Name
			array('description' => esc_html('Display Your Zoom Meeting','MWA_WP_ZOOM')),// Args
			array(
				'customize_selective_refresh' => true,
			)
		);
	}// end constructor

	/*The widget form (for the backend )*/
	public function form( $instance ) {
		global $wpdb;
		$createmeet  = esc_url(get_site_url()."/wp-admin/admin.php?page=create_meeting");
		$tbl_name = $wpdb->prefix . "mwa_zoom_host";
		$results  = $wpdb->get_results( "SELECT `id`,`topicname` FROM `$tbl_name`", OBJECT );
		$rowcount = $wpdb->get_var( "SELECT COUNT(*) FROM `$tbl_name`");

		/*Set widget defaults*/
		$defaults = array(
			'title'    			=> '',
			'checkbox' 			=> '',
			'checkbox_title' 	=> '',
			'select'   			=> '',
		);
		
		/*Parse current settings with defaults*/
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

		<?php 
			if($rowcount>0){
			/*Meeting Title*/
			$imgsrc = MWA_WP_ZOOM_URL."assets/images/mwa_wp-zoom.png";
		?>
		<p>
			<img style="float: right;margin-bottom: 4px;" class="icon_scode" src='<?php echo esc_url($imgsrc); ?>'/>
		</p>
		<p>				
			<label for="Meeting Title"><?php _e( 'Meeting Title', 'MWA_WP_ZOOM' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>	

		<?php /*Meeting Title Show/Hide*/ ?>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'checkbox_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'checkbox_title' ) ); ?>" type="checkbox" value="<?php echo esc_attr("1"); ?>" <?php checked( '1', $checkbox_title ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'checkbox_title' ) ); ?>"><?php _e( 'Meeting Title : Show/Hide', 'MWA_WP_ZOOM' ); ?></label>
		</p>	

		<?php /*Meeting List*/ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'select' ); ?>"><?php _e( 'Meeting List', 'MWA_WP_ZOOM' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'select' ); ?>" id="<?php echo $this->get_field_id( 'select' ); ?>" class="widefat">
			<?php	
			$meet_url  = esc_url(get_site_url()."/wp-admin/admin.php?page=create_meeting");
			foreach ($results as $res) {
				$scode = "[mwazoom_showmeeting id=".$res->id."]";
				?>
					<option <?php if($select==$res->id){ echo esc_html("selected=selected"); } ?> id="<?php echo esc_attr($res->id); ?>" value="<?php echo esc_attr($res->id); ?>"><?php echo esc_html("Topic : ".$res->topicname." ".$scode); ?></option>
				<?php
			}
			?>				
			</select>
		</p>

		<?php /*Meeting Info Show/Hide*/ ?>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'checkbox' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'checkbox' ) ); ?>" type="checkbox" value="<?php echo esc_attr("1"); ?>" <?php checked( '1', $checkbox ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'checkbox' ) ); ?>"><?php _e( 'Meeting Info : Show/Hide', 'MWA_WP_ZOOM' ); ?></label>
		</p>
		<?php
		}else{
			?>
			<p>
				<?php _e( 'No Meeting Found, Please', 'MWA_WP_ZOOM' ); ?> <a href="<?php echo $createmeet; ?>"><?php _e( 'Create First', 'MWA_WP_ZOOM' ); ?></a>
			</p>
			<?php
		}
	 }

	/*Update widget settings*/
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';		
		$instance['checkbox'] = isset( $new_instance['checkbox'] ) ? 1 : false;
		$instance['checkbox_title'] = isset( $new_instance['checkbox_title'] ) ? 1 : false;
		$instance['select']   = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';
		return $instance;
	}

	/*Display the widget*/
	public function widget( $args, $instance ) {

		extract( $args );

		/*Check the widget options*/
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';		
		$select   = isset( $instance['select'] ) ? $instance['select'] : '';
		$checkbox = ! empty( $instance['checkbox'] ) ? $instance['checkbox'] : false;
		$checkbox_title = ! empty( $instance['checkbox_title'] ) ? $instance['checkbox_title'] : false;	
		
		echo $before_widget;

		/*Display the widget*/
		echo '<div class="widget-text mwa_wp_zoom_widget">';

			/*Display widget title if defined*/
			if ( $title && $checkbox_title ) {				
				echo $before_title;
				echo "<h2 style='margin-bottom:10px;'>$title</h2>";
				echo $after_title;

			}	

			/*Display something if checkbox is true*/
			if ( $checkbox ) {				
				do_shortcode( "[mwazoom_showmeeting id=$select]" );
			}

		echo '</div>';		
		echo $after_widget;
	}
} // end class MWAWPZoomWidget

	/*Register the MWA Zoom widget*/
	function mwa_wp_zoom_register_widget() {
		register_widget( 'MWAWPZoomWidget' );
	}
	add_action( 'widgets_init', 'mwa_wp_zoom_register_widget' );
} // end if exist class MWAWPZoomWidget