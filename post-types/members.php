<?php

/**
 * Post Type for Members Salesforce
 */
function register_members_post_type() {

    $labels = array(
        'name'                => __( 'Members SF', 'and' ),
        'singular_name'       => __( 'Members', 'and' ),
        'menu_name'           => __( 'Members SF', 'and' ),
        'all_items'           => __( 'All Members', 'and' ),
        'view_item'           => __( 'View Members', 'and' ),
        'add_new_item'        => __( 'Add New Member', 'and' ),
        'add_new'             => __( 'Add New', 'and' ),
        'edit_item'           => __( 'Edit Member', 'and' ),
        'update_item'         => __( 'Update Member', 'and' ),
        'search_items'        => __( 'Search Members', 'and' ),
        'not_found'           => __( 'Not found', 'and' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'and' ),
    );

    $args = array(
        'label'                => __( 'Members', 'and' ),
        'description'          => __( 'Members', 'and' ),
        'labels'               => $labels,
        'supports'             => array( 'title', 'author', 'thumbnail', 'taxonomies' ),
        'hierarchical'         => false,
        'public'               => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_in_nav_meacps'    => true,
        'show_in_admin_bar'    => true,
        'menu_position'        => 15,
        'menu_icon'            => 'dashicons-groups',
        'can_export'           => true,
        'has_archive'          => true,
        'exclude_from_search'  => true,
        'publicly_queryable'   => true,
        'delete_with_user'     => true,
        'has_archive'           => '',
    );

    register_post_type( 'members', $args );
}
add_action( 'init', 'register_members_post_type' );
