<?php 
/**
 * ACF Options 
 */

function pp_acf_op_init() {
  if( function_exists('acf_add_options_page') ) {
    $option_page = acf_add_options_page(array(
      'page_title'    => __('Theme Options', 'pp'),
      'menu_title'    => __('Theme Options', 'pp'),
      'menu_slug'     => 'theme-options',
      'capability'    => 'edit_posts',
      'redirect'      => false
    ));
  }
}

add_action('acf/init', 'pp_acf_op_init');