<?php
/**
 * Calendly product type
 * 
 * @since 1.0.0
 * @author Mike
 */

add_action( 'init', 'pp_register_calendly_product_type' );

function pp_register_calendly_product_type () {

  class WC_Product_Calendly extends WC_Product {

    public function __construct( $product ) {
      $this->product_type = 'calendly'; // name of your custom product type
      parent::__construct( $product );
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
        'id'          => '_calendly_booking_url',
        'label'       => __( 'Calendly Booking URL', 'pp' ),
        'placeholder' => '',
        'desc_tip'    => 'true',
        'description' => __( 'Enter Calendly Booking URL. (https://calendly.com/event_types/user/me)', 'pp' ),
      ]);
		?></div>
	</div><?php
}

add_action( 'woocommerce_process_product_meta', 'pp_save_calendly_product_type_options_field' );

function pp_save_calendly_product_type_options_field( $post_id ) {

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
		wc_get_template( 'single-product/add-to-cart/gift_card.php',
			'',
			'',
			trailingslashit( $template_path ) );
	}
}