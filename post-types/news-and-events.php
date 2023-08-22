<?php

/**
 * Post Type for News and Events
 */
function register_news_and_events() {

    $labels = array(
        'name'                => __( 'News and Events', 'and' ),
        'singular_name'       => __( 'News and Events', 'and' ),
        'menu_name'           => __( 'News and Events', 'and' ),
        'all_items'           => __( 'All News and Events', 'and' ),
        'view_item'           => __( 'View News and Events', 'and' ),
        'add_new_item'        => __( 'Add New Item', 'and' ),
        'add_new'             => __( 'Add New', 'and' ),
        'edit_item'           => __( 'Edit Item', 'and' ),
        'update_item'         => __( 'Update Item', 'and' ),
        'search_items'        => __( 'Search News and Events', 'and' ),
        'not_found'           => __( 'Not found', 'and' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'and' ),
    );

    $args = array(
        'label'                => __( 'News and Events', 'and' ),
        'description'          => __( 'News and Events', 'and' ),
        'labels'               => $labels,
        'supports'             => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'taxonomies' ),
        'hierarchical'         => false,
        'public'               => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_in_nav_meacps'    => true,
        'show_in_admin_bar'    => true,
        'menu_position'        => 20,
        'menu_icon'            => 'dashicons-calendar-alt',
        'can_export'           => true,
        'has_archive'          => true,
        'exclude_from_search'  => false,
        'publicly_queryable'   => true,
        'delete_with_user'     => true,
        'has_archive'           => '',
        'taxonomies' 	       => array('types', 'news_categories'),
        'rewrite' => array('slug' => 'news-and-events', 'with_front' => false),
    );

    register_post_type( 'news_and_events', $args );
}
add_action( 'init', 'register_news_and_events' );


function news_taxonomy() {
    register_taxonomy(
        'section',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'news_and_events',             // post type name
        array(
            'hierarchical' => true,
            'label' => 'News Categories', // display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'news',    // This controls the base slug that will display before each term
                'with_front' => false  // Don't display the category base before
            )
        )
    );
}
add_action( 'init', 'news_taxonomy');
