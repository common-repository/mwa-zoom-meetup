<?php
/**
 * The plugin Widget Button - For WP Editor on {page or post} handler page.
 *
 * @package mwa-zoom-meetup
 */
defined( 'ABSPATH' ) or die();

if (! class_exists( 'MWAWPZoomWidgetBtn' )){
	class MWAWPZoomWidgetBtn {	

	/**
	* @return MWAWPZoomWidgetBtn constructor.
	*/	

	public function __construct() {		

		add_action( 'admin_footer', array('MWAWPZoomWidgetBtn','editor_widget_button' ));
		add_action( 'media_buttons', array('MWAWPZoomWidgetBtn','add_media_button' ));
		
	}// end constructor

	public static function add_media_button() {
	    $context	  =  null;
	    $img          =  esc_url(MWA_WP_ZOOM_URL.'assets/images/mwa_wp-zoom.png');
	    $container_id = 'MWAZOOM';
	    $title        = 'Select Meeting to insert into post';
	    $context      .= '<a class="button button-primary thickbox" title="'.$title.'" href="#TB_inline?width400&inlineId=' . $container_id . '">
		<span class="wp-media-buttons-icon" style="width:23px;background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>
		MWA ZOOM</a>';
		printf($context);
	}	

	public static function editor_widget_button(){
		global $wpdb;
		$results   = $wpdb->get_results( "SELECT `id`,`topicname` FROM {$wpdb->prefix}mwa_zoom_host", OBJECT );
		?>
			 <script type="text/javascript">
    				jQuery(document).ready(function () {
		            jQuery('#mwawpzoominsert').on('click', function () {
		                var id = jQuery('#mwa-zoom-select option:selected').val();
		                window.send_to_editor('<p>[mwazoom_showmeeting id=' + id + ']</p>');
		                tb_remove();
		            })
		        });
		    </script>

		     <div id="MWAZOOM" style="display:none;">
		        <h3><?php esc_html_e( "Select Meeting from meeting list & click insert button", MWA_WP_ZOOM ); ?></h3> 
		        	<select id="mwa-zoom-select">
		        		<?php
		        			foreach ($results as $res) {
		        				?>
		        					<option value="<?php echo esc_attr($res->id); ?>"> <?php echo esc_html_e("Topic",MWA_WP_ZOOM); echo " : "; echo esc_html($res->topicname); ?> [mwazoom_showmeeting id=<?php echo esc_html($res->id); ?>]</option>
		        				<?php
		        			}
		        		?>	
		            </select>		          
		            <button class='button button-primary' id='mwawpzoominsert'>
						<?php esc_html_e( "Insert Zoom Meeting Shortcode", MWA_WP_ZOOM ); ?>
					</button>					
					<?php					
				?>
		    </div>
			<?php
		}
	} // end class MWAWPZoomWidgetBtn		
} // end if class exist MWAWPZoomWidgetBtn