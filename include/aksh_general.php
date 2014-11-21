<?php
function amcw_get_options( $key = null ) {
	static $options = null;
	if( null === $options ) {
		$defaults = array(
			'general' => array(
				'api_key' => '',
				'list_id' => ''
			)
		);
		$db_keys_option_keys = array(
			'aksh_mailchimp' => 'general'
		);
		$options = array();
		foreach ( $db_keys_option_keys as $db_key => $option_key ) {
			$option = get_option( $db_key, false );
			if ( $option === false ) {
				add_option( $db_key, $defaults[$option_key] );
			}
			$options[$option_key] = array_merge( $defaults[$option_key], (array) $option );
		}
	}
	if( null !== $key ) {
		return $options[$key];
	}
	return $options;
}
?>