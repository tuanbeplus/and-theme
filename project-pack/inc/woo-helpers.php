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

  update_post_meta($product->get_id(), '_junction_id', $_args['junction_id']);

  return $product->get_id();
}

function ppwc_event_add_variation_product($args = [], $parent_id) {
  $default = [
    'sf_event_id' => '',
    'name' => '',
    'image_id' => 0, // attachment image id
  ];

  // add attribute for each
  $_args = wp_parse_args($args, $default);
  $opt_name = $_args['name'] . ' — ' . $_args['sf_event_id'];
  ppwc_add_product_attr_opts($parent_id, $opt_name);

  $variation = new WC_Product_Variation();
  $variation->set_parent_id($parent_id);
  $variation->set_attributes(['events' => $opt_name]);
  $variation->set_name($_args['name']);   

  $variation->save();
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