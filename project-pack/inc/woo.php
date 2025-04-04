<?php 
/**
 * Woo 
 * 
 * @version 2.2
 * @since 2.2
 */

/**
 * Add custom cart fragments
 * 
 * @param array $fragments
 * @return array
 */
function pp_woo_add_custom_cart_fragments($fragments) {
  ob_start();
  pp_woo_mini_cart_tag();
  $content = ob_get_clean();

  $fragments['.pp-minicart'] = $content;
  return $fragments;
}

/**
 * Add product minutes tag
 * 
 * @param array $cart_item
 * @param object $product
 */
function pp_woo_product_minus_string_tag ($cart_item, $_product) {
  $minutes = get_field('minutes', $_product->get_id());
  if(empty($minutes)) return;
  echo '<p class="__minutes">' . sprintf(__('%s minutes', 'pp'), $minutes) . '</p>';
}

/**
 * Preare data course information
 * 
 * @param array $cart_item_data
 * @param int $product_id
 * @param int $variation_id
 * @return array
 */
function pp_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

  if(!$variation_id) return $cart_item_data;

  $wp_event_parent_id = get_post_meta($variation_id, 'wp_parent_event_id', true);
  $wp_event_child_id = get_post_meta($variation_id, 'wp_child_event_id', true);

  $variation = wc_get_product($variation_id);
  $variation->get_formatted_name();

  $cart_item_data['course_information'] = [
    'name' => $variation->get_name(),
    'event_parent' => pp_get_event_data_by_id((int) $wp_event_parent_id),
    'event_child' => pp_get_event_data_by_id((int) $wp_event_child_id), 
  ];

  return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'pp_add_cart_item_data', 40, 3 );

/**
 * Add custom field to order item meta
 * 
 * @param int $item_id
 * @param array $item_values
 * @param int $item_key
 */
function pp_add_custom_field_to_order_item_meta( $item_id, $item_values, $item_key ) {
  if( isset($item_values['course_information']) )
    wc_update_order_item_meta( 
      $item_id, 
      'course_information', 
      $item_values['course_information'] );
}
add_action('woocommerce_add_order_item_meta','pp_add_custom_field_to_order_item_meta', 9, 3 );


add_action( 'pp/script_data', function($data = []) {

  if(isset($_GET['invitee_uuid'])) {
    $data['calendly_return'] = true;
    $data['calendly_response'] = $_GET;
  }

  return $data;
} );

/**
 * Hook to auto complete order to push remaining seats to Salesforce
 * 
 * @param int $order_id
 */
add_action( 'woocommerce_thankyou', 'pp_and_woo_auto_complete_order' );
function pp_and_woo_auto_complete_order( $order_id ) { 
  if ( ! $order_id ) {
    return;
  }
  $order = wc_get_order( $order_id );
  $_updated_remaining_seats = get_post_meta($order_id, '_updated_remaining_seats', true);
  if($_updated_remaining_seats != 'yes') {
    foreach ($order->get_items() as $item_id => $item_obj) {
      $__SF_CONTACT_FULL = wc_get_order_item_meta( $item_id, '__SF_CONTACT_FULL', true );
      $course_information = wc_get_order_item_meta( $item_id, 'course_information', true );
  
      // qty
      $qty = $item_obj->get_quantity();
  
      if(!isset($course_information['event_parent']['sf_event_id'])) continue;
      $parent_eventID = $course_information['event_parent']['sf_event_id'];

      // Update Remaining_Seats at parent event
      pp_update_wp_event_push_Remaining_Seats__c($qty, $parent_eventID);

      $child_eventID = $course_information['event_child']['sf_event_id'] ?? '';
      if (!empty($child_eventID)) {
        // Update Remaining_Seats at child event
        pp_update_wp_event_push_Remaining_Seats__c($qty, $child_eventID);
      }

      update_post_meta($order_id, '_updated_remaining_seats', 'yes');
    }
  }

  if( $order->has_status( 'processing' ) && $order->is_paid() ) {
    $order->update_status( 'completed' );
  }
}

/**
 * Get attendees by order
 * 
 * @param int $order_id
 * @return array
 */
function pp_get_attendees_by_order($order_id) {
  $__ATTENDEES = get_post_meta($order_id, '__ATTENDEES', true);
  return empty($__ATTENDEES) ? [] : array_values($__ATTENDEES);
}

