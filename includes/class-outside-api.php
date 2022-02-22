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
			add_filter( 'template_include', array( $this, 'event_template_include' ) );
			add_filter( 'query_vars', array( $this, 'outside_query_vars' ) );
	}

	/**
	 * Setting query vars
	 *
	 * @param array $query_vars
	 */
	public function outside_query_vars( $query_vars ) {
		$query_vars[] = 'outside-events';
		return $query_vars;
	}

	/**
	 * Register Custom Endpoint.
	 */
	public function add_endpoints() {
		add_rewrite_rule( 'outside-events/([a-z0-9-]+)[/]?$', 'index.php?outside-events=$matches[1]', 'top' );

	}

	/**
	 * Template Includes.
	 *
	 * @param [type] $template
	 */
	public function event_template_include( $template ) {
		global $wp_query;

		if ( array_key_exists( 'outside-events', $wp_query->query_vars ) ) {
			$template = dirname( OUTSIDE_PLUGIN_FILE ) . '/templates/events-template.php';
		}
		return $template;
		// if ( get_query_var('outside-events') && is_singular() ) {
		// $template = dirname( OUTSIDE_PLUGIN_FILE ) . '/templates/events-template.php';
		// }
		// return $template;
	}
}

new OutSide_API();
