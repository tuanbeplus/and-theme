<?php
/*
 Template Name: Sub Landing
 */
get_header();
?>
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-3">
        <div class="sidebar-left">
          <h3 class="title-sidebar">Menu</h3>
          <?php

          // Check rows existexists.
          if( have_rows('bt_menu') ):
              ?><ul class="bt-menu"><?php
              // Loop through rows.
              while( have_rows('bt_menu') ) : the_row();

                  // Load sub field value.
                  $name_value = get_sub_field('bt_name_url');
                  $link_value = get_sub_field('bt_menu_url');
                  // Do something...
                  ?>
                  <li class="menu-item"><a href="<?php echo $link_value; ?>"><?php echo $name_value; ?></a></li>
                  <?php

              // End loop.
              endwhile;
              ?></ul><?php

          // No value.
          else :
              // Do something...
          endif;
          ?>
        </div>
      </div>
      <div class="col-12 col-md-9 info">
        <h3 class="title"><?php the_title(); ?></h3>

        <?php 
        if (have_posts()):
          /* Start the Loop */
          while ( have_posts() ) :
            the_post(); ?>
              <div class="landing-sub-content">
                <?php the_content(); ?>
              </div>
            <?php
          endwhile; // End of the loop.
        endif;
        ?>

      </div>
    </div>
  </div>

<?php 
get_footer();
?>