/**
 * Save attendees to order
 * 
 * @param int $order_id
 * @param array $data
 */
function pp_save_attendees_to_order($order_id, $data = []) {
  update_post_meta($order_id, '__ATTENDEES', $data);
}

/**
 * Add attendees to order by item data
 * 
 * @param int $order_id
 * @param array $item_data
 */
function pp_add_attendees_order($order_id, $item_data = []) {
  // $order = wc_get_order( $order_id );
  $__ATTENDEES = pp_get_attendees_by_order($order_id);
  array_push($__ATTENDEES, $item_data);
  update_post_meta($order_id, '__ATTENDEES', array_values($__ATTENDEES));
}

/**
 * Remove attendees from order by attendees id
 * 
 * @param int $order_id
 * @param string $attendees_id
 */
function pp_remove_attendees_order($order_id, $attendees_id) {
  $__ATTENDEES = pp_get_attendees_by_order($order_id);
  $found_key = array_search($attendees_id, array_column($__ATTENDEES, 'relation_id'));
  $found_key_child = array_search($attendees_id, array_column($__ATTENDEES, 'relation_id_child'));
  if($found_key === false && $found_key_child === false) return; 
  if($found_key !== false) {
    unset($__ATTENDEES[$found_key]);
  }
  if($found_key_child !== false) {
    unset($__ATTENDEES[$found_key_child]);
  }
  update_post_meta($order_id, '__ATTENDEES', array_values($__ATTENDEES));
}

/**
 * Get attendees order by email
 * 
 * @param int $order_id
 * @param string $email
 * @return array
 */
function pp_get_attendees_order_by_email($order_id, $email) {
  $__ATTENDEES = pp_get_attendees_by_order($order_id);
  $found_key = array_search($email, array_column($__ATTENDEES, 'email'));
  return $found_key === false ? [] : $__ATTENDEES[$found_key];
}

/**
 * Add event information to order item meta
 * 
 * @param int $item_id
 * @param array $item
 * @param object $product
 */
