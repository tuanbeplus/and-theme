<?php
/*
 Template Name: Landing
 */
get_header();
?>
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-9 info">
        <h3 class="title"><?php the_field( "bt_title" ); ?></h3>
        <div class="embed-container">
            <?php the_field('bt_video'); ?>
        </div>
        <span>
        <?php 
        $file = get_field('bt_download_file');
        if( $file ): ?>
            <a href="<?php echo $file['url']; ?>"><?php the_field( "bt_download_text" ); ?></a>
        <?php endif; ?>
        </span>
        <div class="text"><?php the_field( "bt_text" ); ?></div>

        <?php 
        $images = get_field('bt_logo_banner');
        if( $images ): ?>
            <h4 class="title-partners">The Access and Inclusion Development Partners</h4>
            <ul class="bt_logo_banner">
                <?php foreach( $images as $image ): ?>
                    <li>
                        <img src="<?php echo esc_url($image['sizes']['medium']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
      </div>
      <div class="col-12 col-md-3">
        <div class="sidebar">
          <h3 class="title-sidebar">In this section</h3>
          <?php

          // Check rows existexists.
          if( have_rows('bt_sidebar') ):
              ?><ul class="bt-link"><?php
              // Loop through rows.
              while( have_rows('bt_sidebar') ) : the_row();

                  // Load sub field value.
                  $name_value = get_sub_field('bt_name');
                  $link_value = get_sub_field('bt_link');
                  // Do something...
                  ?>
                  <li class="limk-item"><a href="<?php echo $link_value; ?>"><img src="/wp-content/themes/and-theme/assets/imgs/igon.jpg" alt="Facebook" /><?php echo $name_value; ?></a></li>
                  <?php

              // End loop.
              endwhile;
              ?></ul><?php

          // No value.
          else :
              // Do something...
          endif;
          ?>
          <a class="button-sidebar" href="#!">Continue Assesment</a>
          <a class="button-sign-in" href="#!">Sign in or sign up to start assesment</a>
        </div>
      </div>
    </div>
  </div>

<?php 
get_footer();
?>