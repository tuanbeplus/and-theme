<?php 
/**
 * Shop landing content
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

// var_dump($template_data);
?>
<div class="shop-lading">

  <div class="site-container">
    <h1 class="page-heading"><?php echo $template_data['title']; ?></h1>
  </div>

  <div class="full-width-image">
    <div 
      class="full-width-image__background-layer" 
      style="background: url(<?php echo $template_data['feature_image_url']; ?>) no-repeat center center / cover, #333"></div>
  </div>

  <div class="site-container">
    <div class="main-content content-and-right-sidebar">
      <div class="shop-entry __entry pp-content">
        <?php echo $template_data['content']; ?>
        <?php
        if($template_data['faqs'] && count($template_data['faqs']) > 0) {
          pp_faq_tags($template_data['faqs']);
        } ?>
      </div>
      <div class="shop-sidebar __sidebar">
        <?php do_action( 'pp/shop_landing_sidebar' ); ?>
      </div>
    </div>
  </div>
  
</div>