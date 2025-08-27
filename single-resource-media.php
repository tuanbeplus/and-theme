<?php
/*
Single Post Template: Current Opportunities
*/

get_header();

?>

  <div class="col-12 current-opportunities">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-6 search">
          <article class="resource-media">
            <h3><?php the_title(); ?></h3>
            <div class="content">
              <div class="resource-item">
                <div class="resource-item-thumbnail">
                  <?php $thumbnail = get_field('thumbnail'); ?>
                  <?php if ($content_type = get_field('content_type') ): ?>
                    <!-- Print default image for external link -->
                    <?php if ($content_type == 'external_website'): ?>
                      <?php $image_path = get_template_directory_uri() . '/assets/imgs/web-link-thumbnail.png'; ?>
                      <img src="<?php echo  $image_path; ?>" title="<?php echo esc_attr( $thumbnail['title'] ); ?>" alt="<?php echo esc_attr( $thumbnail['alt'] ); ?>" class="tippy-added">
                    <?php elseif (isset($thumbnail['url'])): ?>
                      <img src="<?php echo esc_url( $thumbnail['url'] ); ?>" title="<?php echo esc_attr( $thumbnail['title'] ?? '' ); ?>" alt="<?php echo esc_attr( $thumbnail['alt'] ?? ''); ?>" class="tippy-added">
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                <div class="resource-item-content">
                  <!-- check if the resource_topic is  empty before printing the wrapper div -->
                  <?php if ( get_field('resource_topic') ): ?>
                    <div class="resource-item-topic">
                      <?php the_field('resource_topic'); ?>
                    </div>
                  <?php endif; ?>
                  <h3 class="resource-item-title">
                    <?php
                    // Get the url of the resource media post.
                    $post_id = get_the_ID();
                    $post_url = get_permalink($post_id);

                    ?>
                    <a href="<?php echo esc_url( $post_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                      <?php the_title(); ?>
                    </a>
                  </h3>
                  <div class="resource-item-description">
                    <?php if (get_field('description')): ?>
                      <p>
                        <?php the_field('description'); ?>
                      </p>
                    <?php endif; ?>
                  </div>
                  <div class="resource-item-links">
                    <?php if ( have_rows('media') ): ?>
                      <ul class="media-list">
                        <?php while ( have_rows('media') ): the_row(); ?>
                          <li>
                            <?php
                            $media_file = get_sub_field('media_file');

                            // Convert filesize to human-readable format.
                            $filesize = size_format($media_file['filesize'], 2);
                            ?>
                            <a href="<?php echo esc_url($media_file['url']) ?>" target="_blank" rel="noopener noreferrer">
                              <?php the_sub_field('media_text'); ?>
                              (<?php echo esc_html(strtoupper(pp_get_friendly_file_type($media_file['subtype']))) . ' ' . esc_html($filesize); ?>)
                            </a>
                          </li>
                        <?php endwhile; ?>
                      </ul>
                    <?php endif; ?>
                    <?php if ( have_rows('links') ): ?>
                      <?php while ( have_rows('links') ): the_row(); ?>
                        <ul class=" links-list">
                          <?php $link = get_sub_field('link'); ?>
                          <li>
                            <a href="<?php echo esc_url($link['url']) ?>" target="_blank" rel="noopener noreferrer">
                              <span class="link-text"> <?php echo esc_attr($link['title']) ?></span>
                              <span class="external-link-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                              <path d="M10 6V8H5V19H16V14H18V20C18 20.2652 17.8946 20.5196 17.7071 20.7071C17.5196 20.8946 17.2652 21 17 21H4C3.73478 21 3.48043 20.8946 3.29289 20.7071C3.10536 20.5196 3 20.2652 3 20V7C3 6.73478 3.10536 6.48043 3.29289 6.29289C3.48043 6.10536 3.73478 6 4 6H10ZM21 3V11H19V6.413L11.207 14.207L9.793 12.793L17.585 5H13V3H21Z" fill="#663077"/>
                            </svg>
                          </span>
                            </a>
                          </li>
                        </ul>
                      <?php endwhile; ?>
                    <?php endif; ?>
                  </div>

                </div>
              </div>
            </div>
          </article>
        </div>

      </div>
    </div>
  </div>

<?php
get_footer();
