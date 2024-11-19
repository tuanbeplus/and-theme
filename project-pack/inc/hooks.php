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
});

/**
 * Includes GST to price at Product single and displays original price for role-based pricing.
 * 
 * @author Tuan
 */
add_filter('woocommerce_get_price_html', function($price_html, $product) {
  // Initialize price variables
  $base_price = 0;
  $original_price_html = '';

  // If the product is variable, get the price of the first variation
  if ($product->is_type('variable')) {
    $available_variations = $product->get_available_variations();
    if (!empty($available_variations) && isset($available_variations[0]['display_price'])) {
      $base_price = $available_variations[0]['display_price'];
      $original_price = $product->get_variation_regular_price('min');
    }
  } else {
    // For other products, get the regular price and the original price
    $base_price = (float) $product->get_price();
    $original_price = (float) get_post_meta(get_the_ID(), '_regular_price', true);
  }

  // Calculate GST for the base price
  $gst_price = ppwc_calculator_product_gst($base_price);
  $final_price = $base_price + $gst_price;

  // Format the role-based price with GST
  $role_based_price_html = wc_price($final_price);

  // If there's a difference between original and role-based price, display both
  if ($original_price && $original_price != $base_price) {
    $original_price_html = '<del>' . wc_price($original_price + ppwc_calculator_product_gst($original_price)) . '</del> ';
  }

  // Combine the original and role-based prices for display
  $price_html = $original_price_html . ' ' . $role_based_price_html;

  return $price_html;

}, 999, 2);

/**
 * Includes GST to price at Mini Cart items
 * 
 * @author Tuan
 */
add_filter('woocommerce_cart_item_price', function($price_html, $cart_item, $cart_item_key) {
  // Get the product object from the cart item
  $product = $cart_item['data'];
  // Initialize price variable
  $base_price = 0;

  // If the product is variable, get the price of the first variation
  if ($product->is_type('variable')) {
    $available_variations = $product->get_available_variations();
    if (!empty($available_variations) && isset($available_variations[0]['display_price'])) {
      $base_price = $available_variations[0]['display_price'];
    }
  } else {
    // For non-variable products, get the regular price
    $base_price = (float) $product->get_price();
  }

  // Calculator the GST
  $gst_price = ppwc_calculator_product_gst($base_price);

  // Format the price for WooCommerce display
  $price_html = wc_price($base_price + $gst_price);

  return $price_html;

}, 999, 3);

/**
 * Includes GST in the total price at Mini Cart
 * 
 * @author Tuan
 */
remove_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal');
add_filter('woocommerce_widget_shopping_cart_total', function($total_html) {
  // Get the cart object
  $cart = WC()->cart;

  // Get the subtotal excluding tax
  $subtotal_ex_tax = $cart->get_subtotal();

  // Calculate GST based on the subtotal
  $gst_total = ppwc_calculator_product_gst($subtotal_ex_tax);

  // Format the total with GST included
  $total_html = wc_price($subtotal_ex_tax + $gst_total);

  // Optionally add GST breakdown
  $formatted_gst = wc_price($gst_total);

  echo '<strong>Total</strong>
        <span class="total-includes-tax">'. sprintf('%s <small>(includes %s GST)</small>', $total_html, $formatted_gst) .'</span>';

}, 999);

/**
 * Includes GST in each product row's subtotal on the Cart page
 * 
 * @author Tuan
 */
add_filter('woocommerce_cart_item_subtotal', function($subtotal_html, $cart_item, $cart_item_key) {
  // Get the product price from the cart item
  $product = $cart_item['data'];
  $base_price = $cart_item['line_subtotal'] / $cart_item['quantity'];

  // Calculate GST for this product's base price
  $gst_price = ppwc_calculator_product_gst($base_price);

  // Calculate the total price including GST
  $subtotal_with_gst = wc_price(($base_price + $gst_price) * $cart_item['quantity']);

  // Format GST for display
  $formatted_gst = wc_price($gst_price * $cart_item['quantity']);

  // Return the new subtotal HTML with GST breakdown
  return $subtotal_with_gst;

}, 999, 3);


