<?php
/**
 * OutSide AJAX Event Handlers.
 *
 * @package OutSide/Classes
 */

defined( 'ABSPATH' ) || exit;

/**
 * OUTSIDE_AJAX class.
 */
class OUTSIDE_AJAX {

	/**
	 * Hook in ajax handlers.
	 */
	public static function init() {
		$ajax_events = array(
			'event_type' => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_outside_filter_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_outside_filter_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			}
		}
	}

	/**
	 * Filter by Event Type.
	 */
	public static function event_type() {
		check_ajax_referer( 'process-ajax-nonce', 'security' );

		$tax_id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : null;

		echo '<pre>' . print_r( $tax_id, true ) . '</pre>';

	}
}

OUTSIDE_AJAX::init();