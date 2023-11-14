<?php
/*
* Template Name: Shop Landing
*/
get_header();

global $post;
global $woocommerce;

// $showOnThisPage = get_field('show_on_this_page', $post->ID);
// $onThisPageCustomLinks = get_field('on_this_page_custom_links', $post->ID);
// $sidebarCta = get_field('sidebar_cta', $post->ID);
// $showOnPageMainCta = get_field('main_sidebar_cta', $post->ID);

pp_the_shop_landing_template();

get_footer(); return; ?>

	<section id="primary">
        <div class="container">
            <div class="row">
                <div class="col-12 main-area title-area">
                    <h1 class="page-title"><?php echo get_the_title(); ?></h1>
                </div>
            </div>
        </div>

        <?php
            if( have_rows('page_builder') ):
                // loop through the rows of data
                while ( have_rows('page_builder') ) : the_row();

                        get_template_part('components/hero-with-shape');

                endwhile;

            else :
                // no layouts found
            endif;
        ?>

        <div class="container holder">
            <div class="row">
                <?php if( have_rows('page_builder') ): ?>
                    <div class="col-md-12 col-lg-9 main-area">
                        <?php while ( have_rows('page_builder') ) : the_row(); ?>
                            <?php get_template_part( 'components/single-column-content' ); ?>
                            <?php get_template_part( 'components/woo-products-search' ); ?>
                            <?php get_template_part( 'components/faqs' ); ?>
                        <?php endwhile; ?>
                    </div>

                <?php else : ?>
                    // no layouts found
                <?php endif; ?>

                <?php if($showOnThisPage == 'yes'): ?>
                    <div class="col-md-12 col-lg-3 on-this-page sidebar">
                        <div class="inner">
                            <p>On this page</p>
                            <ul id="toc-list"></ul>
                            <div id="btn-mini-cart">
                                <div class="icon-box">
                                    <span class="icon-cart">
                                        <img src="<?php echo AND_IMG_URI . 'icons8-cart-96.png'; ?>" alt="Cart">
                                    </span>
                                    <span>View Cart</span>
                                </div>
                                <div class="quantity-cart"><?php echo $woocommerce->cart->get_cart_contents_count(); ?></div>
                            </div>
                        </div>
                    </div>            
                <?php endif; ?>   
            </div>
        </div>
	</section><!-- #primary -->

<?php
   // get_footer();
