<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WP_Bootstrap_Starter
 */
global $wp_query;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

get_header(); ?>

	<section id="template-results-search" class="content-area">
		<main class="container">
			<div class="row">
				 <div class="content-search col-sm-12 col-lg-8">
					 <?php get_template_part( 'searchform-autocomplete'); ?>

					 <?php
					 if ( have_posts() ) :

						 ?>
						 <div class="count-results">
						 		<?php echo 'Page '.$paged .' of '.$wp_query->max_num_pages.' results'; ?>
						 </div>
						 <div class="row">
						 		<div class="col-sm-12 col-lg-2">
						 			<strong class="all-result-text">All website results:</strong>
						 		</div>
								<div class="col-sm-12 col-lg-10">
						 			<?php
									/* Start the Loop */
									while ( have_posts() ) : the_post();

										get_template_part( 'template-parts/content', 'search' );

									endwhile;

									the_posts_pagination( array(
							        'prev_text'          => __( 'Previous', 'andorgau' ),
							        'next_text'          => __( 'Next', 'andorgau' )
							    ) );

									?>
						 		</div>
						 </div>
						 <?php

					 else :

						 get_template_part( 'template-parts/content', 'none' );

					 endif; ?>
				 </div>
				 <div class="widget-area col-sm-12 col-lg-4">
					 <?php dynamic_sidebar( 'sidebar-search' ); ?>
				 </div>
			</div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
