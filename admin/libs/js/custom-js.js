/**
 * Admin Custom Jquery Scripts
 * @package mwa-zoom-meetup	
 * @author  MyWebApp
 * @since  1.0
 */

jQuery(document).ready( function () {

	function toastvc(message,topic){
		Command: toastr["success"](message, topic);
		toastr.options = {
		  "closeButton": true,
		  "debug": false,
		  "newestOnTop": false,
		  "progressBar": true,
		  "positionClass": "toast-top-right",
		  "preventDuplicates": false,
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "5000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
		}
	}

	function warntoastvc(message,topic){
		Command: toastr["error"](message, topic);
		toastr.options = {
		  "closeButton": true,
		  "debug": false,
		  "newestOnTop": false,
		  "progressBar": true,
		  "positionClass": "toast-top-right",
		  "preventDuplicates": false,
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "5000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
		}
	}

	/* Token Generating Action */
	jQuery('#addtokenform').on('submit', function(e){
		e.preventDefault();
		jQuery("#loader").css("display", "block");
		jQuery("#mwa_tokenmanage_div").css("display", "none");
		jQuery.ajax({
			method: 'post',
			url: ajaxurl + "?action=addmanagetoken",
			data: new FormData(this),
			contentType: false,
			processData: false,
			success: function(admin_token_resp) {
				if( admin_token_resp['success_msg'] == 1 ) {
					toastvc('Token added successfully','Token Manager');
					setTimeout(function(){ location.reload(true); }, 2000);				
				}
				else if( admin_token_resp['success_msg'] == 0 ) {	
					var go_pro_url = "https://mywebapp.in/amember/signup/?product_id_page-0%5B6-6%5D=6-6";
					warntoastvc('Please try pro version for add more','Video Conferencing – Zoom Meetings Pro');
					setTimeout(function(){ window.location.replace(go_pro_url); }, 2000);
				}
			}
		});
	});

	jQuery('#wp_zoom_toktbl').DataTable();
	jQuery('#wp_zoom_meetlist').DataTable({ "pageLength": 50 });
	jQuery('#wp_zoom_useremail_list').DataTable({ "pageLength": 50 });	

    /* Save value ajax to session code */
    jQuery(".save_tok_btn").click( function(e) {
		e.preventDefault(); 
		jQuery("#loader").css("display", "block");
		jQuery("#mwa_tokenmanage_div").css("display", "none");
		authid = jQuery(this).attr("data-id");
		umail  = jQuery(this).attr("data-umail");
		client = jQuery(this).attr("data-client");
		secret = jQuery(this).attr("data-secret");
		reduri = jQuery(this).attr("data-reduri");
		
		jQuery.ajax({
			type : "post",
			dataType : "json",
			url : ajaxurl,
			data : {action: "savetokentosession",authid:authid,client:client,secret:secret,reduri:reduri,umail:umail},
			success: function(response) {
				if(response['lockopen'] == "1") {					
					redirect_to_zoom(client,reduri);
				}else if(response['lockopen'] == "2"){					
					warntoastvc("Please edit your token first & add email of zoom account.","Token Setting Error");
					jQuery('#edittknbtn_'+authid).addClass('glow_btn');
					setTimeout(function(){ location.reload(true); }, 2000);
				}
				else {
					warntoastvc("Error","Token Setting");
				}
			}
		}); 
   });

	function redirect_to_zoom(client,reduri){
		var authurl = "https://zoom.us/oauth/authorize?response_type=code&client_id="+client+"&redirect_uri="+reduri;
		window.location.replace(authurl);
	} 

    jQuery(".edit_tok_databtn").click( function(e) {
    	e.preventDefault();     	
    	authid = jQuery(this).attr("data-id");
		client = jQuery(this).attr("data-client")
		secret = jQuery(this).attr("data-secret");
		uac    = jQuery(this).attr("data-uac");	
		usermail= jQuery(this).attr("data-usermail");	
		stat   = jQuery(this).attr("data-stat");
		nonce  = jQuery(this).data('nonce');
		
		jQuery.ajax({
			type : "post",
			dataType : "json",
			url : ajaxurl,
			data : {
				action: "edittokenajax",
				authid:authid,
				client:client,
				secret:secret,
				stat:stat,
				uac:uac,
				usermail:usermail,
				nonce_data:nonce,
			},
			success: function(response) {				
				jQuery('#edit_uac').val(response['edittoken_uac']);	
				jQuery('#edit_usermail').val(response['edittoken_usermail']);			
				jQuery('#edit_apiid').val(response['edittoken_client']);			
				jQuery('#edit_apikey').val(response['edittoken_secret']);				
				jQuery('#edit_authid').val(response['edittoken_authid']);
				jQuery("#edit_apistat option[value='"+response['edittoken_stat']+"']").attr("selected","selected");
			}
		}); 
    });
    
    jQuery("#copy_clip_btn").click( function(e) {
        jQuery("#redirect_uri_cc").css("background", "#efa112"); 
        jQuery("#redirect_uri_cc").css("color", "#ffffff");
    });

    jQuery(".edit_updatetok_btn").click( function(e) {
    	e.preventDefault();  
    	get_authid = jQuery('#edit_authid').val();
    	get_uac    = jQuery('#edit_uac').val();
    	get_usermail 	= jQuery('#edit_usermail').val();
    	get_apikey = jQuery('#edit_apikey').val();
    	get_apiid  = jQuery('#edit_apiid').val();    	
    	get_stat   = jQuery( "#edit_apistat option:selected" ).val();
    	get_nonce  = jQuery(this).data('updnonce');  	
    	jQuery.ajax({
			type : "post",
			dataType : "json",
			url : ajaxurl,
			data : {
				action: "updatetokenajax",
				get_authid:get_authid,				
				get_uac:get_uac,
				get_usermail:get_usermail,
				get_apiid:get_apiid,
				get_apikey:get_apikey,
				get_stat:get_stat,				
				nonce_upddata:get_nonce,
			},
			success: function(response) {				
				toastvc(response['success_upd'],"Token Manager");
				setTimeout(function(){ location.reload(true); }, 2000);	
			}
		}); 
    	
    });

    var clipboard = new ClipboardJS('#copy_clip_btn');

	clipboard.on('success', function(e) {
		show_toast();
	    e.clearSelection();
	});

	clipboard.on('error', function(e) {
	    console.error('Action:', e.action);
	    console.error('Trigger:', e.trigger);
	}); 

	function show_toast() {	 
	  var x = document.getElementById("snackbar_toast");
	  x.className = "show";	 
	  setTimeout(function(){ x.className = x.className.replace("show", "");
	  jQuery("#redirect_uri_cc").css("background", "#ffffff"); 
	  jQuery("#redirect_uri_cc").css("color", "#000000");
	   }, 3000);
	}

	function show_toast_formail() {	 
	  var x = document.getElementById("snackbar_toast_mail");
	  x.className = "show";	 
	  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
	}  

	/* Create Meeting Action */
	jQuery('#addmeetingform').on('submit', function(e){
		e.preventDefault();
		jQuery("#loader").css("display", "block");
	    jQuery(".mwa_crtmeet").css("display", "none");	    
		jQuery.ajax({
			method: 'post',
			url: ajaxurl + "?action=addmeeting",
			data: new FormData(this),
			contentType: false,
			processData: false,
			success: function(admin_meeting_resp) {		
				
				if( admin_meeting_resp['success_msg'] == 1 ) {
					var go_meetlist_url = admin_meeting_resp['showmeeting_list_url'];
					toastvc('Meeting created successfully','Add Meeting');
					setTimeout(function(){ window.location.replace(go_meetlist_url); }, 2000);															
				}
				
				if( admin_meeting_resp['success_msg'] == 0 ){
				    var goto_url = admin_meeting_resp['home_url'];
				    warntoastvc("Token is expired. Plase regenerate again.","Add Meeting");
					setTimeout(function(){ window.location.replace(goto_url); }, 2000);
				}
			}
		});
	});

	/*Initialize select2 add action of seacrhable dropdown*/
	jQuery("#timezone").select2();

	/* User Create Action */
	jQuery('#user_add_form').on('submit', function(e){
		e.preventDefault();
		jQuery.ajax({
			method: 'post',
			url: ajaxurl + "?action=adduser",
			data: new FormData(this),
			contentType: false,
			processData: false,
			success: function(admin_users) {
				if( admin_users['success_msg'] == 1 ) {
					toastvc("Success","Add Users");
					setTimeout(function(){ location.reload(true); }, 2000);				
				}
				else {
					warntoastvc("Error","Add Users");
					setTimeout(function(){ location.reload(true); }, 2000);					
				}
			}
		});
	});

	/* Delete User Action  for mail*/
	jQuery('.del_userbtn').on('click', function(e){
		e.preventDefault();
		var delid = jQuery(this).data('delid');
		var get_nonce  = jQuery(this).data('delnonce'); 
		toastr.warning("<br /><button type='button' value='yes'>Yes</button> <button type='button' value='no' >No</button>",'Are you sure you want to delete this user?',
		{
		    allowHtml: true,
		    onclick: function (toast) {
		      value = toast.target.value
		      if (value == 'yes') {   
		      		jQuery.ajax({
						type : "post",
						dataType : "json",
						url : ajaxurl,
						data : {
							action: "deluser",
							delid:delid,	
							nonce_deluserdata:get_nonce,
							del_type: "single",
						},
						success: function(response_del) {
							if( response_del['success_msg_del'] == 1 ){								
								toastvc("Deleted","Manage Users");
								setTimeout(function(){ location.reload(true); }, 2000); 
							}else{
								warntoastvc("Error","Manage Users");
								setTimeout(function(){ location.reload(true); }, 2000); 
							}	
						}
					}); 
		      }else{
		      	location.reload(true);
		    }
		   }
		});					
	});

	jQuery('.edit_userbtn').on('click', function(e){		
		var edit_uid = jQuery(this).data('edituid');				
		jQuery('#edit_userbtn_'+edit_uid).hide();
		jQuery('#field_span_user_'+edit_uid).hide();
		jQuery('#field_user_'+edit_uid).show();

		jQuery('#field_span_email_'+edit_uid).hide();
		jQuery('#field_email_'+edit_uid).show();

		jQuery('#save_userbtn_'+edit_uid).show();
		jQuery('#cancel_userbtn_'+edit_uid).show();

		jQuery('#stat_user_'+edit_uid).hide();
		jQuery('#stat_edituser_'+edit_uid).show();		
	});

	/*Cancel User*/
	jQuery('.cancel_userbtn').on('click', function(e){	
		location.reload(true);
	});

	/*Save User*/
	jQuery('.save_userbtn').on('click', function(e){
		var get_saveuid 		= jQuery(this).data('saveuid');		
		var get_field_user   	= jQuery('#field_user_'+get_saveuid).val();
    	var get_field_email		= jQuery('#field_email_'+get_saveuid).val();
    	var get_field_stat 		= jQuery('#stat_edituser_'+get_saveuid).val();   	
    	var get_editusernonce   = jQuery(this).data('editusernonce');  	
    	jQuery.ajax({
			type : "post",
			dataType : "json",
			url : ajaxurl,
			data : {
				action: "edituser",
				get_saveuid:get_saveuid,				
				get_field_user:get_field_user,
				get_field_email:get_field_email,
				get_field_stat:get_field_stat,
				get_editusernonce:get_editusernonce,			
			},
			success: function(responseupd) {
				if(responseupd['success_msg_update']==="1"){					
					toastvc("Updated","Manage User");
					setTimeout(function(){ location.reload(true); }, 2000); 
				}else{
					warntoastvc("Error","Manage User");
					setTimeout(function(){ location.reload(true); }, 2000); 
				}				
			}
		}); 
	});

	/* Multiple User delete js*/ 
    jQuery('body').on('click', '#chkuserAL', function() { 
        jQuery('input:checkbox').not(this).prop('checked', this.checked);
    });

    jQuery( "#del_all_user" ).click(function() {   
     	var multi_delid = [];
        var get_muldelusernonce   = jQuery(this).data('noncedel'); 	
        jQuery(':checkbox:checked').each(function(i){
          multi_delid[i] = jQuery(this).val();
        });

      	toastr.warning("<br /><button type='button' value='yes'>Yes</button> <button type='button' value='no' >No</button>",'Are you sure you want to delete users?',
		{
		    allowHtml: true,
		    onclick: function (toast) {
		      value = toast.target.value
		      if (value == 'yes') {             
		    	  if(multi_delid.length === 0) //tell you if the array is empty
		          {
		           	warntoastvc("Please Select at least one checkbox","Delete Users");		           	
		          }
		          else{
		          	jQuery.ajax({
						type : "post",
						dataType : "json",
						url : ajaxurl,
						data : {
							action: "deluser",
							multi_delid:JSON.stringify(multi_delid),	
							nonce_deluserdata:get_muldelusernonce,
							del_type: "multi",
						},
						success: function(response_del_multi) {
							if( response_del_multi['success_msg_del'] == 1 ){
								toastvc("Deleted","Manage Users");
								setTimeout(function(){ location.reload(true); }, 2000);
							}else{
								warntoastvc("Error","Manage Users");
								setTimeout(function(){ location.reload(true); }, 2000);	
							}	
						}
					}); 		          	
		          }
		      }else{
		      	location.reload(true);
		    }
		   }
		});
    });

    /* Multiple User send mail js*/ 
    jQuery('body').on('click', '#chkuserMAL', function() { 
        jQuery('input:checkbox').not(this).prop('checked', this.checked);
    });

    jQuery( ".send_mail_btn" ).click(function() { 
    	var go_pro_url = "https://mywebapp.in/amember/signup/?product_id_page-0%5B6-6%5D=6-6";
		warntoastvc('Please try pro version for send E-Mail','Video Conferencing – Zoom Meetings Pro');
		setTimeout(function(){ window.location.replace(go_pro_url); }, 2000);
    	/*var pageurl_mail 	 = jQuery('#pageurl_mail').val();
    	if(jQuery('#view_type').val()==1 && pageurl_mail==""){
    		alert("Please Add Page/Post URL First");
    		jQuery('#pageurl_mail').focus();
    	}else{    		
	    	var multi_uid = [];    	
	        var get_emailnonce   = jQuery(this).data('emailnonce');
	        
	        var meet_id 	 = jQuery('#meet_id').val();
	        jQuery(':checkbox:checked').each(function(i){
	          multi_uid[i] = jQuery(this).val();
	        });

	         if(multi_uid.length === 0) //tell you if the array is empty
	          {
	            alert("Please Select at least one checkbox");
	            jQuery('#loader_btn').hide();
    			jQuery('.send_mail_btn').show();
	          }
	          else{	
	          		var multi_uid_jsonstring = JSON.stringify(multi_uid);
	          		jQuery('#loader_btn').show();
    				jQuery('.send_mail_btn').hide();
	          	   	jQuery.ajax({
					type : "post",
					dataType : "json",
					url : ajaxurl,
					data : {
						action: "sendmail",					
						multi_uid:multi_uid_jsonstring,
						pageurl_mail:pageurl_mail,	
						meet_id:meet_id,
						nonce_sendmail:get_emailnonce,
					},
					success: function(response_mail) {	
						if( response_mail['success_msg_email'] == 1 ){						
							show_toast_formail();						
							setTimeout(function() { location.reload(true); }, 3000);
						}else{						
							location.reload(true);
						}	
					}
				});
	        }
    	}*/    	
    });

    /*Get mail data action for meeting*/
    jQuery( ".open_mail_btn" ).click(function() { 
    	var meetid 				 = jQuery(this).data('meetid');
    	var get_openmailnonce    = jQuery(this).data('openmailnonce');
    	jQuery.ajax({
			type : "post",
			dataType : "json",
			url : ajaxurl,
			data : {
				action: "openmail",					
				meetid:meetid,	
				nonce_openmail:get_openmailnonce,
			},
			success: function(open_mail) {	
				var meet_id 		= open_mail['meet_id'];		
				var meet_pass 	    = open_mail['meet_password'];
				var meet_datetime 	= open_mail['meet_date']+" , "+open_mail['meet_time'];			
				var meet_join 		= open_mail['meet_join'];
				var meet_topic 		= open_mail['meet_topic'];
				var view_type		= open_mail['view_type'];
				// 0 => anyone can join meeting , 1 => Only registered user can join meeting
				if(view_type==0){
					var email_msg = '<b>Hi</b> <code>Username</code> , Your New Meeting&nbsp;&nbsp; </br>'+" &nbsp;&nbsp;Topic : "+meet_topic+"</br> &nbsp;&nbsp;Meeeting ID : "+meet_id+"<input id='meet_id' type='hidden' value='"+meet_id+"'/><input id='view_type' type='hidden' value='"+view_type+"'/></br> &nbsp;&nbsp;Meeting Password : "+meet_pass+"</br> &nbsp;&nbsp;Time : "+meet_datetime+"</br> &nbsp;&nbsp;Join URL : "+"<a href='"+meet_join+"' target='_blank'>"+meet_join+"</a>"+"</br>Thank You.</br>-<b><code>The MWA ZOOM Team</code></b>";
				}else{
					var email_msg = '<b>Hi</b> <code>Username</code> , Your New Meeting&nbsp;&nbsp; </br>'+" &nbsp;&nbsp;Topic : "+meet_topic+"</br> &nbsp;&nbsp;Meeeting ID : "+meet_id+"<input id='meet_id' type='hidden' value='"+meet_id+"'/><input id='view_type' type='hidden' value='"+view_type+"'/></br> &nbsp;&nbsp;Meeting Password : "+meet_pass+"</br> &nbsp;&nbsp;Time : "+meet_datetime+"</br> &nbsp;&nbsp;<div style='margin-left: 9px;'>"+"<input type='text' class='form-control' id='pageurl_mail' placeholder='Enter Page/Post URL'/></div>"+"</br>Thank You.</br>-<b><code>The MWA ZOOM Team</code></b>";
				}
				
				jQuery('#mailbody_txt').html(email_msg);
			}
  	  });
	});

    /*Delete Zoom Token Action*/
	jQuery(".del_tokenbtn").click( function(e) {
    	e.preventDefault();  
    	var get_del_authid	 	= jQuery(this).data('id');
    	var get_del_authnonce	= jQuery(this).data('delauthnonce');
    	toastr.warning("<br /><button type='button' value='yes'>Yes</button> <button type='button' value='no' >No</button>",'Are you sure you want to delete this token?',
		{
		    allowHtml: true,
		    onclick: function (toast) {
		      value = toast.target.value
		      if (value == 'yes') {             
		    	jQuery.ajax({
					type : "post",
					dataType : "json",
					url : ajaxurl,
					data : {
						action: "delauthtokenajax",
						get_del_authid:get_del_authid,
						nonce_delauth:get_del_authnonce,
					},
					success: function(response) {				
						if(response['success_auth_del']==1){
							toastvc("Deleted","Token Manager");
							setTimeout(function(){ location.reload(true); }, 1000);	
						}				
					}
				});
		      
		      }else{
		      location.reload(true);
		    }
		   }
		}); 	    			    	
    });


	/*Delete Zoom Meeting Action*/
	jQuery('.del_meet').on('click', function(e){
		e.preventDefault();		
		var meet_did		= jQuery(this).data('did');
		var getmeet_nonce  	= jQuery(this).data('delmeetnonce');
		toastr.warning("<br /><button type='button' value='yes'>Yes</button> <button type='button' value='no' >No</button>",'Are you sure you want to delete this meeting?',
		{
		    allowHtml: true,
		    onclick: function (toast) {
		      value = toast.target.value
		      if (value == 'yes') {             
		    	jQuery.ajax({
					type : "post",
					dataType : "json",
					url : ajaxurl,
					data : {
						action: "deletemeeting",
						meet_did:meet_did,	
						nonce_delmeetdata:getmeet_nonce,
					},
					success: function(response_meet_del) {
						if( response_meet_del['success_meet_del'] == 1 ){							
							toastvc("Deleted","Manage Meeting");
							setTimeout(function(){ location.reload(true); }, 2000); 
						}else{
							warntoastvc("Token is expired. Please generate the token first","Manage Meeting");
							setTimeout(function(){ window.location.replace(response_meet_del['failed_url']); }, 2000); 
						}	
					}
				}); 
		      
		      }else{
		      	location.reload(true);
		    }
		   }
		});					
	});

	jQuery('.meetview_type').change(function(){	
	 	var get_mvtid  	 = jQuery(this).data('mvtid');  	
	 	var get_mvtval 	 = jQuery("#mvtid_"+get_mvtid).children("option:selected").val();
	 	var get_mvtnonce = jQuery(this).data('mvtnonce'); 
	 	jQuery.ajax({
			type : "post",
			dataType : "json",
			url : ajaxurl,
			data : {
				action: "addmeetviewtype",
				get_mvtid:get_mvtid,
				get_mvtval:get_mvtval,		
				get_mvtnonce:get_mvtnonce,
			},
			success: function(response_mvt) {
				toastvc("Updated","Meeting View Type");
				setTimeout(function(){ location.reload(true); }, 2000);	
			}
		}); 		
	});

	var input = jQuery('#lvtime_zoom');
	input.clockpicker({
	    autoclose: true
	});

	jQuery('#material-tabs').each(function() {

		var $active, $content, $links = jQuery(this).find('a');

		$active = jQuery($links[0]);
		$active.addClass('active');

		$content = jQuery($active[0].hash);

		$links.not($active).each(function() {
			jQuery(this.hash).hide();
	});

	jQuery(this).on('click', 'a', function(e) {
		$active.removeClass('active');
		$content.hide();

		$active = jQuery(this);
		$content = jQuery(this.hash);

		$active.addClass('active');
		$content.show();

		e.preventDefault();
	});	
});	
});	