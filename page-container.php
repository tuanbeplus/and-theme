<?php
/*
 * Template Name: Page Container
 */

/**
 * @version 1.0.0
 * @since 1.0.0
 * @package project-pack
 */ 

get_header(); ?>

<div class="page-container">
  <div class="site-container">

    <?php do_action('pp/page_container_before'); ?>

    <?php 
    // The Loop 
      if ( have_posts() ) : 
      while ( have_posts() ) : the_post();
        the_content();
      endwhile;
    endif;
    
    // Reset Post Data
    wp_reset_postdata();
    ?>

    <?php do_action('pp/page_container_after'); ?>

  </div>
</div>

<?php
get_footer();
