
<?php if( get_row_layout() == 'image_carousel' ): ?>
<?php $cards_id = rand(1, 999); ?>
<div class="image-carousel container">
  <div id="cards-<?php echo $cards_id; ?>" class="owl-carousel" data-slider-id="<?php echo $cards_id; ?>">
    <!-- Carousel -->
    <?php if( have_rows('image_carousel') ): ?>
          <?php while( have_rows('image_carousel') ): the_row(); ?>
            <?php $image = get_sub_field('carousel_image');
            if ($image): ?>
              <div data-hash="<?php echo trim($image['id']); ?>">
               <div class="image-wrapper"> <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>"></div>
                <?php if (isset($image['caption']) && $image['caption']): ?>
                  <figcaption class="image-caption">
                    <?php echo esc_html($image['caption']); ?>
                  </figcaption>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php endwhile; ?>
    <?php endif; ?>

  </div>
  <div class="owl-thumbs" data-slider-id="<?php echo $cards_id; ?>">
    <?php if( have_rows('image_carousel') ): ?>
      <?php while( have_rows('image_carousel') ): the_row(); ?>
        <?php $image = get_sub_field('carousel_image');
        if ($image): ?>
          <div class="owl-thumb-item">
            <?php
              // Display the thumbnail image in the carousel navigation.
              if( $image ) {
                echo wp_get_attachment_image( $image['id'], 'medium' );
              }
            ?>
          </div>
        <?php endif; ?>
      <?php endwhile; ?>
    <?php endif; ?>

  </div>
</div>

<script>
  jQuery(document).ready(function(){
    const owlCarousel = jQuery("#cards-<?php echo $cards_id; ?>");
    owlCarousel.owlCarousel({
      loop: false,
      margin: 16,
      items: 1,
      nav: true,
      dots: true,
      dotsEach: 1,
      autoplay: false,
      autoplayHoverPause: true,
      thumbs: true,
      thumbsPrerendered: true,
      autoHeight: true,
    });
    const observer = new MutationObserver(() => {
      document.querySelectorAll('.owl-prev[role="presentation"], .owl-next[role="presentation"]').forEach(el => {
        el.removeAttribute('role');
      });
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });

  });

</script>

<?php endif; ?>