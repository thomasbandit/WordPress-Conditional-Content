<?php
/**
 * @package WordPress Conditional Content
 */

class WP_Conditional_Content {

	/**
	 * Constructor functions, registers the 'conditional' shortcode
	 *
	 */
	public function __construct() {

		# Base layer
		add_shortcode( 'if', array( &$this, 'short_code_if' ) );

		# Second layer
		add_shortcode( 'if2', array( &$this, 'short_code_if' ) );

		# Third layer
		add_shortcode( 'if3', array( &$this, 'short_code_if' ) );

		# Fourth layer
		add_shortcode( 'if4', array( &$this, 'short_code_if' ) );

	}

	/**
	 * The function that is called by when the shortcode is parsed
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise nothing.
	 * 
	 */
	public function short_code_if( $atts, $content ) {

		if ( empty( $atts ) )
			return $content;

		$condition_met = false;

		$match = '';

		if ( isset( $atts['match'] ) )
			$match = ( 'contain' == $atts['match'] ) ? 'contain' : 'exact';
		
		foreach ( $atts as $key => $value ) {

			if ( 'qs' == $key )
				$condition_met = $this->condition_query_string( $value, $match );
			
			elseif ( 'referrer' == $key )
				$condition_met = $this->condition_referrer( $value, $match );

			elseif ( 'role' == $key )
				$condition_met = $this->condition_user_role( $value );

			if( ! $condition_met )
				return '';

		}

		return do_shortcode( $content );

	}

	/**
	 * Returns true if specified query string parameters match specified values
	 * @param array $atts The attributes of the shortcode
	 * @return bool True if conditions are met
	 * 
	 */
	private function condition_query_string( $value, $match ) {

		if( ! strstr( $value, ':' ) )
			return false;
		
		list( $qs_key, $qs_value ) = explode( ':', $value );

		$match = ( empty( $match ) ) ? 'exact' : $match;

		# Check if GET value matches given value
		if ( ! isset( $_GET[ $qs_key ] ) || false === $this->check_value( $_GET[ $qs_key ], $qs_value, $match ) )
			return false;

		return true;

	}

	/**
	 * Returns true if HTTP_REFERER matches (any of) the given string(s)
	 * @param array $atts The attributes of the shortcode
	 * @return bool True if conditions are met
	 * 
	 */
	private function condition_referrer( $referrer, $match ) {

		$match = ( empty( $match ) ) ? 'contain' : $match;

		# Return false if no referrer set
		if ( empty( $_SERVER['HTTP_REFERER'] ) )
			return false;

		# Return wether referrer matches
		if( ! $this->check_value( $referrer, $_SERVER['HTTP_REFERER'], $match ) )
			return false;

		return true;

	}

	/**
	 * Returns true if user is logged in and has (any of) the specified role(s)
	 * @param array $atts The attributes of the shortcode
	 * @return bool True if conditions are met
	 */
	private function condition_user_role( $role ) {

		# If role is empty, a user should not have a role to match
		if ( empty( $role ) && is_user_logged_in() )
			return false;
		
		# If role is not empty, user needs to be logged in
		elseif ( ! is_user_logged_in() )
			return false;

		# Return wether current user role matches 
		if( $this->check_value( $role, $this->get_current_user_role() ) )
			return true;

		return false;

	}

	/**
	 * Compare value with another value or set of values
	 * @param string $value1 The value to test
	 * @param string $value2 The allowed value(s)
	 * @param string $match Can be exact for exact matches or contain for wildcard
	 * @return bool Returns true if value1 matches value2 
	 */
	private function check_value( $value1, $value2, $match = 'exact' ) {

		# Check if multiple values
		if ( strstr( $value2, ';' ) )
			$allowed_values = explode( ';', $value2 );
		else
			$allowed_values = array( $value2 );

		# loop through available values to check
		foreach ( $allowed_values as $allowed_value ) {

			# Exact match
			if ( 'exact' == $match ) {
				if ( $allowed_value == $value1 )
					return true;
			}
			# String contains string (wildcard)
			elseif ( 'contain' == $match ) {
				if ( strstr( $allowed_value, $value1 ) )
					return true;
			}
		}
		
		return false;

	}

	/**
	 * Get the role of the current logged in user
	 *
	 */
	private function get_current_user_role() {

		# Get current user object
		$current_user = wp_get_current_user();

		# Get first available role
		if ( ! empty( $current_user->roles ) )
			return array_shift( $current_user->roles );

		return false;

	}


}