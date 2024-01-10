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

add_action('woocommerce_add_order_item_meta','pp_add_sf_contact_data_to_order_item_meta', 20, 3 );

function pp_add_sf_contact_data_to_order_item_meta($item_id, $item_values, $item_key) {
  $custom_meta_field = '__SF_CONTACT_FULL';

  if( isset($item_values[$custom_meta_field]) )
    wc_update_order_item_meta( 
      $item_id, 
      $custom_meta_field,  
      $item_values[$custom_meta_field] ); 
}

add_action('init', function() {
  if(!isset($_GET['__debug'])) return;
  // print_r(get_field('__role-based_pricing', 'option'));
  // echo ppsf_base_Pricebook2_base_price_id();
  // echo '<pre>'; print_r(get_post_meta(19406)); echo '</pre>'; 
  // echo '<pre>'; print_r(get_post_meta(19406, 'product_role_based_price', true)); echo '</pre>'; 
  // echo '<pre>'; print_r(get_post_meta(19406, 'product_role_based_price_PRIMARY_MEMBERS', true)); echo '</pre>'; 
  // echo '<pre>'; print_r(get_post_meta(19406, 'product_role_based_price_MEMBERS', true)); echo '</pre>'; 
});

add_action('PPSF::AFTER_UPDATE_REGULAR_PRICE_PRODUCT_CHILD_EACH', 'ppsf_update_role_based_pricing', 20, 3);
add_action('PPSF:AFTER_IMPORT_VARIATION', 'ppsf_update_role_based_pricing', 20, 3);

function ppsf_update_role_based_pricing($pid, $productParentId, $prices) {
  // wp_send_json( [$pid, $productParentId, $prices] );
  $__role_based_pricing = get_field('__role-based_pricing', 'option');
  if(empty($__role_based_pricing) || count($__role_based_pricing) == 0) return;
  $data_update = [];
  // wp_send_json( $__role_based_pricing );
  foreach($__role_based_pricing as $key => $item) {
    if($item['role'] == 'regular_price') continue;
    $pricebook2 = $item['pricebook2'];

    $found_key = array_search($pricebook2, array_column($prices, 'Pricebook2Id'));
    if($found_key === false) continue;

    $UnitPrice = $prices[$found_key]['UnitPrice'];
    update_post_meta($pid, 'product_role_based_price_' . $item['role'], floatval($UnitPrice));
    
    $data_update[$item['role']] = [
      'role_price' => floatval($UnitPrice)
    ];
  }

  if(count($data_update) > 0) {
    update_post_meta($pid, 'product_role_based_price', $data_update);
  }
}

add_action('wp_head', function() {
  // $product_variation = new WC_Product_Variation(19433);
  // $regular_price = $product_variation->regular_price;
  // var_dump($regular_price); 

  // global $current_user;

  // $user_roles = $current_user->roles;
  // $current_user_role = $user_roles[0];
  // // print_r($user_roles);
  // $product_variation = new WC_Product_Variation(19433);
  // echo $product_variation->get_meta( 'product_role_based_price_' . $current_user_role );
  // $product = wc_get_product(19375);
  // $product = new WC_Product_Variation(19486);
  // echo $product->get_sku() . 'dev';
});

// add_filter('woocommerce_product_variation_get_price', function($price = '', $product = null) {
//   return $price;
// }, 100, 2);

add_filter('woocommerce_get_price_html', function($price, $_) {
  if($_->is_type( 'variable' ) == true) {
    $current_products = $_->get_available_variations();
    // print_r($current_products[0]);
    if(isset($current_products[0])) {
      return $current_products[0]['price_html'];
    }
  }
  
  return $price;
}, 999, 2);

add_action('pp/mini_cart_item_after_title', function($cart_item, $_product) {
  // echo '<pre>'; print_r($cart_item); echo '</pre>';
  if(isset($cart_item['course_information'])) {
    echo pp_woo_remaining_seats_available($cart_item['course_information']);
  }
  
}, 20, 2);