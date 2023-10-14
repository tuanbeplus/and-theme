<?php 
/**
 * Project pack
 */

{
  /**
   * Define
   */
  define('PP_DIR', get_stylesheet_directory() . '/project-pack/');
  define('PP_URI', get_stylesheet_directory_uri() . '/project-pack/');
  define('PP_VER', '1.0.0');
}

{
  /**
   * Inc
   */
  require(PP_DIR . '/inc/admin/woo.php');
  require(PP_DIR . '/inc/admin/calendly-poduct-type.php');
  require(PP_DIR . '/inc/event-tickets/index.php');
  // require(PP_DIR . '/inc/options.php');
  require(PP_DIR . '/inc/admin/sf-event-import/index.php');

  require(PP_DIR . '/inc/static.php');
  require(PP_DIR . '/inc/helpers.php');
  require(PP_DIR . '/inc/hooks.php');
  require(PP_DIR . '/inc/woo.php');
  require(PP_DIR . '/inc/shortcode.php');
  require(PP_DIR . '/inc/template-tags.php');
  require(PP_DIR . '/inc/ajax.php');
  require(PP_DIR . '/inc/custom-roles.php');
}

{
  /**
   * MS Outlook Calendar 
   * 
   */
  require(PP_DIR . '/inc/outlook-calendar/index.php');
}

{
  /**
   * Salesforce
   */
  require(PP_DIR . '/inc/salesforce-api.php');
  require(PP_DIR . '/inc/sf-oauth-login.php');
  require(PP_DIR . '/inc/admin/users-sf-sync.php');
} 