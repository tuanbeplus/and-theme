<?php

/**
 * Post Type for Campaign
 */
function register_campaigns() {

    $labels = array(
        'name'                => __( 'New Campaign', 'and' ),
        'singular_name'       => __( 'Campaign', 'and' ),
        'menu_name'           => __( 'Campaign', 'and' ),
        'all_items'           => __( 'All Campaign', 'and' ),
        'view_item'           => __( 'View Campaign', 'and' ),
        'add_new_item'        => __( 'Add New Campaign', 'and' ),
        'add_new'             => __( 'Add New', 'and' ),
        'edit_item'           => __( 'Edit Campaign', 'and' ),
        'update_item'         => __( 'Update Campaign', 'and' ),
        'search_items'        => __( 'Search Campaign', 'and' ),
        'not_found'           => __( 'Not found', 'and' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'and' ),
    );

    $args = array(
        'label'                => __( 'Campaign', 'and' ),
        'description'          => __( 'Campaign', 'and' ),
        'labels'               => $labels,
        'supports'             => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'taxonomies' ),
        'hierarchical'         => false,
        'public'               => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_in_nav_meacps'    => true,
        'show_in_admin_bar'    => true,
        'menu_position'        => 20,
        'menu_icon'            => 'dashicons-calendar',
        'can_export'           => true,
        'has_archive'          => true,
        'exclude_from_search'  => false,
        'publicly_queryable'   => true,
        'delete_with_user'     => true,
        'has_archive'           => '',
        'taxonomies' 	       => array('types'),
        'rewrite' => array('slug' => 'news-and-events', 'with_front' => false),
    );

    register_post_type( 'new_campaign', $args );
}
add_action( 'init', 'register_campaigns' );
