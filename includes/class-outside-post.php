<?php
/**
 * @class   OutSide_Post
 * @version 1.0.0
 * @package OutSide/Classes
 */

defined( 'ABSPATH' ) || exit;

/**
 * OutSide_Post class
 */
class OutSide_Post {
	/**
	 * Plugin Constructor.
	 */
	public function __construct() {
		add_action('init', array( $this, 'register_custom_taxonomy_type') );
		add_action('init', array( $this, 'register_custom_post_type') );
		add_shortcode( 'outside', array( $this, 'event_render' ) );
		add_shortcode( 'outside_filter_event', array( $this, 'event_filter' ) );
	}

	/**
	 * Event Filter
	 *
	 * @param array $atts
	 */
	public function event_filter($atts = array() ) {
		$months = array(
			'january' => 'January',
			'febuary' => 'Febuary',
			'march' => 'March',
			'april' => 'April',
			'may' => 'May',
			'june' => 'June',
			'july' => 'July',
			'august' => 'August',
			'septermber' => 'September',
			'october' => 'October',
			'november' => 'November',
			'december' => 'December'

		);
		$args = array(
			'type' => get_post_type(),
			'orderby' => 'name',
			'order' => 'ASC'
		);
		$tags = get_tags($args);


		$categories = get_categories('taxonomy=event_type&type=event');

		$output = '<div class="filter-wrapper">';
		// <!-- $output .= '<div class="month-filter"><h4>'.__('Filter by month','outside').'</h4>'; -->
		$output .= '<div class="month-filter"><label for="outside_month_filter">'.__("Filter By Month","outside").'<select id="outside_month_filter" class="outside-month-filter" multiple="multiple" style="width: 100%">';
			foreach ($months as $key => $month) {
				$output .= '<option value="'.$key.'">'.$month.'</option>';
			}

		$output .='</select></label></div></br><div class"event-type-filter"><label for="outside_event_filter">'.__("Filter By Event Type","outside").'<select id="outside_event_filter" class="outside-event-type-filter" multiple="multiple" style="width: 100%">';
			foreach ($categories as $key => $category) {
				$output .= '<option value="'.$category->term_id.'">'.$category->name.'</option>';
			}
		$output .= '</select></label></div>';

		return $output;
	}

	/**
	 * Showing the bubbles
	 *
	 * @param array $atts
	 * @return void
	 */
	public function event_render( $atts = array() ) {

		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		// override default attributes with user attributes
		$outside_atts = shortcode_atts(
			array(
				'id'          => '',
				'event_type' => '',
				'limit'        => '10',
			),
			$atts
		);

		$post_args = array(
			'showposts' => -1,
			'post_type' => 'event',
			'post_per_page' => $outside_atts['limit'],
		);

		if( ! empty( $outside_atts['event_type'] ) ) {
			$post_args['tax_query'] = array(
				array(
					'taxonomy' => 'event_type',
					'field' => 'slug',
					'terms' => $outside_atts['event_type'],
				),
			);
		}

		$query = new WP_Query( $post_args );

		$output = '<div class="outside-post-wrapper"><div class="category-posts-container">';

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
		}

		$output .= '</div></div>';

		return $output;

	}

	/**
	 * Register Custom Post Types.
	 */
	public function register_custom_post_type() {
		register_post_type('event', [
			'label' => __('Event', 'outside'),
			'public' => true,
			'menu_position' => 5,
			'menu_icon' => 'dashicons-book',
			'supports' => ['title', 'editor', 'thumbnail', 'author', 'custom-fields'],
			'show_in_rest' => true,
			'query_var' =>  true,
			'capability_type' =>  'page',
			'rewrite' => ['slug' => 'event'],
			'taxonomies' => ['event_type'],
			'labels' => [
				'singular_name' => __('Event', 'outside'),
				'add_new_item' => __('Add new event', 'outside'),
				'new_item' => __('New event', 'outside'),
				'view_item' => __('View event', 'outside'),
				'not_found' => __('No event found', 'outside'),
				'not_found_in_trash' => __('No event found in trash', 'outside'),
				'all_items' => __('All Events', 'outside'),
				'insert_into_item' => __('Insert into event', 'outside')
			],
		]);
	}

	/**
	 * Register Custom Taxonomies.
	 */
	public function register_custom_taxonomy_type() {
		register_taxonomy('event_type', ['event'], [
			'label' => __('Event Type', 'outside'),
			'hierarchical' => true,
			'rewrite' => ['slug' => 'bubble'],
			'show_admin_column' => true,
			'show_in_rest' => true,
			'labels' => [
				'singular_name' => __('event_type', 'outside'),
				'all_items' => __('All Event Types', 'outside'),
				'edit_item' => __('Edit Event', 'outside'),
				'view_item' => __('View Event', 'outside'),
				'update_item' => __('Update Event', 'outside'),
				'add_new_item' => __('Add New Event', 'outside'),
				'new_item_name' => __('New Event Name', 'outside'),
				'search_items' => __('Search Event Types', 'outside'),
				'popular_items' => __('Popular Event Types', 'outside'),
				'separate_items_with_commas' => __('Separate Event Types with comma', 'outside'),
				'choose_from_most_used' => __('Choose from most used Event Types', 'outside'),
				'not_found' => __('No Event Types found', 'outside'),
			]
		]);
	}
}

new OutSide_Post();