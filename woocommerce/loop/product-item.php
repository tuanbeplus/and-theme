<?php
$product_id = get_the_ID();
$product   = wc_get_product( $product_id );
$image_id  = $product->get_image_id();
$image_url = wp_get_attachment_image_url( $image_id, 'large' );
if (!$image_url) {
    $image_url  = AND_IMG_URI .'woo-product-placeholder-img.webp';
}
?>
<li class="product-item product">
    <a href="<?php echo get_the_permalink(); ?>">
        <div class="item-thumbnail">
            <img src="<?php echo $image_url; ?>" alt="<?php echo get_the_title(); ?>">
        </div>
        <div class="item-body">
            <h3 class="item-title"><?php echo get_the_title(); ?></h3>
            <div class="item-description">
                <?php 
                    echo '<p>';
                    echo wp_strip_all_tags($product->get_short_description());
                    echo '<span class="btn-read-more">Read more</span>';
                    echo '</p>';
                ?>
            </div>
        </div>
    </a>
    <?php echo do_shortcode('[add_to_cart id="'. get_the_ID() .'"]'); ?>
</li>