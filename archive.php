<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

	<section id="primary">
		<main id="main" class="site-main" role="main">

        <?php if (is_post_type_archive('assessments') || is_post_type_archive('submissions') || is_post_type_archive('reports') || is_post_type_archive('members')): ?>
            <?php get_template_part( '404' ); exit(); ?>
        <?php else: ?>

		<?php
		if ( have_posts() ) : ?>
            
            <div class="breadcrumbs-top">
                <div class="container">
                    <?php bcn_display($return = false, $linked = true, $reverse = false, $force = false); ?>
                </div>
            </div>
            <section class="hero breadcrumbs">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3 backlink">
                        <a href="/news-and-events" class="btn circle red">
                                <span class="material-icons">arrow_back</span>
                                <span class="text">News & Events</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-8 col-lg-9 title">
                                <header class="page-header">
                                    <?php
                                        the_archive_title( '<h1 class="page-title">', '</h1>' );
                                        the_archive_description( '<div class="archive-description">', '</div>' );
                                    ?>
                                </header><!-- .page-header -->
                        </div>
                    </div>
                </div>
            </section>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3"></div>

                    <div class="col-12 col-md-8 col-lg-9">
                    <section class="row cards">
                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();

                            /*
                            * Include the Post-Format-specific template for the content.
                            * If you want to override this in a child theme, then include a file
                            * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                            */
                            get_template_part( 'template-parts/content', get_post_format() );

                        endwhile;
                    
                    echo '</section>
                    </div>';

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

        endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <section class="back-page">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-6 text-content-block">
                        <a href="/news-and-events/" class="btn circle">
                            <span class="material-icons">arrow_back</span>
                            <span class="text">News & Events</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
