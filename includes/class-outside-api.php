<?php
/**
 * @class   OutSide_API
 * @version 1.0.0
 * @package OutSide/Classes
 */

defined( 'ABSPATH' ) || exit;

/**
 * OutSide_API class
 */
class OutSide_API {

	/**
	 * Plugin Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_endpoints' ), 0 );
		if ( ! is_admin() ) {
			add_filter('template_include',array( $this, 'event_template_include') );
			add_filter('request', array( $this, 'outside_request') );
		}
	}

	/**
	 * Set outside event true.
	 *
	 * @param array $vars
	 */
	public function outside_request($vars){
		if (isset($vars['outside-events'])) {
			$vars['outside-events'] = true;
		}
		return $vars;
	}

	/**
	 * Register Custom Endpoint.
	 */
	public function add_endpoints(){
		add_rewrite_endpoint( 'outside-events', EP_PERMALINK );
	}

	/**
	 * Template Includes.
	 *
	 * @param [type] $template
	 */
	public function event_template_include( $template ) {
		if ( get_query_var('outside-events') && is_singular() ) {
			$template = dirname( OUTSIDE_PLUGIN_FILE ) . '/templates/events-template.php';
		}
		return $template;
	}
}

new OutSide_API();