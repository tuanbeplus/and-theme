<?php
/*
Single Post Template: Current Opportunities
*/

get_header();

// Check if the resource is member-only and if the user has access.
$member_only = get_field('member_only');
$media_access = false;

// Public resources are always accessible.
if ( ! $member_only ) {
  $media_access = true;
}
// For member-only resources, check if the user is logged in and has the correct role.
else {
  if ( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $roles = $user->roles;
    $allowed_roles = array_intersect( [ 'MEMBERS', 'PRIMARY_MEMBERS' ], $roles );
    if ( ! empty( $allowed_roles ) ) {
      $media_access = true;
    }
  }
}
?>

  <div class="col-12 current-opportunities">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-10 search">
          <article class="resource-media">
            <h3><?php the_title(); ?></h3>
            <div class="resource-media-content">
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

                    <?php if ( $member_only ) : ?>
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
                  <?php if ($media_access): ?>
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
                  <?php else : ?>
                    <div class="member-login-cta"><a href="/login">Log in to access member exclusive content</a></div>
                  <?php endif ?>
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
