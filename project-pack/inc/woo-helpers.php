<?php 
/**
 * WooCommerce Helpers Functions 
 * 
 * @since 2.2
 * @author Mike
 */

/**
 * Create variable product
 * 
 * @param array $args
 * @return int
 */
function ppwc_event_create_variable_product($args = []) {
  $default = [
    'junction_id' => 0,
    'name' => '',
  ];

  $_args = wp_parse_args($args, $default);
  $product = new WC_Product_Variable();
  // Name and image would be enough
  $product->set_name($_args['name']);

  // Event attribute
  $attribute = new WC_Product_Attribute();
  $attribute->set_name('Events');
  $attribute->set_options([]);
  $attribute->set_position(0);
  $attribute->set_visible(1);
  $attribute->set_variation(1); // here it is
  
  $product->set_attributes([$attribute]); 
  $product->save();
  pp_log('Message: Added product variable successfully #' . $product->get_id());

  update_post_meta($product->get_id(), '_junction_id', $_args['junction_id']); 
  do_action( 'PPSF/after_add_variable_hook', $product, $product->get_id(), $_args);
  return $product->get_id();
}

/**
 * Add variation product
 * 
 * @param array $args
 * @param int $parent_id
 * @return int
 */
function ppwc_event_add_variation_product($args = [], $parent_id) {
  $default = [
    'name' => $parent_event['Subject'],
    'wp_parent_event_id' => $wp_parent_event_id, 
    'wp_child_event_id' => $wp_child_event_id
  ]; 

  // add attribute for each
  $_args = wp_parse_args($args, $default);
  $opt_name = $_args['name'];
  ppwc_add_product_attr_opts($parent_id, $opt_name);

  $variation = new WC_Product_Variation();
  $variation->set_parent_id($parent_id);
  $variation->set_attributes(['events' => $opt_name]);
  $variation->set_name($_args['name']);   

  $variation->save(); 
  pp_log('Message: Added product variation successfully #' . $variation->get_id());

  // Update meta fields
  $meta_fields = apply_filters( 'PPSF/event_import_meta_fields_filter', [
    'wp_parent_event_id' => $_args['wp_parent_event_id'],
    'wp_child_event_id' => $_args['wp_child_event_id'], 
  ], $_args); 

  foreach($meta_fields as $name => $value) {
    update_post_meta($variation->get_id(), $name, $value);
  }

  do_action( 'PPSF/after_add_variation_hook', $variation, $variation->get_id(), $_args);
  return $variation->get_id();
}

/**
 * Add product attribute options
 * 
 * @param int $pid
 * @param string $name
 */
function ppwc_add_product_attr_opts($pid, $name) {
  $attributes = get_post_meta($pid, '_product_attributes', true);

  // add new item opt
  $eventOptions = explode(' | ', $attributes['events']['value']);
  array_push($eventOptions, $name);

  $attributes['events']['value'] = implode(' | ', $eventOptions);
  update_post_meta($pid, '_product_attributes', $attributes);
}

/**
 * Get event data by product variation id
 * 
 * @param int $variation_id
 * @return array
 */
function ppwc_get_event_data_by_product_variation_id($variation_id) {
  $wp_event_parent_id = get_post_meta($variation_id, 'wp_parent_event_id', true);
  $wp_event_child_id = get_post_meta($variation_id, 'wp_child_event_id', true);

  return [
    'event_parent' => $wp_event_parent_id ? pp_get_event_data_by_id((int) $wp_event_parent_id) : '',
    'event_child' => $wp_event_child_id ? pp_get_event_data_by_id((int) $wp_event_child_id) : '',
  ];
}

/**
 * Get the Tax rate for GST
 * 
 * @return float Tax rate
 */
function ppwc_get_tax_rates_for_gst() {
  // Get all tax rates for the "Standard" tax class
  $tax_rates = WC_Tax::get_rates_for_tax_class(''); // Leave empty for "Standard" tax rates
  // Get tax rates for GST
  $gst_rate = 0;
  foreach ($tax_rates as $rate_id => $rate) {
    if ( $rate->tax_rate_name == 'GST' ) {
      $gst_rate = (float)$rate->tax_rate;
    } 
  }
  return $gst_rate;
}

/**
 * Calculator GST from product price
 * 
 * @param float $p_price  Product price
 * @return float    GST
 */
function ppwc_calculator_product_gst($p_price) {
  $gst_price = 0;
  $gst_rate = ppwc_get_tax_rates_for_gst();
  if ( isset($gst_rate) && $gst_rate > 0 ) {
    $gst_price = round((float)$p_price * $gst_rate / 100, 1);
  }
  return $gst_price;
}

/**
 * Merge Event names
 * 
 * @param string $event_name_1  Event parent name
 * @param string $event_name_2  Event child name
 * @return string The final event name
 */
function ppwc_merge_event_names($event_name_1, $event_name_2) {
  // Validate inputs
  if (empty($event_name_1) && empty($event_name_2)) {
    return ''; // Explicitly return an empty string
  } 
  elseif (!empty($event_name_1) && empty($event_name_2)) {
    return $event_name_1;
  } 
  elseif (empty($event_name_1) && !empty($event_name_2)) {
    return $event_name_2;
  }
  if ($event_name_1 === $event_name_2) {
    return $event_name_1;
  }
  // Default: combine event names
  return trim($event_name_1 . '<br>& ' . $event_name_2);
}