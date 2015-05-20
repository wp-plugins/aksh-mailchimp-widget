jQuery('#amcw-submit').click(function(e) {
	e.preventDefault();
	if(jQuery('#amcw_name').length){
		var name = jQuery('#amcw_name').val();
	}
	else{
		var name = "";
	}
	var email = jQuery('#amcw_email').val();
	
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	if(email==""){
		jQuery("#statusmsg").html('<span class="error">Please provide a valid email address.</span>');
		return;
	}
	else if(filter.test(email)){
	}
	else{
		jQuery("#statusmsg").html('<span class="error">Please provide a valid email address.</span>');
		return;
	}
	jQuery('#amcw-submit').attr('disabled','disabled');
   	jQuery.post(
		AkshMailchimpWidgetForm.ajaxurl,
		{
			action : 'aksh-mailchimp-widget-submit',
			FNAME:name,
			EMAIL:email,
			amcw_robocop:jQuery('#amcw_robocop').val(),
			// other parameters can be added along with "action"
			amcwFormNonce:AkshMailchimpWidgetForm.amcwFormNonce,
		},
		function( response ) {
			jQuery('#amcw-submit').removeAttr('disabled');
			if(response['success']==true){
				if(jQuery('#amcw_name').length){
					jQuery('#amcw_name').val('');
				}
				jQuery('#amcw_email').val('');
				jQuery("#statusmsg").html(response['message']);
			}
			else{
				if(jQuery('#amcw_name').length){
					jQuery('#amcw_name').val('');
				}
				jQuery('#amcw_email').val('');
				jQuery("#statusmsg").html(response['message']);
			}
		}
	); 
});
