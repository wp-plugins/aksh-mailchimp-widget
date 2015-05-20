<?php
class amcw_admin{
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'initialize_menu' ) );
		add_action( 'admin_init', array( $this, 'initialize' ) );
	}
	public function initialize(){
		register_setting( 'aksh_mailchimp_settings', 'aksh_mailchimp', array( $this, 'amcw_validate_settings' ) );
	}
	public function initialize_menu() {
		if (function_exists('add_submenu_page')) {
			add_submenu_page('options-general.php', 'Aksh MailChimp', __( 'Aksh MailChimp'), 'manage_options', 'aksh-mailchimp', array( $this, 'amcw_api_settings' ) );
		}
	}
	public function amcw_api_settings(){
		$opts = amcw_get_options( 'general' );
		?>
		<h2><img src="<?php echo AKSH_PLUGIN_URL . 'images/MailchimpIcon.png'; ?>" /> <?php _e( 'Aksh Mailchimp Widgets'); ?></h2>
        <form action="options.php" method="post">
			<?php settings_fields( 'aksh_mailchimp_settings' ); 
			do_settings_sections( 'aksh_mailchimp_settings' );
			?>
			<table width="100%">
            <tr>
            <td valign="top">           
                <table class="form-table">
                <tr>
                	<td colspan="2" style="padding:0px;"><h3 class="amcw-title">MailChimp <?php _e('API Settings'); ?></h3></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="mailchimp_api_key">MailChimp <?php _e( 'API Key'); ?></label></th>
                    <td>
                        <input type="text" class="widefat" placeholder="<?php _e( 'Your MailChimp API key'); ?>" id="aksh_api_key" name="aksh_mailchimp[api_key]" value="<?php echo $opts['api_key']; ?>" />
                        <p class="help"><a target="_blank" href="http://admin.mailchimp.com/account/api"><?php _e( 'Get your API key here.' ); ?></a></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="mailchimp_api_key">MailChimp <?php _e( 'List ID'); ?></label></th>
                    <td>
                        <input type="text" class="widefat" placeholder="<?php _e( 'List ID'); ?>" id="aksh_list_id" name="aksh_mailchimp[list_id]" value="<?php echo $opts['list_id']; ?>" />
                    </td>
                </tr>
                <tr>
                	<td colspan="2" style="padding:0px;"><h3 class="amcw-title">MailChimp <?php _e('Sign up form Settings'); ?></h3></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="aksh_form_labelname">Label for Name input field</label></th>
                    <td>
                        <input type="text" class="widefat" placeholder="<?php _e( 'Your Name'); ?>" id="aksh_form_labelname" name="aksh_mailchimp[aksh_form_labelname]" value="<?php echo $opts['aksh_form_labelname']; ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="aksh_form_labelemail">Label for Email input field</label></th>
                    <td>
                        <input type="text" class="widefat" placeholder="<?php _e( 'Your Email'); ?>" id="aksh_form_labelemail" name="aksh_mailchimp[aksh_form_labelemail]" value="<?php echo $opts['aksh_form_labelemail']; ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="aksh_form_labelsubscribe">Label for Subscribe Button</label></th>
                    <td>
                        <input type="text" class="widefat" placeholder="<?php _e( 'Subscribe'); ?>" id="aksh_form_labelsubscribe" name="aksh_mailchimp[aksh_form_labelsubscribe]" value="<?php echo $opts['aksh_form_labelsubscribe']; ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <td colspan="2"><input type="checkbox" name="aksh_mailchimp[form_ishidenamefield]" value="true" id="form_ishidenamefield" <?php if($opts['form_ishidenamefield']==true){ echo 'checked="checked"';}?>/>&nbsp;<label for="form_ishidenamefield"><?php _e( 'Hide NAME field in sign up form'); ?></label></td>
                </tr>
                <tr valign="top">
                    <td colspan="2"><input type="checkbox" name="aksh_mailchimp[form_isplaceholder]" value="true" id="form_isplaceholder" <?php if($opts['form_isplaceholder']==true){ echo 'checked="checked"';}?>/>&nbsp;<label for="form_isplaceholder"><?php _e( 'Display label as placeholder in sign up form input fields'); ?></label></td>
                </tr>
                <tr valign="top">
                    <td colspan="2"><input type="checkbox" name="aksh_mailchimp[amcw_double_opt_in]" value="true" id="amcw_double_opt_in" <?php if($opts['amcw_double_opt_in']==true){ echo 'checked="checked"';}?>/>&nbsp;<label for="amcw_double_opt_in"><?php _e( 'Enable Mailchimp Double Opt-in to send confirmation email to subscriber before register in MailChimp list.'); ?></label></td>
                </tr>
                </table>
            </td>
            <td width="10"></td>
            <td width="281" valign="top">
            	<p>Donate small amount to appreciate my work and inspire me to create more plugins and widgets.</p>
                <p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=manish2384%40gmail%2ecom&lc=IN&item_name=Aksh%20Mailchimp%20Widget&amount=10%2e00&currency_code=USD&button_subtype=services&bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHosted" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" alt="Donate Button with Credit Cards" /></a></p>
            </td>
            </tr>
            </table>
            <?php submit_button(); ?>
                
		</form>
		<?php
	}
	public function amcw_validate_settings( $settings ) {
		if( isset( $settings['api_key'] ) ) {
			$settings['api_key'] = trim( strip_tags( $settings['api_key'] ) );
		}
		return $settings;
	}
}
?>