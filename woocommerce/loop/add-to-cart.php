<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$gst_price_html = '';
$gst_rate = ppwc_get_tax_rates_for_gst();
if ( $gst_rate > 0 ) {
    $p_price = '';
    if ( $product->is_type('variable') ) {
        $available_variations = $product->get_available_variations();
        if(isset($available_variations[0])) {
            $first_variation = $available_variations[0];
            if ( $first_variation ) $p_price = $first_variation['display_price'];
        }
    } else {
        $p_price = $product->get_price();
    }
    if ( $p_price ) {
        $gst_price_html = ppwc_show_gst_price($gst_rate, $p_price);
    }
}
?>
<div class="item-bottom">
    <?php if ( $price_html = $product->get_price_html() ) : ?>
        <span class="price">
            <?php 
            echo $price_html; 
            if ( $gst_price_html ) echo ' <span class="price--gst">'.$gst_price_html.'</span>'; 
            ?>
        </span>
    <?php endif; ?>
    <div class="item-button">
    <?php 
        echo apply_filters(
            'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
            sprintf(
                '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                esc_html( $product->add_to_cart_text() )
            ),
            $product,
            $args
        );
    ?>
        <div class="loading-wrapper">
            <img style="display:none;" src="<?php echo AND_IMG_URI . 'Spinner-per.svg'; ?>" alt="Spinner">
        </div>
    </div>
</div>
<?php


