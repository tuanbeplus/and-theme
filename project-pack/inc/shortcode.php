<?php 
/**
 * Shortcodes
 */

/**
 * [landing_products_card]
 */
function pp_shortcode_landing_products_card($atts, $content = "") {
  $atts = shortcode_atts( [
    'search_and_filter' => true,
		'items' => 6,
    'loadmore' => true,
  ], $atts );

  set_query_var( 'atts', $atts );

  ob_start();
  pp_load_template( 'landing-product-v2' );
  return ob_get_clean();
}

add_shortcode( 'landing_products_card', 'pp_shortcode_landing_products_card' );