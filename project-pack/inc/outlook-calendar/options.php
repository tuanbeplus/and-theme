<?php
/**
 * Options
 */

if( function_exists('acf_add_options_page') ) {
  acf_add_options_page([
    'page_title'    => 'MS Outlook Calendar',
    'menu_title'    => 'MS Outlook Calendar',
    'menu_slug'     => 'ms-outlook-calendar',
    'capability'    => 'edit_posts',
    'redirect'      => false
  ]);
}
