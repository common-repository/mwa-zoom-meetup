/**
 * Public Custom Jquery Scripts
 * @package mwa-zoom-meetup	
 * @author  MyWebApp
 * @since  1.0
 */

jQuery(document).ready( function () {

	//jQuery('#wp_zoominfo_meetlist').DataTable();

	var clipboard = new ClipboardJS('#meetid_btn,#meetpass_btn,#meetjoin_btn');


	clipboard.on('success', function(e) {
		show_toast();
	    e.clearSelection();
	});

	clipboard.on('error', function(e) {
	    console.error('Action:', e.action);
	    console.error('Trigger:', e.trigger);
	}); 

	function show_toast() {	 
	  var x = document.getElementById("snackbar_toastmeet");
	  x.className = "show";	 
	  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
	}
	
});	