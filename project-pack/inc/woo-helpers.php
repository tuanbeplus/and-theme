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

// Added by Tap
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

// Added by Tap
function ppwc_show_gst_price($gst_rate, $p_price, $only_value = false) {
  $gst_price_html = '';
  if ( $gst_rate > 0 ) {
    $gst_price = wc_price( round((float)$p_price * $gst_rate / 100, 1) );
    if ($only_value ) {
      $gst_price_html = $gst_price;
    } else {
      $gst_price_html = '(GST: '.$gst_price.')';
    }
  }
  return $gst_price_html;
}

// Added by Tap
add_action( 'and_widget_shopping_cart_total_tax', 'ppwc_display_total_tax_in_cart' );
function ppwc_display_total_tax_in_cart() {
  if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
    $taxable_address = WC()->customer->get_taxable_address();
    $estimated_text  = '';

    if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
      $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
    }

    if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
      foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { 
        ?>
          <strong><?php echo esc_html( $tax->label ) . $estimated_text; ?>:</strong>
          <span class="woocommerce-Price-amount amount"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
        <?php
      }
    } else {
      ?>
        <strong><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></strong>
        <span class="woocommerce-Price-amount amount"><?php wc_cart_totals_taxes_total_html(); ?></span>
      <?php
    }
  }
}