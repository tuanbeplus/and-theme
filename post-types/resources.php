<?php

/**
 * Post Type for Resources
 */
function register_resources() {
    $slug = 'resources';

    $labels = array(
        'name'                => __( 'Resources', 'and' ),
        'singular_name'       => __( 'Resources', 'and' ),
        'menu_name'           => __( 'Resources', 'and' ),
        'all_items'           => __( 'All Resources', 'and' ),
        'view_item'           => __( 'View Resources', 'and' ),
        'add_new_item'        => __( 'Add New Resource', 'and' ),
        'add_new'             => __( 'Add New', 'and' ),
        'edit_item'           => __( 'Edit Resource', 'and' ),
        'update_item'         => __( 'Update Resource', 'and' ),
        'search_items'        => __( 'Search Resources', 'and' ),
        'not_found'           => __( 'Not found', 'and' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'and' ),
    );

    $args = array(
        'label'                => __( 'Resources', 'and' ),
        'description'          => __( 'Resources', 'and' ),
        'labels'               => $labels,
        'supports'             => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'taxonomies' ),
        'hierarchical'         => false,
        'public'               => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_in_nav_meacps'    => true,
        'show_in_admin_bar'    => true,
        'menu_position'        => 20,
        'menu_icon'            => 'dashicons-open-folder',
        'can_export'           => true,
        'has_archive'          => true,
        'exclude_from_search'  => false,
        'publicly_queryable'   => true,
        'delete_with_user'     => true,
        'has_archive'           => '',
        'taxonomies' 	       => array('types'),
        'rewrite' => array('slug' => 'resources', 'with_front' => false),
    );

    register_post_type( 'resources', $args );
}
add_action( 'init', 'register_resources' );
