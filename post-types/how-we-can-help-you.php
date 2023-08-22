<?php

/**
 * Post Type for How We Can Help You
 */
function register_how_we_can_help_you() {

    $labels = array(
        'name'                => __( 'How We Can Help You', 'and' ),
        'singular_name'       => __( 'How We Can Help You', 'and' ),
        'menu_name'           => __( 'How We Can Help You', 'and' ),
        'all_items'           => __( 'All How We Can Help You', 'and' ),
        'view_item'           => __( 'View How We Can Help You', 'and' ),
        'add_new_item'        => __( 'Add New Item', 'and' ),
        'add_new'             => __( 'Add New', 'and' ),
        'edit_item'           => __( 'Edit Item', 'and' ),
        'update_item'         => __( 'Update Item', 'and' ),
        'search_items'        => __( 'Search How We Can Help You', 'and' ),
        'not_found'           => __( 'Not found', 'and' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'and' ),
    );

    $args = array(
        'label'                => __( 'How We Can Help You', 'and' ),
        'description'          => __( 'How We Can Help You', 'and' ),
        'labels'               => $labels,
        'supports'             => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'taxonomies' ),
        'hierarchical'         => false,
        'public'               => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_in_nav_meacps'    => true,
        'show_in_admin_bar'    => true,
        'menu_position'        => 20,
        'menu_icon'            => 'dashicons-editor-help',
        'can_export'           => true,
        'has_archive'          => true,
        'exclude_from_search'  => false,
        'publicly_queryable'   => true,
        'delete_with_user'     => true,
        'has_archive'           => '',
        'taxonomies' 	       => array('types'),
        'rewrite' => array('slug' => 'how-we-can-help-you', 'with_front' => false),
    );

    register_post_type( 'how_we_can_help_you', $args );
}
add_action( 'init', 'register_how_we_can_help_you' );
