<?php 
/**
 * Product single content 
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

global $product;
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'full' );
$image_url = isset($image[0]) ? $image[0] : '';
$content_block = get_field('block_content', $product->get_id());
?>
<div class="product-single-content">
  <div class="site-container">
    <h1 class="page-heading product-single__title"><?php pp_product_first_term_name_tag($product) ?>: <?php the_title(); ?></h1>
  </div>
  <div class="full-width-image">
    <div 
      class="full-width-image__background-layer background-parallax-layer" 
      data-ratio="0.15"
      style="background: url(<?php echo $image_url; ?>) no-repeat center center / cover, #333"></div>
  </div>

  <div class="site-container">
    <div class="main-content content-and-right-sidebar">
      <div class="__entry">

        <?php do_action('pp/product_single_start', $product); ?>

        <?php do_action('pp/product_single_before_content', $product); ?>

        <div id="POST_CONTENT" class="post-content pp-content"><?php the_content(); ?></div>
        
        <?php do_action('pp/product_single_after_content', $product); ?>

        <?php if($content_block && count($content_block)) {
          foreach($content_block as $_index => $item) {
        ?>
        <div id="PRODUCT_BLOCK_CONTENT_<?php echo $_index; ?>" class="pp__block-content">
          <h4 class="pp__block-content-title"><?php echo $item['title'] ?></h4>
          <div class="pp-content pp__block-content-entry"><?php echo $item['content'] ?></div>
        </div>
        <?php
          }
        } ?>

        <?php do_action('pp/product_single_end', $product); ?>

      </div>
      <div class="__sidebar">
        <div class="sidebar-container __sidebar-sticky">
          <?php do_action( 'pp/product_single_sidebar', $product ); ?>
        </div>
      </div>
    </div>
  </div>
</div>