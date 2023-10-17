<?php 
/**
 * Static 
 */

function pp_enqueue_scripts() {

  # Calendly
  wp_enqueue_style('pp-calendly', 'https://assets.calendly.com/assets/external/widget.css' );
  wp_enqueue_script('pp-calendly', 'https://assets.calendly.com/assets/external/widget.js' );

  if( !wp_script_is( 'wc-cart-fragments', 'enqueued' ) && wp_script_is( 'wc-cart-fragments', 'registered' ) ) {
    // Enqueue the wc-cart-fragments script
    wp_enqueue_script( 'wc-cart-fragments' );
  }

  wp_enqueue_script( 'pp-script', PP_URI . '/dist/project-pack.main.bundle.js', ['jquery'], PP_VER, true );
  wp_enqueue_style( 'pp-style', PP_URI . '/dist/css/project-pack.main.bundle.css', false, PP_VER );

  wp_localize_script( 'pp-script', 'PP_DATA', apply_filters( 'pp/script_data', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'lang' => [],
  ] ) );
}

add_action( 'wp_enqueue_scripts', 'pp_enqueue_scripts' );

function pp_admin_enqueue_scripts() {
  wp_enqueue_script( 'pp-admin-script', PP_URI . '/dist/project-pack.admin.bundle.js', ['jquery'], PP_VER, true );
  wp_enqueue_style( 'pp-admin-style', PP_URI . '/dist/css/project-pack.admin.bundle.css', false, PP_VER );

  wp_enqueue_script( 'pp-salesforce-admin-script', PP_URI . '/dist/salesforce.admin.bundle.js', ['jquery'], PP_VER, true );
}

add_action( 'admin_enqueue_scripts', 'pp_admin_enqueue_scripts' );