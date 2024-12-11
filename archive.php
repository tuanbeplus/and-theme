<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

// List of post types to hide their archives
$post_types_hidden = array('assessments', 'submissions', 'dcr_submissions', 'reports', 'dcr_reports');
// Get the queried post type
$post_type = get_query_var('post_type');
// Check if the current archive is for a hidden post type
if (in_array($post_type, $post_types_hidden)) {
    // Trigger the 404 template
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    include(get_query_template('404'));
    exit;
}

get_header(); ?>

	<section id="primary">
		<main id="main" class="site-main" role="main">

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
