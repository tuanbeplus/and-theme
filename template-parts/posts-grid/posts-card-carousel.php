<?php 
/**
 * Template posts card loop with Owl Carousel
 * 
 * @param string $post_type	    Post type name
 * @param array $posts		    Array posts ID
 * 
 */
$cards_id = rand(1, 999);
?>
<!-- Cards carousel for Mobile -->
<div id="cards-<?php echo $cards_id; ?>" class="cards owl-carousel">
<?php foreach ($posts as $post_id): ?>
    <div class="post-card item">
        <div>
            <?php 
                if (function_exists('wc_get_product')) {
                    $product = wc_get_product($post_id);
                }
                $img_url = get_the_post_thumbnail_url($post_id, 'medium_large');
                if(empty($img_url)) {
                    $img_url = AND_IMG_URI .'footer-bg.jpg';
                }
            ?>
            <img class="card__thumb" src="<?php echo $img_url; ?>" alt="<?php echo get_the_title($post_id); ?>" loading="lazy">
            <div class="card__body">
                <span class="card__meta"><?php echo get_the_date('d/m/Y', $post_id) ?></span>
                <h3 class="card__title">
                    <a class="card__action" href="<?php echo get_the_permalink($post_id); ?>">
                        <?php 
                            if (strlen(get_the_title($post_id)) > 60) {
                                echo substr(get_the_title($post_id), 0, 60).'...'; 
                            }
                            else {
                                echo substr(get_the_title($post_id), 0, 60); 
                            }
                        ?>
                    </a>
                </h3>
            </div>
        </div>
        <div class="card__footer">
            <div class="card__description">
                <?php 
                    if ($post_type == 'product') {
                        if (!empty($product->get_short_description())) {
                            echo '<p>';
                            echo substr(wp_strip_all_tags($product->get_short_description()), 0, 160).'...';
                            echo '<span class="read-more">Read more</span>';
                            echo '</p>';
                        }
                    }
                    else {
                        if (!empty(get_the_excerpt($post_id))) {
                            echo substr(get_the_excerpt($post_id), 0, 160).'...';
                        }
                    }
                ?>
            </div>
            <?php 
                if ($post_type == 'product') {
                    echo do_shortcode('[add_to_cart id="'.$post_id.'"]'); 
                }
                else {
                    echo '<span class="card__btn">Read more</span>';
                }
            ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
<!-- /Cards carousel for Mobile -->
<script>
    jQuery(document).ready(function(){
        jQuery("#cards-<?php echo $cards_id; ?>").owlCarousel({
            loop: false,
            margin: 16,
            items: 1,
            nav: false,
            dots: true,
            dotsEach: 1,
            autoplay: false,
            autoplaySpeed: 1000,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            navText: [
                '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>', 
                '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>'
            ],
        });
    });
</script>