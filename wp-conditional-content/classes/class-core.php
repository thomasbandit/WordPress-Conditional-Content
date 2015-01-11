<?php
/**
 * @package WordPress Conditional Content
 */

class WP_Conditional_Content {

	/**
	 * The state if an 'if' shortcode has been registered
	 * @var		bool
	 */
	private $shortcode_init;

	/**
	 * The currently registered conditional statements
	 * @var 	array
	 */
	private $if_statement_collection;

	/**
	 * Default values for a new conditional statement
	 * @var 	array
	 */
	private $initial_statement_conditions;

	/**
	 * Constructor functions, registers the 'conditional' shortcodes
	 *
	 */
	public function __construct() {

		$this->shortcode_init = false;
		$this->if_statement_collection = array();
		$this->initial_statement_conditions = array( true, false, false, false );

		# Base layer
		add_shortcode( 'if', array( &$this, 'short_code_if' ) );

		# Second layer
		add_shortcode( 'if2', array( &$this, 'short_code_if_2' ) );

		# Third layer
		add_shortcode( 'if3', array( &$this, 'short_code_if_3' ) );

		# Fourth layer
		add_shortcode( 'if4', array( &$this, 'short_code_if_4' ) );

		# Base layer elseif
		add_shortcode( 'elseif', array( &$this, 'short_code_elseif' ));

		# Second layer elseif
		add_shortcode( 'elseif2', array( &$this, 'short_code_elseif_2' ));

		# Third layer elseif
		add_shortcode( 'elseif3', array( &$this, 'short_code_elseif_3' ));

		# Fourth layer elseif
		add_shortcode( 'elseif4', array( &$this, 'short_code_elseif_4' ));

	}

	/**
	 * The function that is called when the first if shortcode hook is fired; on 
	 * first run initializes the statements collection array
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	public function short_code_if( $atts, $content ) {

		if ( ! $this->shortcode_init )
			$this->shortcode_init = true;

		$this->if_statement_collection[] = $this->initial_statement_conditions;

		if ( empty( $atts ) )
			return $content;

		return $this->parse_shortcode_if( $atts, $content );

	}

	/**
	 * The function that is called when the second child if shortcode hook is 
	 * fired
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	public function short_code_if_2( $atts, $content ) {
		return $this->shortcode_if_child( 1, $atts, $content );
	}

	/**
	 * The function that is called when the third child if shortcode hook is 
	 * fired
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	public function short_code_if_3( $atts, $content ) {
		return $this->shortcode_if_child( 2, $atts, $content );
	}

	/**
	 * The function that is called when the fourth child if shortcode hook is 
	 * fired
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	public function short_code_if_4( $atts, $content ) {
		return $this->shortcode_if_child( 3, $atts, $content );
	}

	/**
	 * The function that is called when the first elseif shortcode hook is 
	 * fired
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	public function short_code_elseif( $atts, $content ) {
		return $this->parse_shortcode_elseif( 0, $atts, $content );
	}

	/**
	 * The function that is called when the second elseif shortcode hook is 
	 * fired
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	public function short_code_elseif_2( $atts, $content ) {
		return $this->parse_shortcode_elseif( 1, $atts, $content );
	}

	/**
	 * The function that is called when the third elseif shortcode hook is 
	 * fired
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	public function short_code_elseif_3( $atts, $content ) {
		return $this->parse_shortcode_elseif( 2, $atts, $content );
	}

	/**
	 * The function that is called when the fourth elseif shortcode hook is 
	 * fired
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	public function short_code_elseif_4( $atts, $content ) {
		return $this->parse_shortcode_elseif( 3, $atts, $content );
	}

	/**
	 * The function that handles the registering of child if shortcodes and 
	 * adds it to the relevant conditional collection.
	 * @param  int $instance The nested instance of the statement
	 * @param  array $atts The attributes of the shortcode
	 * @param  string $content The content between the shortcode tags
	 * @return If parent if statement doesn't exist false, otherwise if conditions
	 * are met $content or nothing.
	 */
	private function shortcode_if_child( $instance, $atts, $content ) {

		if ( ! $this->shortcode_init )
			return false;

		// Gets the current instance of the parent conditional statement
		$parent_condition_instance = count( $this->if_statement_collection ) - 1;
		$this->if_statement_collection[ $parent_condition_instance ][ $instance ] = true;

		if ( empty( $atts ) )
			return $content;

		return $this->parse_shortcode_if( $atts, $content );

	}

	/**
	 * The function that is called to handle the shortcode attribute parsing
	 * @param array $atts The attributes of the shortcode
	 * @param string $content The content between the shortcode tags
	 * @return string If conditions are met $content, otherwise false or nothing.
	 * 
	 */
	private function parse_shortcode_if( $atts, $content ) {

		/*
		 * Check if the condition is met
		 */
		if ( ! $this->parse_shortcode_attributes( $atts ) ) {
			return '';
		}

		return do_shortcode( $content );

	}

	/**
	 * The function that handles the registering of elseif shortcodes and 
	 * adds it to the relevant conditional collection.
	 * @param  int $instance The nested instance of the statement
	 * @param  array $atts The attributes of the shortcode
	 * @param  string $content The content between the shortcode tags
	 * @return If parent if statement doesn't exist false, otherwise if conditions
	 * are met $content or nothing.
	 */
	private function parse_shortcode_elseif( $instance, $atts, $content ) {

		if ( ! $this->shortcode_init )
			return false;

		// Gets the current instance of the parent conditional statement
		$parent_condition_instance = count( $this->if_statement_collection ) - 1;
		$this->if_statement_collection[ $parent_condition_instance ][ $instance ] = true;

		if ( empty( $atts ) || ! $this->if_statement_collection[ $parent_condition_instance ][ $instance ] )
			return $content;

		/*
		 * Check if the condition is met
		 */
		if ( ! $this->parse_shortcode_attributes( $atts ) ) {
			return '';
		}

		return do_shortcode( $content );
		
	}

	/**
	 * Parses the shortcode attributes and returns the relevant state
	 * @param  array $atts The attributes of the shortcode
	 * @return $condition_met The returned state of the shortcode attributes
	 */
	private function parse_shortcode_attributes( $atts ) {

		$condition_met = false;

		$match = '';

		if ( isset( $atts['match'] ) )
			$match = ( 'contain' == $atts['match'] ) ? 'contain' : 'exact';

		foreach ( $atts as $key => $value ) {

			if ( 'qs' == $key ) {
				$condition_met = $this->condition_query_string( $value, $match );
			} elseif ( 'referrer' == $key ) {
				$condition_met = $this->condition_referrer( $value, $match );
			} elseif ( 'role' == $key ) {
				$condition_met = $this->condition_user_role( $value );
			} else {
				return false;
			}

		}

		return $condition_met;

	}

	/**
	 * Returns true if specified query string parameters match specified values
	 * @param array $atts The attributes of the shortcode
	 * @return bool True if conditions are met
	 * 
	 */
	private function condition_query_string( $value, $match ) {

		if ( ! strstr( $value, ':' ) )
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
