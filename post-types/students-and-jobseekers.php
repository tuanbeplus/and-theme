<?php

/**
 * Post Type for Students Jobseekers
 */
function register_students_jobseekers() {

    $labels = array(
        'name'                => __( 'Students Jobseekers', 'and' ),
        'singular_name'       => __( 'Students Jobseekers', 'and' ),
        'menu_name'           => __( 'Students Jobseekers', 'and' ),
        'all_items'           => __( 'All Students Jobseekers', 'and' ),
        'view_item'           => __( 'View Students Jobseekers', 'and' ),
        'add_new_item'        => __( 'Add New Students Jobseekers', 'and' ),
        'add_new'             => __( 'Add New', 'and' ),
        'edit_item'           => __( 'Edit Students Jobseekers', 'and' ),
        'update_item'         => __( 'Update Students Jobseekers', 'and' ),
        'search_items'        => __( 'Search Students Jobseekers', 'and' ),
        'not_found'           => __( 'Not found', 'and' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'and' ),
    );

    $args = array(
        'label'                => __( 'Students Jobseekers', 'and' ),
        'description'          => __( 'Students Jobseekers', 'and' ),
        'labels'               => $labels,
        'supports'             => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'taxonomies' ),
        'hierarchical'         => false,
        'public'               => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_in_nav_meacps'    => true,
        'show_in_admin_bar'    => true,
        'menu_position'        => 20,
        'menu_icon'            => 'dashicons-block-default',
        'can_export'           => true,
        'has_archive'          => true,
        'exclude_from_search'  => false,
        'publicly_queryable'   => true,
        'delete_with_user'     => true,
        'has_archive'           => '',
        'taxonomies' 	       => array('types'),
        'rewrite' => array('slug' => 'students-jobseekers', 'with_front' => false),
    );

    register_post_type( 'students-jobseekers', $args );
}
add_action( 'init', 'register_students_jobseekers' );
