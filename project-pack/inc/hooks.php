<?php
/**
 * Hooks
 */

add_action( 'pp/shop_landing_sidebar', 'pp_shoplanding_sidebar_tag' );
add_action( 'pp/shoplanding-sidebar-item', 'pp_shoplanding_widget_on_this_page_tag' );

add_action( 'init', 'sf_oauth_login');

add_action( 'wp_footer', 'pp_offcanvas_tag' );
add_action( 'pp/offcanvas-item', 'pp_woo_mini_cart_tag' );
add_action( 'pp/mini_cart_item_before_title', function($cart_item, $product) {
  pp_product_first_term_name_tag($product, '<p><strong>%s</strong></p>');
}, 20, 2 );

add_action('after_setup_theme', function () {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
});

/**
 * WOO HOOKS
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'pp_woo_add_custom_cart_fragments' );
add_action( 'pp/product_single_sidebar', 'pp_product_single_widget_on_this_page_tag' );
add_action( 'pp/product_single_sidebar', 'pp_product_single_widget_buy_tag' );
add_action( 'pp/single_product_widget_by_end', 'pp_product_button_add_to_cart_tag' );
add_action( 'pp/product_single_after_content', 'pp_product_variable_choose_options_tag' );
add_filter( 'woocommerce_product_add_to_cart_text', 'pp_custom_addtocart_button_text', 20, 2 );
add_action( 'pp/product_single_end', 'pp_product_button_add_to_cart_tag', 20 );
add_action( 'pp/product_single_end', 'pp_button_back_to_shoplanding_tag', 22 );
add_action( 'pp/mini_cart_item_after_title', 'pp_woo_product_minus_string_tag', 22, 2 );

/**
 * Redirect
 */
add_action( 'wp_head', function() {
  if (function_exists('is_shop')):
    if( is_shop() ):
      $shopLanding = get_field('shop_landing_page', 'option');
      wp_redirect($shopLanding);
    endif;
  endif;
} );


