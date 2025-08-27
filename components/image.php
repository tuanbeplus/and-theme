<?php if( get_row_layout() == 'image' ): ?>
  <?php $image = get_sub_field('image'); ?>
  <div class="image-component container">
      <?php if ($image && isset($image['url'])): ?>
          <img src="<?php echo esc_url($image['url']); ?>"
               alt="<?php echo esc_attr($image['alt'] ?? ''); ?>"
               title="<?php echo esc_attr($image['title'] ?? ''); ?>"/>
      <?php endif; ?>
      <?php if (isset($image['caption']) && $image['caption']): ?>
          <figcaption class="image-caption">
              <?php echo esc_html($image['caption']); ?>
          </figcaption>
      <?php endif; ?>
  </div>
<?php endif; ?>