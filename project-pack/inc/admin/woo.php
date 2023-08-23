<?php 
/**
 * Woo Admin
 */

function pp_woo_add_btn_custom_date_data_to_variations($loop, $variation_data, $variation) {
  echo '<div class="pp-product-variable-custom-field">';
  woocommerce_wp_text_input( array(
    'id' => 'start_date[' . $loop . ']',
    'class' => 'short',
    'label' => __( 'Start Date', 'pp' ),
    'type' => 'date',
    'value' => get_post_meta( $variation->ID, 'start_date', true )
  ) );

  woocommerce_wp_text_input( array(
    'id' => 'start_time[' . $loop . ']',
    'class' => 'short',
    'label' => __( 'Start Time', 'pp' ),
    'value' => get_post_meta( $variation->ID, 'start_time', true )
  ) );


  woocommerce_wp_text_input( array(
    'id' => 'end_date[' . $loop . ']',
    'class' => 'short',
    'label' => __( 'End Date', 'pp' ),
    'type' => 'date',
    'value' => get_post_meta( $variation->ID, 'end_date', true )
  ) );

  woocommerce_wp_text_input( array(
    'id' => 'end_time[' . $loop . ']',
    'class' => 'short',
    'label' => __( 'End Time', 'pp' ),
    'value' => get_post_meta( $variation->ID, 'end_time', true )
  ) );

  echo '</div>';
}

add_action( 'woocommerce_variation_options_pricing', 'pp_woo_add_btn_custom_date_data_to_variations', 50, 3 );

function pp_woo_save_date_field_variations( $variation_id, $i ) {
  $updateFields = ['start_date', 'start_time', 'end_date', 'end_time'];

  foreach($updateFields as $_idnex => $field) {
    $value = $_POST[$field][$i];
    if ( isset( $value ) ) update_post_meta( $variation_id, $field, esc_attr( $value ) );
  }
}

add_action( 'woocommerce_save_product_variation', 'pp_woo_save_date_field_variations', 10, 2 );



function pp_woo_add_date_field_variation_data( $variations ) {
  $dateFields = ['start_date', 'start_time', 'end_date', 'end_time'];
  
  foreach($dateFields as $_idnex => $field) {
    $value = get_post_meta( $variations[ 'variation_id' ], $field, true );
    $variations[$field] = $value;
  }
  return $variations;
}

add_filter( 'woocommerce_available_variation', 'pp_woo_add_date_field_variation_data' );


/**
 * Add or modify AU States
 */
add_filter( 'woocommerce_states', 'pp_custom_woocommerce_au_states' , 10);

function pp_custom_woocommerce_au_states( $states ) {

  $states['AU'] = array(
    'ACT' => __( 'ACT', 'woocommerce' ),
		'NSW' => __( 'NSW', 'woocommerce' ),
		'NT'  => __( 'NT', 'woocommerce' ),
		'QLD' => __( 'QLD', 'woocommerce' ),
		'SA'  => __( 'SA', 'woocommerce' ),
		'TAS' => __( 'TAS', 'woocommerce' ),
		'VIC' => __( 'VIC', 'woocommerce' ),
		'WA'  => __( 'WA', 'woocommerce' ),
  );

  return $states;
}