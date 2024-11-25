<?php 
/**
 * Woo Admin
 */

function pp_woo_add_btn_custom_date_data_to_variations($loop, $variation_data, $variation) {
  echo '<div class="pp-product-variable-custom-field">';
  // woocommerce_wp_text_input( array(
  //   'id' => 'start_date[' . $loop . ']',
  //   'class' => 'short',
  //   'label' => __( 'Start Date', 'pp' ),
  //   'type' => 'date',
  //   'value' => get_post_meta( $variation->ID, 'start_date', true )
  // ) );

  // woocommerce_wp_text_input( array(
  //   'id' => 'start_time[' . $loop . ']',
  //   'class' => 'short',
  //   'label' => __( 'Start Time', 'pp' ),
  //   'value' => get_post_meta( $variation->ID, 'start_time', true )
  // ) );


  // woocommerce_wp_text_input( array(
  //   'id' => 'end_date[' . $loop . ']',
  //   'class' => 'short',
  //   'label' => __( 'End Date', 'pp' ),
  //   'type' => 'date',
  //   'value' => get_post_meta( $variation->ID, 'end_date', true )
  // ) );

  // woocommerce_wp_text_input( array(
  //   'id' => 'end_time[' . $loop . ']',
  //   'class' => 'short',
  //   'label' => __( 'End Time', 'pp' ),
  //   'value' => get_post_meta( $variation->ID, 'end_time', true )
  // ) );

  $wp_parent_event_id = get_post_meta( $variation->ID, 'wp_parent_event_id', true );
  $wp_child_event_id = get_post_meta( $variation->ID, 'wp_child_event_id', true );
  $e_parent = pp_get_event_data_by_id($wp_parent_event_id);
  $e_child = pp_get_event_data_by_id($wp_child_event_id);

  woocommerce_wp_text_input( array(
    'id' => 'wp_parent_event_id[' . $loop . ']',
    'class' => 'short hidden',
    'label' => __( 'SF Event Parent ID', 'pp' ) . ' ' . (isset($e_parent['sf_event_id']) ? $e_parent['sf_event_id'] : ''),
    'value' => $wp_parent_event_id
  ) );

  if ( $wp_child_event_id ) {
    woocommerce_wp_text_input( array(
      'id' => 'wp_child_event_id[' . $loop . ']',
      'class' => 'short hidden',
      'label' => __( 'SF Event Children ID', 'pp' ) . ' ' . (isset($e_child['sf_event_id']) ? $e_child['sf_event_id'] : ''),
      'value' => $wp_child_event_id
    ) );
  }

  echo '</div>';
}

add_action( 'woocommerce_variation_options_pricing', 'pp_woo_add_btn_custom_date_data_to_variations', 50, 3 );

function pp_woo_save_date_field_variations( $variation_id, $i ) {
  // $updateFields = ['start_date', 'start_time', 'end_date', 'end_time'];
  $updateFields = ['wp_parent_event_id', 'wp_child_event_id'];

  foreach($updateFields as $_idnex => $field) {
    $value = $_POST[$field][$i];
    if ( isset( $value ) ) update_post_meta( $variation_id, $field, esc_attr( $value ) );
  }
}

add_action( 'woocommerce_save_product_variation', 'pp_woo_save_date_field_variations', 10, 2 );

function pp_woo_add_date_field_variation_data( $variations ) {
  // $dateFields = ['start_date', 'start_time', 'end_date', 'end_time'];
  $dateFields = ['wp_parent_event_id', 'wp_child_event_id'];
  
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

function woo_order_add_attendees_custom_box() {
	$screens = [ 'shop_order' ];
	foreach ( $screens as $screen ) {
		add_meta_box(
			'add_attendees_id',                 // Unique ID
			'Add Attendees',                    // Box title
			'woo_order_add_attendees_html',      // Content callback, must be of type callable
			$screen                             // Post type
		);
	}
}
add_action( 'add_meta_boxes', 'woo_order_add_attendees_custom_box' );

function woo_order_add_attendees_html($post) {
  // print_r($post);
  ?>
  <p>Click to open the Add Attendees area.</p>
  <div>
    <button class="button" type="button" onClick="javascript: document.body.classList.toggle('add-attendees-modal__open')">Open Attendees Config</button>
  </div>
  <?php
}

add_action( 'admin_footer', function() {
  if(!isset($_GET['post'])) return;
  $posttype = get_post_type($_GET['post']);
  if($posttype != 'shop_order') return;
  ?>
  <div class="add-attendees-modal">
    <div class="add-attendees-modal__inner">
      <span class="add-attendees-modal__close" onClick="javascript: document.body.classList.toggle('add-attendees-modal__open')">✕</span>
      <?php echo do_shortcode("[add_attendees_to_order order_id={$_GET['post']}]") ?>
    </div>
  </div>
  <?php
} );