add_action( 'woocommerce_after_order_itemmeta', function($item_id, $item, $product) {
  if(!isset($item['course_information'])) return;
  ?>
  <table cellspacing="0" class="display_meta">
    <?php if(!empty($item['course_information']['event_parent'])): ?>
    <tr>
      <th>Event Parent (#<?php echo $item['course_information']['event_parent']['sf_event_id'] ?? '' ?>)</th>
      <td><?php echo $item['course_information']['event_parent']['workshop_event_date_text__c'] . ' | ' . $item['course_information']['event_parent']['workshop_times__c'] ?> </td>
    </tr>
    <?php endif; ?>
    <?php if(!empty($item['course_information']['event_child'])): ?>
    <tr>
      <th>Event Child (#<?php echo $item['course_information']['event_child']['sf_event_id'] ?? '' ?>)</th>
      <td><?php echo $item['course_information']['event_child']['workshop_event_date_text__c'] . ' | ' . $item['course_information']['event_child']['workshop_times__c'] ?> </td>
    </tr>
    <?php endif; ?>
  </table>
  <?php
}, 10, 3 );

/**
 * Checkout custom
 */
add_action('woocommerce_before_checkout_form', 'ppwc_step_checkout_bar');

function ppwc_step_checkout_bar() {
  pp_load_template('step-checkout-bar');
}

// add_action('woocommerce_before_checkout_form', 'ppwc_step_add_seats_contact_form');

function ppwc_step_add_seats_contact_form() {
  pp_load_template('add-seats-contact-form');
}
/**
 * End checkout custom
 */

add_action('woocommerce_thankyou', 'pp_form_add_attendees_to_order', 90);
add_action('woocommerce_view_order', 'pp_form_add_attendees_to_order', 90);

function pp_form_add_attendees_to_order($order_id) {
  $order = wc_get_order( $order_id );
  
  // completed
  if (!$order->has_status('completed')) return;
  echo do_shortcode('[add_attendees_to_order order_id='. $order_id .']');
}

/**
 * Customize Order Confirmation Email for Workshop products
 */
add_action('woocommerce_email_customer_details', function($order, $sent_to_admin, $plain_text, $email) {
  // Get the order items
  $items = $order->get_items();
  // Flag to determine if any item belongs to the 'workshops' category
  $has_workshop_product = false;
  // Loop through each item in the order
  if (!empty($items)) {
    foreach ($items as $item) {
      // Get the product object
      $product = $item->get_product();
      // Check if the product is a variation
      if ($product->is_type('variation')) {
        // Get the parent product ID
        $parent_id = $product->get_parent_id();
        // Get the parent product object
        $parent_product = wc_get_product($parent_id);
      } 
      else {
        $parent_product = $product;
      }
      // Check if the product belongs to the 'workshops' category
      if (has_term('workshops', 'product_cat', $parent_product->get_id())) {
        $has_workshop_product = true;
        break;
      }
    }
  }
  // If there's at least one product from the 'workshops' category, print the HTML
  if ($has_workshop_product) {
    $view_order_url = $order->get_view_order_url();
    ?>
    <div style="margin: 15px 0;">
      <a href="<?php echo $view_order_url; ?>" style="color: white; font-size: 16px; 
        background: #6e3685; display: block; padding: 10px 30px; 
        border-radius: 3px; text-decoration: none; text-align: center;">
        <?php _e('You can add attendees here', 'pp'); ?>
      </a>
    </div>
    <?php
  }
}, 60, 4);  

// add_action( 'woocommerce_view_order', 'and_woo_after_account_orders_action' );
function and_woo_after_account_orders_action( $order_id ){
  $order = new WC_Order( $order_id );
  $items = $order->get_items(); 

  foreach ($items as $item) {
    // Get the product object
    $product = $item->get_product();
    // Check if the product is a variation
    if ($product->is_type('variation')) {
      // Get the parent product ID
      $parent_id = $product->get_parent_id();
      // Get the parent product object
      $parent_product = wc_get_product($parent_id);
    } 
    else {
      $parent_product = $product;
    }
    echo "<pre>";
    // Check if the product belongs to the 'workshops' category
    if (has_term('workshops', 'product_cat', $parent_product->get_id())) {
      echo 'true';
      break;
    }
    echo "</pre>";
  }
  
}

function ppsf_base_Pricebook2_base_price_id() {
  $__role_based_pricing = get_field('__role-based_pricing', 'option');
  $role = 'regular_price';
  $default = '01s28000006rWeWAAU'; // Standard Price Book

  if(!$__role_based_pricing || count($__role_based_pricing) == 0) return $default;

  $found_key = array_search($role, array_column($__role_based_pricing, 'role'));
  if($found_key === false) return $default;

  return $__role_based_pricing[$found_key]['pricebook2'];
}

/**
 * set product price
 * 
 * @param number $productParentId
 * @param array $prices
 */
function ppsf_set_product_price($productParentId = 0, $prices = []) {
  if(!$productParentId) return;
  $args = $visible_only_args = array( 
    'post_parent' => (int) $productParentId, 
    'post_type'   => 'product_variation', 
    'orderby'     => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ), 
    'fields'      => 'ids', 
    'post_status' => 'publish', 
    'numberposts' => -1, 
  );

  $Pricebook2_base_price_id = ppsf_base_Pricebook2_base_price_id();
  $found_key = array_search($Pricebook2_base_price_id, array_column($prices, 'Pricebook2Id'));
  if($found_key === false) return;
  $_regular_price = floatval($prices[$found_key]['UnitPrice']);
  // wp_send_json($prices);

  $products = get_children($args);
  if($products && count($products) > 0) {
    foreach($products as $_index => $pid) {
      // $product_variation = new WC_Product_Variation($pid);
      update_post_meta($pid, '_regular_price', $_regular_price);
      do_action('PPSF::AFTER_UPDATE_REGULAR_PRICE_PRODUCT_CHILD_EACH', $pid, (int) $productParentId, $prices);
    }
  } 
  
  wp_send_json([
    'success' => $productParentId,
    'data' => $products,
  ]);
}

function pp_woo_remaining_seats_available($event) {
  $total_number_of_seats__c = $event['event_parent']['total_number_of_seats__c'];
  $remaining_seats__c = $event['event_parent']['remaining_seats__c'];
  
  if(!$remaining_seats__c) {
    $remaining_seats__c = $total_number_of_seats__c;
  }

  ob_start();
  ?>
  <span class="__remaining-seats"> 
    <?php echo sprintf(__('(Remaining Seats %s/%s)', 'pp'), (int)$remaining_seats__c, (int)$total_number_of_seats__c); ?>
  </span> <!-- .__remaining-seats -->
  <?php
  return ob_get_clean();
}

add_action( 'woocommerce_variation_header', function($variation, $loop) {
  $wp_parent_event_id = get_post_meta( $variation->ID, 'wp_parent_event_id', true );
  if(!empty($wp_parent_event_id)) {
    $startdatetime = get_field('startdatetime', $wp_parent_event_id);
    $date = new DateTime($startdatetime);
    $startTimestamp = $date->getTimestamp();
    $currentTimestamp = current_time('timestamp');
    $isUpcoming = ($startTimestamp < $currentTimestamp ? false : true);

    echo sprintf('<span style="'. ($isUpcoming ? 'color: green;' : 'color: gray;') .'" title="%s">[%s]</span>', $startdatetime, ($isUpcoming ? 'UPCOMING' : 'OLD EVENT'));
  }
  
}, 20, 2 );

// Hide the WooCommerce "product" post type from search results
add_filter('register_post_type_args', 'pp_hide_woocommerce_products_from_search', 10, 2);
function pp_hide_woocommerce_products_from_search($args, $post_type) {
  if ($post_type === 'product') {
    $args['exclude_from_search'] = true;
  }
  return $args;
}

/**
 * Update billing data in user meta before checkout page
 */
function pp_update_billing_data_before_checkout( $checkout ) {
  // Get the current user
  $user_id = get_current_user_id();
  if ( $user_id > 0 ) {
    $sf_user_id = get_user_meta($user_id, '__salesforce_user_id', true);
    $sf_account_id = get_user_meta($user_id, '__salesforce_account_id', true);

    $sf_user_data = ppsf_get_user($sf_user_id) ?? array();
    $sf_org_data = ppsf_get_account($sf_account_id) ?? array();

    if (!empty($sf_user_data)) {
      $first_name = $sf_user_data['FirstName'] ?? '';
      $last_name = $sf_user_data['LastName'] ?? '';
      // Update billing First Name
      if (!empty($first_name)) {
        $updated = update_user_meta( $user_id, 'billing_first_name', $first_name );
      }
      // Update billing Last Name
      if (!empty($last_name)) {
        $updated = update_user_meta( $user_id, 'billing_last_name', $last_name );
      }
    }
    if (!empty($sf_org_data)) {
      $org_name = $sf_org_data['Name'] ?? '';
      // Update billing Company Name
      if (!empty($org_name)) {
        $updated = update_user_meta( $user_id, 'billing_company', $org_name );
      }
    }
  }
}
add_action( 'woocommerce_checkout_init', 'pp_update_billing_data_before_checkout' );

/**
 * Customize Billing details form fields on Checkout page
 * 
 * @param array $fields
 * @return array
 */
function pp_custom_woocommerce_checkout_fields( $fields ) {
  // Change the fields
  $fields['billing']['billing_company']['label'] = 'Company (Organisation Name)';
  $fields['billing']['billing_company']['required'] = true; // Make it required to remove "(optional)"
  $fields['billing']['billing_first_name']['custom_attributes'] = array('readonly' => 'readonly');
  $fields['billing']['billing_last_name']['custom_attributes'] = array('readonly' => 'readonly');
  $fields['billing']['billing_company']['custom_attributes'] = array('readonly' => 'readonly');

  return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'pp_custom_woocommerce_checkout_fields' );

/**
 * Customize Thank you page
 * 
 * @param int $order_id
 */
function pp_custom_woo_thankyou_order_received($order_id) {
  // Get shop landing page link
  $shop_landing_page = get_field('shop_landing_page', 'option');
  $shop_landing_page_link = !empty($shop_landing_page) ? $shop_landing_page : '/shop';
  // Add button 
  echo '<a id="btn-back-to-shop" href="'. $shop_landing_page_link .'">
          Back to Learning Solutions
        </a>';
}
add_action('woocommerce_before_thankyou', 'pp_custom_woo_thankyou_order_received', 10, 1);

