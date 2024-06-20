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
// add_action( 'pp/product_single_end', 'pp_product_button_add_to_cart_tag', 20 );
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

  foreach($__role_based_pricing as $key => $item) {
    if($item['role'] == 'regular_price') continue;
    $pricebook2 = $item['pricebook2'];
    if (is_array($prices) && !empty($prices)) {
      if ($pricebook2 !== null) {
        $found_key = array_search($pricebook2, array_column($prices, 'Pricebook2Id'));
      }
    }
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

add_filter('woocommerce_cart_item_name', 'pp_woo_cart_item_name', 90, 2);
add_filter('woocommerce_order_item_name', 'pp_woo_order_item_name', 90, 2);

function pp_woo_order_item_name($name, $item) {
  $product_id = $item['product_id'];
  return sprintf('<a href="%s">%s<a/>', get_the_permalink($product_id), get_the_title($product_id)) ;
}

function pp_woo_cart_item_name($name, $item) {
  $product_id = $item['product_id'] ?? '';
  $variation_id = $item['variation_id'] ?? '';
  $event_name = $item['variation']['attribute_events'] ?? '';

  if (!empty($product_id)) {
    $product = '<a href="'.get_the_permalink($product_id).'">'. get_the_title($product_id) .'</a>';
    if (!empty($variation_id) && !empty($event_name)) {
      $product .= '<br><strong>Event:</strong> '. $event_name;
    }
  }
  return $product;
}

// Remove variations of a product from the cart before checkout
function remove_product_variations_before_checkout() {

  // Check if it's the checkout page
  if (is_checkout()) {
    $item_removed_arr = array();

    // Get the cart
    $cart = WC()->cart;
    $cart_contents = $cart->get_cart();

    // Loop through cart items
    foreach ( $cart_contents as $cart_item_key => $cart_item ) {
      
      // Get SF event start date & convert to strtotime
      $sf_event_start_date = $cart_item['course_information']['event_parent']['startdatetime'] ?? '';
      $sf_event_start_date = strtotime($sf_event_start_date);
      $strtotime_now = strtotime('now');
      
      // Find & remove out date Events
      if (!empty($sf_event_start_date) && $sf_event_start_date < $strtotime_now) {
        // get event title
        $sf_event_title = $cart_item['course_information']['event_parent']['post_title'];

        // Remove the variation from the cart
        $cart->remove_cart_item( $cart_item_key );

        // Push items removed to an array
        $item_removed_arr[] = $sf_event_title;
      }
    }

    // Show removed items message
    if (!empty($item_removed_arr)) {
      $message  = '<div class="removed-items-message">';
      $message .= '<p>The out-dated events have been removed from the Cart:</p>';
      $message .= '<ul>';
      foreach ($item_removed_arr as $item_title)  {
        $message .= '<li style="margin-top:8px;">'. $item_title .'</li>';
      }
      $message .= '</ul>';
      $message .= '</div>';

      // Empty Cart
    if ($cart->is_empty()) {
      $message .= '<div class="empty-cart-message" style="margin-top:30px;">';
      $message .= '<p>Your cart is currently empty.</p>';
      $message .= '<p><a class="button" href="' . esc_url(wc_get_page_permalink('shop')) . '">Return to Shop</a></p>';
      $message .= '</div>';
    }

      // Display a success notice
      wc_add_notice($message, 'success');
    }
  }
}
add_action('wp_head', 'remove_product_variations_before_checkout');

// Custom required login or register in Checkout page
function and_custom_checkout_login_message() {
  $register_url = get_field('ecomm_register_url', 'option');
  ?>
  <div class="required-login-wrapper">
    <h3 class="heading">Please login or register to checkout.</h3>
    <a href="/login" class="btn" role="button">Login</a>
    <?php if (!empty($register_url)): ?>
      <a href="<?php echo $register_url ?>" class="btn" role="button">Register</a>
    <?php endif; ?>
  </div>
  <?php
}
add_filter('woocommerce_checkout_must_be_logged_in_message', 'and_custom_checkout_login_message');
