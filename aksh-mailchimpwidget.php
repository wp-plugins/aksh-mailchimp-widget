<?php
/*
Plugin Name: Aksh Mailchimp Widget
Description: Aksh Mailchimp widget for wordpress to let your users sign up for MailChimp list. You can use it in your any widget area defined in your theme. 
Version: 1.0
Author: Manish H. Gajjar
Author URI: #
License: GPLv2 or later

Manish H. Gajjar
Copyright (C) 2014-2015, Manish H. Gajjar

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
// Prevent direct file access
function aksh_mailchimp_pluginload(){
	define('AKSH_PLUGIN_DIR',plugin_dir_path(__FILE__));
	define('AKSH_PLUGIN_URL',plugins_url('/' , __FILE__ ));
	define('AKSH_PLUGIN_FILE',__FILE__);
	require_once AKSH_PLUGIN_DIR . 'include/aksh_general.php';
	require_once AKSH_PLUGIN_DIR . 'include/aksh_widget.php';
	if ( is_admin() ) {
		// backend only
		require_once AKSH_PLUGIN_DIR . 'include/aksh_admin.php';
		new amcw_admin();
	}
	return true;
}
add_action('plugins_loaded', 'aksh_mailchimp_pluginload', 22);
?>