<?php
// Check if the resource is member-only and if the user is logged in.
$member_only = get_field('member_only');
$restricted = false;
if ($member_only) {
  $restricted = !isset($_COOKIE['lgi']);
}
?>

<div class="resource-item container">
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
              <div class="resource-item-tags">
                <?php if ( get_field('resource_topic') ): ?>
                  <div class="resource-item-topic">
                    <?php the_field('resource_topic'); ?>
                  </div>
                <?php endif; ?>
                <?php if ( $is_featured = get_field('is_featured_media') ) : ?>
                  <?php if ( $is_featured) : ?>
                    <div class="resource-featured">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path d="M8.99994 13.695L3.71019 16.656L4.89144 10.71L0.440186 6.594L6.46044 5.88L8.99994 0.375L11.5394 5.88L17.5597 6.594L13.1084 10.71L14.2897 16.656L8.99994 13.695Z" fill="white"/>
                      </svg>
                    Featured resource
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
                <?php if ( $restricted ) : ?>
                  <div class="resource-item-member-exclusive">
                    Member exclusive
                  </div>
                 <?php endif; ?>
              </div>
    <h3 class="resource-item-title">
      <?php
        // Get the url of the resource media post.
        $post_id = get_the_ID();
        $post_url = get_permalink($post_id);
      ?>
      <?php the_title(); ?>
    </h3>
    <div class="resource-item-description">
      <?php if (get_field('description')): ?>
        <p>
          <?php the_field('description'); ?>
        </p>
      <?php endif; ?>
    </div>
    <?php if (!$restricted): ?>
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
                <a href="<?php echo esc_url($media_file['url']) ?>" download rel="noopener noreferrer">
                  <span class="link-text">
                    <?php the_sub_field('media_text'); ?>
                    (<?php echo esc_html(strtoupper(pp_get_friendly_file_type($media_file['subtype']))) . ', ' . esc_html($filesize); ?>)
                  </span class="link-text">
                  <span class="download-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
                        <path d="M3 19.5562H21V21.5562H3V19.5562ZM13 13.7282L19.071 7.65615L20.485 9.07015L12 17.5562L3.515 9.07115L4.929 7.65615L11 13.7262V2.55615H13V13.7282Z" fill="#663077"/>
                    </svg>
                  </span>
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
    <?php else : ?>
       <div class="member-login-cta"><a href="/login">Log in to access member exclusive content</a></div>
    <?php endif ?>


  </div>
</div>