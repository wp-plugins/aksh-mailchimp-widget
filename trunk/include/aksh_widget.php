<?php
class akshMailchimpWidget extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'aksh_mailchimp_widget', 'description' => __('Displays a mailchimp sign-up form.'));
		parent::__construct(false, 'Aksh Mailchimp Signup', $widget_ops);
	}
	function widget($args, $instance) {
		wp_enqueue_script( 'aksh-mailchimp-widget-form', AKSH_PLUGIN_URL.'js/aksh-mailchimp-widgetform.js', array(), '20150405', true );
		wp_localize_script( 'aksh-mailchimp-widget-form', 'AkshMailchimpWidgetForm', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),'amcwFormNonce'=>wp_create_nonce( 'amcw_form_nonce' )));
		
		/* Provide some defaults */
		$defaults = array( 'title' => '', 'text_before_form' => '', 'text_after_form' => '');
		$instance = wp_parse_args( (array) $instance, $defaults );	
		extract( $args );
		extract($instance);
		$title = apply_filters('widget_title', $title);
		echo $before_widget;
		if($title!=""){
			echo $before_title . $title . $after_title;
		}
		
		$opts = amcw_get_options( 'general' );
		if($opts['api_key']!="" && $opts['list_id']!=""){		
			if(!empty($text_before_form)) { 
				?><div class="amcw-text-before-form"><?php
					$instance['filter'] ? _e(wpautop($text_before_form)) : _e($text_before_form); 
				?></div><?php
			}
			
			echo '<div class="amcw_formwrap"><form class="amcw_form" id="amcw-form" action="'.admin_url( 'admin-ajax.php' ).'" method="post">';
			echo '<div id="statusmsg"></div>';
			if($opts['form_ishidenamefield']!=true){/**Sign Up form with name field**/
				/**Set default labels if not set from admin**/
				if($opts['aksh_form_labelname']!=""){
					$label_namefield=$opts['aksh_form_labelname'];
				}
				else{
					$label_namefield=__('Your Name');
				}
				if($opts['aksh_form_labelemail']!=""){
					$label_emailfield=$opts['aksh_form_labelemail'];
				}
				else{
					$label_emailfield=__('Your Email');
				}
				/**Set default labels for subscribe button if not set from admin**/
				if($opts['aksh_form_labelsubscribe']!=""){
					$label_subscribebuton=$opts['aksh_form_labelsubscribe'];
				}
				else{
					$label_subscribebuton=__('Subscribe');
				}
				/**Set labels of placeholder as per admin settings*/
				if($opts['form_isplaceholder']==true){
					echo '<p class="amcw_fieldwrap"><input type="text" id="amcw_name" name="FNAME" placeholder="'.$label_namefield.'" /></p>';
					echo '<p class="amcw_fieldwrap"><input type="email" id="amcw_email" name="EMAIL" placeholder="'.$label_emailfield.'" /></p>';
				}
				else{
					echo '<p class="amcw_fieldwrap"><label for="aksh_form_labelname" class="aksh_form_label">'.$label_namefield.'</label>';
					echo '<input type="text" id="amcw_name" name="FNAME" /></p>';
					
					echo '<p class="amcw_fieldwrap"><label for="aksh_form_labelemail" class="aksh_form_label">'.$label_emailfield.'</label>';
					echo '<input type="text" id="amcw_email" name="EMAIL" /></p>';
				}
				echo '<p class="amcw_submitwrap"><input type="submit" id="amcw-submit" class="amcw-submit" name="amcw_submit" value="'.$label_subscribebuton.'" /></p>';
			}
			else{/**Sign Up form without name field**/
				/**Set default labels if not set from admin**/
				if($opts['aksh_form_labelemail']!=""){
					$label_emailfield=$opts['aksh_form_labelemail'];
				}
				else{
					$label_emailfield=__('Your Email');
				}
				/**Set default labels for subscribe button if not set from admin**/
				if($opts['aksh_form_labelsubscribe']!=""){
					$label_subscribebuton=$opts['aksh_form_labelsubscribe'];
				}
				else{
					$label_subscribebuton=__('Subscribe');
				}
				/**Set labels of placeholder as per admin settings*/
				if($opts['form_isplaceholder']==true){
					echo '<input type="text" id="amcw_email" name="EMAIL" placeholder="'.$label_emailfield.'" />';
					echo '<input type="submit" id="amcw-submit" class="amcw-submit" name="amcw_submit" value="'.$label_subscribebuton.'" />';
				}
				else{
					echo '<p class="amcw_submitwrap"><label for="aksh_form_labelemail" class="aksh_form_label">'.$label_emailfield.'</label>';
					echo '<input type="text" id="amcw_email" name="EMAIL" /></p>';
					echo '<p class="amcw_submitwrap"><input type="submit" id="amcw-submit" class="amcw-submit" name="amcw_submit" value="'.$label_subscribebuton.'" /></p>';
				}
			}
			
			echo '<textarea name="amcw_robocop" id="amcw_robocop" style="display: none;"></textarea>';
			//echo '<input type="hidden" name="_amcw_form_nonce" id="_amcw_form_nonce" value="'.wp_create_nonce( '_amcw_form_nonce' ).'" />';
			echo '</form></div>';
			
			if(!empty($text_after_form)) {
				?><div class="amcw-text-after-form"><?php
					$instance['filter'] ? _e(wpautop($text_after_form)) : _e($text_after_form); 
				?></div><?php
			}
		}
		else{
			echo __('Oops! admin forget to set MailChimp requirements.');
		}
		echo $after_widget; 
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['filter'] = strip_tags($new_instance['filter']);
		if ( current_user_can('unfiltered_html') ) {
			$instance['text_before_form'] =  $new_instance['text_before_form'];
			$instance['text_after_form'] =  $new_instance['text_after_form'];
		} else {
			$instance['text_before_form'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text_before_form']) ) );
			$instance['text_after_form'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text_after_form']) ) );
		}
		return $instance;
	}
	function form($instance) {	
		$defaults = array( 'title' => __(''), 'text_before_form' => '', 'text_after_form' => '');
		$instance = wp_parse_args( (array) $instance, $defaults );		
		extract($instance);
		$title = strip_tags($title);
		?>       
		<p>
		  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
        	<label title="You can use the following HTML-codes:  &lt;a&gt;, &lt;strong&gt;, &lt;br /&gt;,&lt;em&gt; &lt;img ..&gt;" for="<?php echo $this->get_field_id('text_before_form'); ?>"><?php _e('Text to show before the form:'); ?></label> 
			<textarea rows="8" cols="10" class="widefat" id="<?php echo $this->get_field_id('text_before_form'); ?>" name="<?php echo $this->get_field_name('text_before_form'); ?>"><?php echo $text_before_form; ?></textarea>
		</p>
        <p>
			<label title="You can use the following HTML-codes:  &lt;a&gt;, &lt;strong&gt;, &lt;br /&gt;,&lt;em&gt; &lt;img ..&gt;" for="<?php echo $this->get_field_id('text_after_form'); ?>"><?php _e('Text to show after the form:'); ?></label> 
			<textarea rows="8" cols="10" class="widefat" id="<?php echo $this->get_field_id('text_after_form'); ?>" name="<?php echo $this->get_field_name('text_after_form'); ?>"><?php echo $text_after_form; ?></textarea>
		</p>
        <p>
		  <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" <?php if($instance['filter']){ echo 'checked="checked"';}?> />&nbsp;&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label> 
		</p>
        <p><strong>Important: </strong>Go to <strong>Settings->Aksh MailChimp</strong>, if you forget to set MailChimp API and List ID. </p>
        <p>Donate small amount to appreciate my work and inspire me to create more plugins and widgets.</p>
        <p>
        	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=manish2384%40gmail%2ecom&lc=IN&item_name=Aksh%20Mailchimp%20Widget&amount=10%2e00&currency_code=USD&button_subtype=services&bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHosted" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" alt="Donate Button with Credit Cards" /></a>
        </p>
		<?php 
	}
}
add_action( 'wp_ajax_nopriv_aksh-mailchimp-widget-submit', 'aksh_mailchimp_widget_submit');
add_action( 'wp_ajax_aksh-mailchimp-widget-submit','aksh_mailchimp_widget_submit');
function aksh_mailchimp_widget_submit(){
	$error = '';
	$amcwFormNonce=$_POST['amcwFormNonce'];
	if ( ! wp_verify_nonce( $amcwFormNonce, 'amcw_form_nonce' ))
		die ( 'Busted!');
	if ( isset( $_POST['amcw_robocop'] ) && empty( $_POST['amcw_robocop'] ) ) {
		$data = array();
		// Ignore those fields, we don't need them
		$ignored_fields = array( 'CPTCH_NUMBER', 'CNTCTFRM_CONTACT_ACTION', 'CPTCH_RESULT', 'CPTCH_TIME' );
		foreach( $_POST as $key => $value ) {
			// Sanitize key
			$key = trim( strtoupper( $key ) );
			// Skip field if it starts with _ or if it's in ignored_fields array
			if( $key[0] === '_' || in_array( strtoupper( $key ), $ignored_fields ) ) {
				continue;
			}
			// Sanitize value
			$value = ( is_scalar( $value ) ) ? trim( $value ) : $value;
			// Add value to array
			$data[ $key ] = $value;
		}
		// strip slashes on everything
		$data = stripslashes_deep( $data );
		if( ! isset( $data['EMAIL'] ) || ! is_email( $data['EMAIL'] ) ) {
			$error = '<span class="error">'.__('Please provide a valid email address.').'</span>';
		}
		else{
			$name = $data['FNAME'];
			$strpos = strpos( $name, ' ' );

			if ( $strpos ) {
				$merge_vars['FNAME'] = substr( $name, 0, $strpos );
				$merge_vars['LNAME'] = substr( $name, $strpos );
			} else {
				$merge_vars['FNAME'] = $data['FNAME'];
				$merge_vars['LNAME'] = '';
			}
			
			$email = $data['EMAIL'];
			$opts = amcw_get_options( 'general' );
			
			$api_url = 'https://api.mailchimp.com/2.0/';
			$api_key=$opts['api_key'];
			$list_id=$opts['list_id'];
			if($opts['amcw_double_opt_in']){
				$double_opt_in=true;
			}
			else{
				$double_opt_in=false;
			}
			$email_type='html';
			$method='lists/subscribe';
			
			if( strpos( $api_key, '-' ) !== false ) {
				$api_url = 'https://' . substr( $api_key, -3 ) . '.api.mailchimp.com/2.0/';
			}
			$data = array(
				'apikey' => $api_key,
				'id' => $list_id,
				'email' => array( 'email' => $email),
				'merge_vars' => $merge_vars,
				'email_type' => $email_type,
				'double_optin' => $double_opt_in,
				'update_existing' => false,
				'replace_interests' =>true,
				'send_welcome' => false
			);
			
			$url = $api_url . $method . '.json';
			
			$response = wp_remote_post( $url, array( 
				'body' => $data,
				'timeout' => 15,
				'headers' => array('Accept-Encoding' => ''),
				'sslverify' => false
				) 
			);
			
			if( is_wp_error( $response ) ) {
				// show error message to admins
				$error = '<span class="error">HTTP Error: ' . $response->get_error_message().'</span>';
			}
			else{
				$body = wp_remote_retrieve_body( $response );
				$result = json_decode( $body );
				if( is_object( $result ) ) {
					if(isset( $result->error ) ) {
						if( (int) $result->code === 214 ) {
							$error = '<span class="error">'.__('Given email address is already subscribed, thank you!').'</span>';
						} 
						// store error message
						$error = '<span class="error">'.$result->error.'</span>';
						$response = json_encode( array( 'fail' => true, 'message'=>$error, 'amcwFormNonce'=>$amcwFormNonce) );
					} else {
						$error = '<span class="success">'.__('Thank you, your sign-up request was successful! Please check your e-mail inbox.').'</span>';
						$response = json_encode( array( 'success' => true, 'message'=>$error, 'amcwFormNonce'=>$amcwFormNonce) );
					}
				}
			}
		}
	}
	else{
		$error = '<span class="error">'.__('Oops. Something went wrong. Please try again later.').'</span>';
		$response = json_encode( array( 'fail' => true, 'message'=>$error,'amcwFormNonce'=>$amcwFormNonce) );
	}
	header( "Content-Type: application/json" );
	echo $response;
	// IMPORTANT: don't forget to "exit"
	exit;
}
function amcw_widget_init() {
	register_widget('akshMailchimpWidget');
}
add_action('widgets_init', 'amcw_widget_init');
?>