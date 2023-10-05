<?php

if( get_row_layout() == 'posts_grid' ):
    $headline = get_sub_field('headline');
    $show_cta = get_sub_field('show_cta');
    $cta_text = get_sub_field('cta_text');
    $cta_link = get_sub_field('cta_link');
    $content_options = get_sub_field('content_options');
    $post_type = $content_options['post_type'];
    $select_cards_by = $content_options['select_cards_by'];
    $dynamic_order = $content_options['select_cards_dynamicallly'];
    $select_cards_manually = $content_options['select_cards_manually'];
    $number_cards = $content_options['number_cards'];
    $posts = array();

    if ($select_cards_by == 'manually') {
        $posts = $select_cards_manually;
    } else {
        $posts = get_posts_grid_component($post_type, $number_cards, $dynamic_order);
    }
    ?>
    <section class="posts-grid">
        <div class="container">
            <div class="grid-top">
                <?php if ($headline): ?>
                    <h2 class="headline"><?php echo $headline; ?></h2>
                <?php endif; ?>

                <?php if ($show_cta == true): ?>
                    <a class="posts-grid__cta" href="<?php echo $cta_link; ?>">
                        <?php echo $cta_text; ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="posts-grid-wrapper">
                <?php if ($post_type == 'product'): ?>
                    <?php echo pp_product_landing_load_init(); ?>
                <?php else: ?>
                    <?php echo get_template_posts_card($posts); ?>
                <?php endif ?>
            </div>
        </div>
    </section>

<?php endif; ?>
