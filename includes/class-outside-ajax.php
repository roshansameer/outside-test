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

		$tax_id = isset( $_POST['id'] ) ? wp_unslash( $_POST['id'] ) : '';

		$args = array(
			'post_type' => 'event',
			'post_status' => 'publish',
		);

		if( ! empty( $tax_id ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event_type',
					'field' => 'term_id',
					'terms' => $tax_id
				)
			);
		}

		$query = new WP_Query( $args );

		$output = '';

		if ( ! empty( $query->posts) ) {
			foreach ($query->posts as $key => $post) {
				$output .= '<div class="category-post-item"><div class="category-single-post"><div class="cat-post-img-wrap">';
				$feature_img = get_the_post_thumbnail_url( $post->ID );
				if ( $feature_img ) {
					$output .= '<a href="'.esc_url(get_permalink( $post->ID )).'"><img src="'.$feature_img.'" alt=""></a>';
				} else {
					$output .= '<a href="'.esc_url(get_permalink( $post->ID )).'"><img src="https://images.unsplash.com/photo-1638913662539-46e7ccd6d912?ixlib=rb-1.2.1&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt=""></a>';
				}
				$output .= '</div><h2 class="cat-post-title"><a href="#">'.$post->post_title.'</a></h2></div></div>';
			}
		} else {
			$output .= __("No result found.","outside");
		}

		wp_reset_postdata();

		wp_send_json_success(array( 'posts' => $output ) );

	}
}

OUTSIDE_AJAX::init();