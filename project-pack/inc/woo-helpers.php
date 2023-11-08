<?php 
/**
 * WooCommerce Helpers Functions 
 * 
 * @since 1.0.0
 * @author Mike
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

function ppwc_add_product_attr_opts($pid, $name) {
  $attributes = get_post_meta($pid, '_product_attributes', true);

  // add new item opt
  $eventOptions = explode(' | ', $attributes['events']['value']);
  array_push($eventOptions, $name);

  $attributes['events']['value'] = implode(' | ', $eventOptions);
  update_post_meta($pid, '_product_attributes', $attributes);
}

function ppwc_get_event_data_by_product_variation_id($variation_id) {
  $wp_event_parent_id = get_post_meta($variation_id, 'wp_parent_event_id', true);
  $wp_event_child_id = get_post_meta($variation_id, 'wp_child_event_id', true);

  // if(!$wp_event_parent_id && !$wp_event_child_id) return;

  return [
    'event_parent' => $wp_event_parent_id ? pp_get_event_data_by_id((int) $wp_event_parent_id) : '',
    'event_child' => $wp_event_child_id ? pp_get_event_data_by_id((int) $wp_event_child_id) : '',
  ];
}