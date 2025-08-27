<?php

if( get_row_layout() == 'related_content' ):
  $heading = get_sub_field('heading');
  $related_posts = get_sub_field('posts');
  if (!empty($related_posts)) :
    ?>
    <div class="related-content">
      <div class="container">
        <div class="col-12">
            <?php if ($heading) :?>
              <h2 class="related-content-title"> <?php echo esc_html($heading) ?> </h2>
            <?php endif; ?>
            <div class="related-content-list">
          <?php
            foreach ($related_posts as $post) {
              ?>
              <div class="related-content-item">
                <?php
                // Get the post title with link.
                $post_title = get_the_title($post->ID);
                $post_url = get_permalink($post->ID);
                $excerpt = get_the_excerpt($post->ID);
                ?>
                <div class="related-content-item-inner">
                  <div class="related-content-item-thumbnail"><?php the_post_thumbnail('medium'); ?></div>
                  <h3 href="<?php echo esc_url($post_url)?>"> <?php echo esc_html($post_title)?></h3>
                  <div class="related-content-excerpt">
                    <?php echo esc_html($excerpt); ?>
                  </div>
                  <div class="related-content-readmore">
                    <a href="<?php echo esc_url($post_url)?>" aria-label="Read more about <?php echo esc_html($post_title)?>"> Read more </a>
                  </div>
                </div>
              </div>
            <?php  } ?>
            </div>
        </div>
      </div>
  </div>

  <?php endif;
  wp_reset_postdata();?>
  <?php endif; ?>