add_action('pp/mini_cart_item_after_title', function($cart_item, $_product) {
  // echo '<pre>'; print_r($cart_item); echo '</pre>';
  if(isset($cart_item['course_information'])) {
    echo pp_woo_remaining_seats_available($cart_item['course_information']);
  }
  
}, 20, 2);

/**
 * Custom Cart item Product Name with event name & datetime
 */
function ppwc_custom_cart_item_name($name, $item) {
  $product_id = $item['product_id'] ?? '';
  $variation_id = $item['variation_id'] ?? '';
  $events_data = $item['course_information'] ?? '';
  $e_parent_name = '';
  $e_child_name = '';
  $product = '';
  
  if (!empty($events_data)) {
    if (isset($events_data['event_parent'])) {
      $e_parent_name = $events_data['event_parent']['post_title'] ?? '';
    }
    if (isset($events_data['event_child'])) {
      $e_child_name = $events_data['event_child']['post_title'] ?? '';
    }
  }
  if (!empty($product_id)) {
    $product .= '<a href="'.get_the_permalink($product_id).'">'. get_the_title($product_id) .'</a>';
  }
  if (!empty($e_parent_name) || !empty($e_child_name)) {
    $product .= '<p class="event-name"><strong>Events:</strong><br> '. $e_parent_name .'<br>'. $e_child_name .'</p>';
  }

  return $product;
}
add_filter('woocommerce_cart_item_name', 'ppwc_custom_cart_item_name', 999, 2);

/**
 * Custom Order item Product Name with event name & datetime
 */
function ppwc_custom_order_item_name($name, $item) {
  $product_id = $item['product_id'] ?? '';
  $variation_id = $item['variation_id'] ?? '';
  $events_data = $item['course_information'] ?? '';
  $event_parent = $events_data['event_parent'] ?? '';
  $event_child = $events_data['event_child'] ?? '';
  $event_date = '';
  $event_time = '';
  $product = '';

  if (!empty($product_id)) {
    $product .= '<a href="'.get_the_permalink($product_id).'">'. get_the_title($product_id) .'</a>';
  }
  if (!empty($event_parent) || !empty($event_child)) {
    $product .= '<p><strong>Events:</strong></p>';
  }
  if (!empty($event_parent)) {
    $event_name = $event_parent['post_title'] ?? '';
    $event_date = $event_parent['workshop_event_date_text__c'] ?? '';
    $event_time = $event_parent['workshop_times__c'] ?? '';

    if (!empty($event_name)) {
      $product .= '<p class="event">
                    <strong>'. $event_name .'</strong><br>
                    <span class="time">'. $event_date .', '. $event_time .'<span>
                  </p>';
    }
  }
  if (!empty($event_child)) {
    $event_name = $event_child['post_title'] ?? '';
    $event_date = $event_child['workshop_event_date_text__c'] ?? '';
    $event_time = $event_child['workshop_times__c'] ?? '';

    if (!empty($event_name)) {
      $product .= '<p class="event">
                    <strong>'. $event_name .'</strong><br>
                    <span class="time">'. $event_date .', '. $event_time .'<span>
                  </p>';
    }
  }

  return $product;
}
add_filter('woocommerce_order_item_name', 'ppwc_custom_order_item_name', 999, 2);


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
function ppwc_custom_require_login_message() {
  $register_url = get_field('ecomm_register_url', 'option');
  ?>
  <div class="woocommerce-checkout">
    <div class="required-login-wrapper">
      <h3 class="heading">Please login or register.</h3>
      <a href="/login" class="btn" role="button">Login</a>
      <?php if (!empty($register_url)): ?>
        <a href="<?php echo $register_url ?>" class="btn" role="button">Register</a>
      <?php endif; ?>
    </div>
  </div>
  <?php
}
add_filter('woocommerce_checkout_must_be_logged_in_message', 'ppwc_custom_require_login_message');

// Redirect address billing edit pages to prevent access
add_action('template_redirect', function() {
  if ($_SERVER['REQUEST_URI'] == '/my-account/edit-address/billing/') {
    wp_redirect(wc_get_account_endpoint_url('dashboard'));
    exit;
  }
});

