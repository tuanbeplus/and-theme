<?php if (get_row_layout() === 'video') : ?>
    <?php
    $video_file = get_sub_field('video_file');
    $video_url = get_sub_field('video_url');
    $transcript = get_sub_field('transcript');

    // More videos/Video series.
    $more_videos = get_sub_field('add_more_videos');
    $more_videos_heading = get_sub_field('more_videos_heading');
    $play_all_link = get_sub_field('play_all_link');
    if ($more_videos) :
      // Fetch the youtube_url field inside the repeater field `more_urls`
      $video_urls = [];
      if (have_rows('more_urls')) :
          while (have_rows('more_urls')) : the_row();
              $video_urls[] = get_sub_field('youtube_url');
          endwhile;
          // Get thumbnails for each video URL
          $video_thumbnails = [];
          foreach ($video_urls as $url) {
              $video_id = preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
              if ($video_id) {
                  $video_thumbnails[] = 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
              }
          }
      endif;
    endif;

    if (!empty($video_file) || !empty($video_url)) : ?>
        <div class="video-component-wrapper container">
            <?php if (!empty($video_file) && !empty($video_file['url'])) : ?>
                <div class="component-video">
                    <video controls>
                        <source src="<?php echo esc_url($video_file['url']); ?>" type="video/mp4">
                        <?php esc_html_e('Your browser does not support the video tag.', 'and-theme'); ?>
                    </video>
                </div>
            <?php endif; ?>

            <?php if (!empty($video_url)) : ?>
                <?php
                $embed = wp_oembed_get($video_url);
                if ($embed) :
                    ?>
                    <div class="component-video oembed">
                        <?php echo $embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                <?php else : ?>
                    <p>
                        <a href="<?php echo esc_url($video_url); ?>" target="_blank" rel="noopener noreferrer">
                            <?php esc_html_e('Watch Video', 'and-theme'); ?>
                        </a>
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($transcript)) : ?>
                <?php $transcript_id = uniqid(); ?>
                <div class="video-transcript">
                    <button class="transcript-toggle" aria-expanded="false" aria-controls="transcript-content-<?php echo $transcript_id; ?>">
                        <span class="text">Video transcript</span>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                                <path d="M18.0001 19.758L25.4251 12.333L27.5461 14.454L18.0001 24L8.4541 14.454L10.5751 12.333L18.0001 19.758Z" fill="black"/>
                            </svg>
                        </span>
                    </button>
                    <div class="transcript-content" id="transcript-content-<?php echo $transcript_id; ?>">
                       <div class="text-content-block"> <?php echo wp_kses_post($transcript); ?></div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($more_videos && !empty($video_urls)) : ?>
              <div class="more-videos">
                 <div class="more-videos-heading">
                      <h3 class="more-videos-title"><?php echo $more_videos_heading ?></h3>
                      <a href="<?php echo esc_url($play_all_link); ?>" target="_blank"> <span>Play all </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M19.376 12.4159L8.777 19.4819C8.70171 19.5321 8.61423 19.5608 8.52389 19.5651C8.43355 19.5694 8.34373 19.5492 8.264 19.5065C8.18427 19.4638 8.1176 19.4003 8.07111 19.3227C8.02462 19.2451 8.00005 19.1564 8 19.0659V4.93395C8.00005 4.8435 8.02462 4.75477 8.07111 4.67719C8.1176 4.59961 8.18427 4.53609 8.264 4.49341C8.34373 4.45072 8.43355 4.43045 8.52389 4.43478C8.61423 4.4391 8.70171 4.46784 8.777 4.51795L19.376 11.5839C19.4445 11.6296 19.5006 11.6915 19.5395 11.764C19.5783 11.8366 19.5986 11.9176 19.5986 11.9999C19.5986 12.0823 19.5783 12.1633 19.5395 12.2359C19.5006 12.3084 19.4445 12.3703 19.376 12.4159Z" fill="#663077"/>
                        </svg>
                     </a>
                 </div>
                  <div class="video-list-wrapper">
                    <ul class="video-list">
                      <?php foreach ($video_urls as $index => $url) : ?>
                        <li>
                          <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo esc_url($video_thumbnails[$index]); ?>" alt="<?php esc_attr_e('Video thumbnail', 'and-theme'); ?>">
                          </a>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
