<?php
/**
 * Landing product
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

extract($atts);

$cat_slug = '';
$term_html = '';
$q_args = [
  'posts_per_page' => $items,
  'paged' => 1,
];

if(isset($_GET['product-term'])) {
  $cat_slug = $_GET['product-term'];
  $term = get_term_by('slug', $cat_slug, 'product_cat');
  $term_html = "<div class=\"term-html\"><h2>$term->name</h2>" . wpautop($term->description) . "</div>";
  $q_args['tax_query'] = [
    [
      'taxonomy' => 'product_cat',                
      'field' => 'slug',                    
      'terms' => $cat_slug,   
      'include_children' => true,         
      'operator' => 'IN'          
    ]
  ];
}

$products_query = pp_query_product($q_args);
echo pp_product_landing_load_init();
?>
<div 
  class="landing-product" 
  data-items="<?php echo $items; ?>"
  data-max-numpage="<?php echo $products_query->max_num_pages; ?>"
  data-current-page="1" >
  <?php if($search_and_filter) {
    pp_product_search_filter_tag($cat_slug);
  } ?>

  <div class="landing-product__entry">
    <div class="product-term-content">
      <?php echo $term_html; ?>
    </div>
    <?php pp_get_products_tag($products_query); ?>
    <?php if ($loadmore && $products_query->max_num_pages > 1) : ?>
    <button class="btn-loadmore"><?php _e('View more', 'pp') ?></button>
    <?php endif; ?>
  </div>
</div>