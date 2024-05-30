<?php 
/**
 * Template posts card loop default
 * 
 * @param string $post_type	    Post type name
 * @param array $posts		    Array posts ID
 * 
 */
?>
<!-- Cards for Desktop, tablet -->
<ul class="cards">
<?php foreach ($posts as $post_id): ?>
    <li class="post-card item">
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
    </li>
<?php endforeach; ?>
</ul>
<!-- /Cards for Desktop, tablet -->