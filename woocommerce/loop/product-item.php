<?php
$product_id = get_the_ID();
$product   = wc_get_product( $product_id );
$image_id  = $product->get_image_id();
$image_url = wp_get_attachment_image_url( $image_id, 'large' );
if (!$image_url) {
    $image_url  = '/wp-content/uploads/woocommerce-placeholder-300x300.png';
}

?>
<li class="product-item product">
    <a href="<?php echo get_the_permalink(); ?>">
        <div class="item-thumbnail">
            <img src="<?php echo $image_url; ?>" alt="<?php echo get_the_title(); ?>">
        </div>
        <div class="item-body">
            <?php pp_product_list_item_info_tag($product); ?>
            <h3 class="item-title"><?php echo get_the_title(); ?></h3>
            <div class="item-description">
                <?php 
                    // $product_description = get_the_content(); 
                    // preg_match("/(\S+\s*){0,20}/", $product_description, $regs);
                    // $product_description = trim($regs[0]);
                    // $product_link = get_the_permalink();

                    // echo $product_description;
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