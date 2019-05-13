<?php

/**
 * Registers the `movie` post type.
 */
function movie_init() {
	register_post_type( 'movie', array(
		'labels'                => array(
			'name'                  => __( 'Movies', 'wuxt' ),
			'singular_name'         => __( 'Movie', 'wuxt' ),
			'all_items'             => __( 'All Movies', 'wuxt' ),
			'archives'              => __( 'Movie Archives', 'wuxt' ),
			'attributes'            => __( 'Movie Attributes', 'wuxt' ),
			'insert_into_item'      => __( 'Insert into Movie', 'wuxt' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Movie', 'wuxt' ),
			'featured_image'        => _x( 'Featured Image', 'movie', 'wuxt' ),
			'set_featured_image'    => _x( 'Set featured image', 'movie', 'wuxt' ),
			'remove_featured_image' => _x( 'Remove featured image', 'movie', 'wuxt' ),
			'use_featured_image'    => _x( 'Use as featured image', 'movie', 'wuxt' ),
			'filter_items_list'     => __( 'Filter Movies list', 'wuxt' ),
			'items_list_navigation' => __( 'Movies list navigation', 'wuxt' ),
			'items_list'            => __( 'Movies list', 'wuxt' ),
			'new_item'              => __( 'New Movie', 'wuxt' ),
			'add_new'               => __( 'Add New', 'wuxt' ),
			'add_new_item'          => __( 'Add New Movie', 'wuxt' ),
			'edit_item'             => __( 'Edit Movie', 'wuxt' ),
			'view_item'             => __( 'View Movie', 'wuxt' ),
			'view_items'            => __( 'View Movies', 'wuxt' ),
			'search_items'          => __( 'Search Movies', 'wuxt' ),
			'not_found'             => __( 'No Movies found', 'wuxt' ),
			'not_found_in_trash'    => __( 'No Movies found in trash', 'wuxt' ),
			'parent_item_colon'     => __( 'Parent Movie:', 'wuxt' ),
			'menu_name'             => __( 'Movies', 'wuxt' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title', 'editor', 'custom-fields' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'movie',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'movie_init' );

/**
 * Sets the post updated messages for the `movie` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `movie` post type.
 */
function movie_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['movie'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Movie updated. <a target="_blank" href="%s">View Movie</a>', 'wuxt' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'wuxt' ),
		3  => __( 'Custom field deleted.', 'wuxt' ),
		4  => __( 'Movie updated.', 'wuxt' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Movie restored to revision from %s', 'wuxt' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Movie published. <a href="%s">View Movie</a>', 'wuxt' ), esc_url( $permalink ) ),
		7  => __( 'Movie saved.', 'wuxt' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Movie submitted. <a target="_blank" href="%s">Preview Movie</a>', 'wuxt' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Movie scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Movie</a>', 'wuxt' ),
		date_i18n( __( 'M j, Y @ G:i', 'wuxt' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Movie draft updated. <a target="_blank" href="%s">Preview Movie</a>', 'wuxt' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'movie_updated_messages' );
