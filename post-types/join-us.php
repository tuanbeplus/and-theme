<?php

/**
 * Post Type for Join Us
 */
function register_join_us() {

    $labels = array(
        'name'                => __( 'Join Us', 'and' ),
        'singular_name'       => __( 'Join Us', 'and' ),
        'menu_name'           => __( 'Join Us', 'and' ),
        'all_items'           => __( 'All Join Us', 'and' ),
        'view_item'           => __( 'View Join Us', 'and' ),
        'add_new_item'        => __( 'Add New Join Us', 'and' ),
        'add_new'             => __( 'Add New', 'and' ),
        'edit_item'           => __( 'Edit Join Us', 'and' ),
        'update_item'         => __( 'Update Join Us', 'and' ),
        'search_items'        => __( 'Search Join Us', 'and' ),
        'not_found'           => __( 'Not found', 'and' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'and' ),
    );

    $args = array(
        'label'                => __( 'Join Us', 'and' ),
        'description'          => __( 'Join Us', 'and' ),
        'labels'               => $labels,
        'supports'             => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'taxonomies' ),
        'hierarchical'         => false,
        'public'               => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_in_nav_meacps'    => true,
        'show_in_admin_bar'    => true,
        'menu_position'        => 20,
        'menu_icon'            => 'dashicons-insert',
        'can_export'           => true,
        'has_archive'          => true,
        'exclude_from_search'  => false,
        'publicly_queryable'   => true,
        'delete_with_user'     => true,
        'has_archive'           => '',
        'taxonomies' 	       => array('types'),
        'rewrite' => array('slug' => 'join-us', 'with_front' => false),
    );

    register_post_type( 'join-us', $args );
}
add_action( 'init', 'register_join_us' );
