<?php
/**
 * Landing product
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

extract($atts);
?>
<div 
  class="landing-product-v2" 
  data-post-per-page-for-search="<?php echo $items; ?>"
  data-loadmore=<?php echo $loadmore; ?> >
  <?php if($search_and_filter) {
    pp_product_search_filter_v2_tag();
  } ?>

  <div class="landing-product__entry">
    <?php echo pp_product_landing_load_init([
      // 's' => 'lorem',
      // 'term' => 32
      'posts_per_page' => $items,
      'paged' => 1,
      'loadmore' => true
    ]); ?>
  </div>
</div>