<?php 
/**
 * Woo 
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

function pp_woo_add_custom_cart_fragments($fragments) {

  ob_start();
  pp_woo_mini_cart_tag();
  $content = ob_get_clean();

  $fragments['.pp-minicart'] = $content;
  return $fragments;
}

function pp_woo_product_minus_string_tag ($cart_item, $_product) {
  $minutes = get_field('minutes', $_product->get_id());
  if(empty($minutes)) return;
  echo '<p class="__minutes">' . sprintf(__('%s minutes', 'pp'), $minutes) . '</p>';
}

/**
 * Preare data course
 * 
 */
function pp_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

  if(!$variation_id) return $cart_item_data;

  // $start_date = get_post_meta($variation_id, 'start_date', true);
  // $start_time = get_post_meta($variation_id, 'start_time', true);
  // $end_date = get_post_meta($variation_id, 'end_date', true);
  // $end_time = get_post_meta($variation_id, 'end_time', true);

  $wp_event_parent_id = get_post_meta($variation_id, 'wp_parent_event_id', true);
  $wp_event_child_id = get_post_meta($variation_id, 'wp_child_event_id', true);

  // if(! $wp_event_parent_id && ! $start_time && ! $end_date && ! $end_time) {
  //   return $cart_item_data;
  // }

  $variation = wc_get_product($variation_id);
  $variation->get_formatted_name();

  $cart_item_data['course_information'] = [
    'name' => wp_strip_all_tags($variation->get_formatted_name()),
    'event_parent' => pp_get_event_data_by_id((int) $wp_event_parent_id),
    'event_child' => pp_get_event_data_by_id((int) $wp_event_child_id),

    // 'start_date' => $start_date,
    // 'start_time' => $start_time,
    // 'end_date' => $end_date,
    // 'end_time' => $end_time,
  ];

  // wp_send_json( $cart_item_data ); die;
  return $cart_item_data;
}

add_filter( 'woocommerce_add_cart_item_data', 'pp_add_cart_item_data', 40, 3 );

// ADD THE INFORMATION AS ORDER ITEM META DATA SO THAT IT CAN BE SEEN AS PART OF THE ORDER
function pp_add_custom_field_to_order_item_meta( $item_id, $item_values, $item_key ) {

  if( isset($item_values['course_information']) )
    wc_update_order_item_meta( 
      $item_id, 
      'course_information', 
      $item_values['course_information'] );
}

add_action('woocommerce_add_order_item_meta','pp_add_custom_field_to_order_item_meta', 9, 3 );

// add_filter( 'woocommerce_checkout_coupon_message', 'bt_rename_coupon_message_on_checkout' );

add_action( 'pp/script_data', function($data = []) {

  if(isset($_GET['invitee_uuid'])) {
    $data['calendly_return'] = true;
    $data['calendly_response'] = $_GET;
  }

  return $data;
} );

add_action( 'woocommerce_thankyou', 'pp_and_woo_auto_complete_order' );
function pp_and_woo_auto_complete_order( $order_id ) { 
  if ( ! $order_id ) {
    return;
  }

  $order = wc_get_order( $order_id );

  if( $order->has_status( 'processing' ) && $order->is_paid() ) {
    $order->update_status( 'completed' );
  }
}

add_action( 'woocommerce_after_order_itemmeta', function($item_id, $item, $product) {
  # echo '<pre>'; print_r($item['course_information']); echo '</pre>'; 
  if(!isset($item['course_information'])) return;
  ?>
  <table cellspacing="0" class="display_meta">
    <tr>
      <th>Event Parent (#<?php echo $item['course_information']['event_parent']['sf_event_id'] ?>)</th>
      <td><?php echo $item['course_information']['event_parent']['workshop_event_date_text__c'] . ' | ' . $item['course_information']['event_parent']['workshop_times__c'] ?> </td>
    </tr>
    <tr>
      <th>Event Child (#<?php echo $item['course_information']['event_child']['sf_event_id'] ?>)</th>
      <td><?php echo $item['course_information']['event_child']['workshop_event_date_text__c'] . ' | ' . $item['course_information']['event_child']['workshop_times__c'] ?> </td>
    </tr>
  </table>
  <?php
}, 10, 3 );

// add_action('init', function() {
//   global $woocommerce;
//   $items = $woocommerce->cart->get_cart();
//   echo '<pre>'; print_r($items); echo '</pre>'; 
// });