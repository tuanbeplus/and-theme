<?php if( get_row_layout() == 'quote_with_citation' ): ?>
<div class="quote-with-citation-component container">
  <div class="quote-content">
    <div class="quote-text"> <?php the_sub_field('quote'); ?> </div>
    <span class="citation"><?php the_sub_field('citation'); ?></span>
  </div>
</div>
<?php endif; ?>