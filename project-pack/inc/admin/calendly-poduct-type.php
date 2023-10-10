<?php
/**
 * Calendly product type
 * 
 * @since 1.0.0
 * @author Mike
 */

add_action( 'init', 'pp_register_calendly_product_type' );

function pp_register_calendly_product_type () {

	if (class_exists('WC_Product')) {
		class WC_Product_Calendly extends WC_Product {
			public function __construct( $product ) {
			  @$this->product_type = 'calendly'; // name of your custom product type
			  parent::__construct( $product );
			}
		}
	}
}

add_filter( 'product_type_selector', 'pp_add_calendly_product_type' );

function pp_add_calendly_product_type ( $type ) {
	$type[ 'calendly' ] = __( 'Calendly', 'pp' );
	return $type;
}

add_filter( 'woocommerce_product_data_tabs', 'pp_calendly_product_type_tab' );

function pp_calendly_product_type_tab( $tabs) {
	// Key should be exactly the same as in the class product_type
	return ['calendly' => [
    'label'	 => __( 'Calendly', 'wcpt' ),
		'target' => 'calendly_options',
		'class'  => ('show_if_calendly'),
  ]] + $tabs;
}

add_action( 'woocommerce_product_data_panels', 'pp_calendly_product_type_options_product_tab_content' );

function pp_calendly_product_type_options_product_tab_content() {
	// Dont forget to change the id in the div with your target of your product tab
	?><div id='calendly_options' class='panel woocommerce_options_panel'><?php
		?><div class='options_group'><?php
			woocommerce_wp_text_input([
				'id'          => '_slot_price',
				'label'       => __( 'Slot Price', 'pp' ),
				'placeholder' => '',
				'desc_tip'    => 'true',
				'description' => __( 'Enter Price.', 'pp' ),
				'type' 				=> 'number',
				'custom_attributes' => [
					'step' 	=> 'any',
					'min' 	=> '0'
				]
			]);
		
			woocommerce_wp_text_input([
        'id'          => '_calendly_booking_url',
        'label'       => __( 'Calendly Booking URL', 'pp' ),
        'placeholder' => '',
        // 'desc_tip'    => 'true',
        'description' => __( 'Enter Calendly Booking URL. (Get it here: https://calendly.com/event_types/user/me)', 'pp' ),
      ]);
		?></div>
	</div><?php
}

add_action( 'woocommerce_process_product_meta', 'pp_save_calendly_product_type_options_field' );

function pp_save_calendly_product_type_options_field( $post_id ) {

	if ( isset( $_POST['_slot_price'] ) ) :
		update_post_meta( $post_id, '_slot_price', sanitize_text_field( $_POST['_slot_price'] ) );
	endif;

	if ( isset( $_POST['_calendly_booking_url'] ) ) :
		update_post_meta( $post_id, '_calendly_booking_url', sanitize_text_field( $_POST['_calendly_booking_url'] ) );
	endif;
}

// add_action( 'woocommerce_single_product_summary', 'pp_calendly_product_type_template', 60 );

function pp_calendly_product_type_template () {

	global $product;
	if ( 'gift_card' == $product->get_type() ) {

		$template_path = plugin_dir_path( __FILE__ ) . 'templates/';
		// Load the template
		wc_get_template( 'single-product/add-to-cart/....php',
			'',
			'',
			trailingslashit( $template_path ) );
	}
}

add_filter( 'woocommerce_product_get_price', 'pp_reseller_price', 10, 2 );
// add_filter( 'woocommerce_get_price', 'pr_reseller_price', 10, 2 );

function pp_reseller_price( $price, $product ) {

	if($product->is_type( 'calendly' )) {
		return get_post_meta( $product->get_id(), '_slot_price', true );
	} 

	return $price;
}

add_action( 'wp_ajax_pp_add_product_calendly_to_cart', 'pp_add_product_calendly_to_cart' );
add_action( 'wp_ajax_nopriv_pp_add_product_calendly_to_cart', 'pp_add_product_calendly_to_cart' );

function pp_add_product_calendly_to_cart() {
	global $woocommerce;
	$pid = (int) $_POST['payload']['product_id'];
	$custom_data = [
		'calendly_data' => $_POST['payload']['extra_data']
	];

	$result = $woocommerce->cart->add_to_cart($pid, 1, 0, [], $custom_data);
	wp_send_json( [
		'success' => true, 
		'payload' => $result,
	] );
}

/**
 * Add custom meta data to order item
 */
add_action('woocommerce_add_order_item_meta','pp_add_custom_data_to_order_item_meta', 20, 3 );

function pp_add_custom_data_to_order_item_meta($item_id, $item_values, $item_key) {
  $custom_meta_field = 'calendly_data';

  if( isset($item_values[$custom_meta_field]) )
    wc_update_order_item_meta( 
      $item_id, 
      $custom_meta_field, 
      $item_values[$custom_meta_field] );
}

add_action( 'woocommerce_before_order_itemmeta', 'pp_before_order_itemmeta', 10, 3 );
function pp_before_order_itemmeta( $item_id, $item, $_product ){
	if(!isset($item['calendly_data'])) return;
	?>
	<br />
	<p>
		Event URL: <a href="<?php echo $item['calendly_data']['event']['uri']; ?>" target="_blank"><?php echo $item['calendly_data']['event']['uri']; ?></a> <br />
		Invitee URL: <a href="<?php echo $item['calendly_data']['invitee']['uri']; ?>" target="_blank"><?php echo $item['calendly_data']['invitee']['uri']; ?></a>
	</p>
	<?php
}

add_action( 'init', function() {
	// if(!isset($_GET['__showcart'])) return; 
	// global $woocommerce;
	// $items = $woocommerce->cart->get_cart();
	// foreach($items as $item => $values) { 
	// 	echo '<pre>'; print_r($values); echo '</pre>';
	// } 

	// $order=wc_get_order(30799);
	// foreach ( $order->get_items() as $item_id => $item ) {
	// 	var_dump($item['item_meta']);
	// }
} );

