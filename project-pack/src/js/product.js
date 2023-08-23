/**
 * Product 
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

import PP_BG_Parallax from './background-image-parallax';

;((w, $) => {
  'use strict';

  const featureImageParallaxFn = () => {
    let $layers = $('.background-parallax-layer');
    $layers.each(function() {
      new PP_BG_Parallax(this);
    })
  }

  const buttonVariationAddToCartFn = () => {
    const $btn = $('.pp-button-variation-add-to-cart');
    const $form = $('form.pp-form-product-variations');
    
    $btn.on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      if($form.length <= 0) return;

      $('html, body').stop(true, true).animate({
        scrollTop: $form.offset().top,
      }, 300)

      $form.trigger('submit');
    })
  }

  $(() => {
    featureImageParallaxFn();
    buttonVariationAddToCartFn();
  })

})(window, jQuery)