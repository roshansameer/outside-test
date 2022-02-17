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