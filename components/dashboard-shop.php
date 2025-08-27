<?php 
if( get_row_layout() != 'dashboard_shop') {
    return;
}
$shop_heading = get_sub_field('shop_heading');
$shop_heading = !empty($shop_heading) ? $shop_heading : 'Our learning solutions';
$cta_text = get_sub_field('cta_text');
$cta_text = !empty($cta_text) ? $cta_text : 'View our products';
$cta_link = get_sub_field('cta_link');
$cta_link = !empty($cta_link) ? $cta_link : '/shop/';
?>
<section class="dashboard tasks shop">
    <div class="container">
        <div class="inner">
            <div class="row">
                <div class="col-12 header">
                    <div class="inside">
                        <div class="row">
                            <div class="col-md-6 title">
                                <img src="<?php echo AND_IMG_URI. 'tasks.svg' ?>" alt=""/>
                                <h2><?php echo $shop_heading; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ( class_exists('WooCommerce') ): 
                    $cart_url = wc_get_cart_url();
                    $checkout_url = wc_get_checkout_url();
                    $my_account_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
                    $woo_landing_pages  = array(
                        'View Cart' => $cart_url,
                        'Checkout' => $checkout_url,
                        'My Account' => $my_account_url,
                    );
                    ?>
                    <div class="col-12 the-details">
                        <ul>
                        <?php foreach ($woo_landing_pages as $page_name => $page_url): ?>
                            <?php if (!empty($page_url)): ?>
                            <li>
                                <a href="<?php echo $page_url ?>" target="_blank">
                                    <span class="external-link-icon" role="img" aria-label="External, opens in a new tab">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </span>
                                    <?php echo $page_name; ?>
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-12 cta">
                        <div class="inside">
                            <a href="<?php echo esc_url($cta_link) ?>">
                                <div><span class="material-icons">arrow_forward</span></div>
                                <?php echo $cta_text; ?></a>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-12 the-details">
                        <p><?php echo 'WooCommerce is not active.'; ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>