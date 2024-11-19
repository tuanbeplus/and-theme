/**
 * cart
 */

import { doTippyGlobal, notificationGlobal } from "./general";

;((w, $) => {
  'use strict';

  const removeCartItemFn = () => {
    $(document.body).on('pp:wpp_remove_cart_item', async function(ev, cart_key) {
      const $miniCart = $('.pp-minicart');
      $miniCart.addClass('pp__loading');

      const result = await $.ajax({
        type: 'POST',
        url: PP_DATA.ajax_url,
        data: { 
          action: 'pp_ajax_woo_remove_cart_item',
          data: {
            cart_item_key: cart_key
          }
        }
      })

      $(document.body).trigger('wc_fragment_refresh');
    })

    $(document.body).on('click', '.pp-minicart a.remove_from_cart_button', function(e) {
      e.preventDefault();
      let key = $(this).data('cart_item_key');
      $(document.body).trigger('pp:wpp_remove_cart_item', [key])
    })
  }

  const qttUpdateFn = () => {
    $(document.body).on('pp:woo_update_qtt', async function(ev, cart_key, number) {
      const $miniCart = $('.pp-minicart');

      $miniCart.addClass('pp__loading');

      const result = await $.ajax({
        type: 'POST',
        url: PP_DATA.ajax_url,
        data: {
          action: 'pp_ajax_woo_update_qtt',
          data: {
            cart_item_key: cart_key,
            qtt_number: number
          }
        }
      })

      if(result?.successful == false) {
        notificationGlobal(result?.message, 3);
        $('.pp-minicart.pp__loading').removeClass('pp__loading');
        return;
      }

      $(document.body).trigger('wc_fragment_refresh');

    });

    $(document.body).on('click', '.pp-product-qtt-update-ui .__increase', function(e) {
      e.preventDefault();
      const $wrap = $(this).parent();
      let key = $wrap.data('cart-item-key');
      let $qttInput = $wrap.find('input[type=number]');
      let oldValue = parseInt($qttInput.val());
      let newValue = oldValue += 1;
      let maxValue = $qttInput.attr('max');

      if(maxValue && newValue > parseInt(maxValue)) {
        newValue = maxValue;
        $qttInput.val(newValue);
        return;
      }

      $qttInput.val(newValue);
      $(document.body).trigger('pp:woo_update_qtt', [key, newValue])
    })

    $(document.body).on('click', '.pp-product-qtt-update-ui .__decrease', function(e) {
      e.preventDefault();
      const $wrap = $(this).parent();
      let key = $wrap.data('cart-item-key');
      let $qttInput = $wrap.find('input[type=number]');
      let oldValue = parseInt($qttInput.val());
      let maxValue = $qttInput.attr('max');
      let newValue = oldValue -= 1;

      if(newValue <= 0) {
        return;
      }

      $qttInput.val(newValue);
      $(document.body).trigger('pp:woo_update_qtt', [key, newValue])
    })

    $(document.body).on('change', '.pp-product-qtt-update-ui input[type=number]', function(e) {
      e.preventDefault();
      const $wrap = $(this).parent();
      let key = $wrap.data('cart-item-key');
      let value = parseInt(this.value);
      let maxValue = $(this).attr('max');

      if(value <= 0) {
        this.value = 1;
      }

      if(maxValue && value > parseInt(maxValue)) {
        this.value = maxValue;
      }

      $(document.body).trigger('pp:woo_update_qtt', [key, this.value])
    })
  }

  const afterAjaxUpdateCart = () => {
    const __MESS = [
      {
        url: '/?wc-ajax=add_to_cart',
        message: 'Added to cart successfully! 👍',
      },
      {
        url: '/?wc-ajax=remove_from_cart',
        message: 'Removed item successfully! 👍',
      },
      {
        url: '/?wc-ajax=get_refreshed_fragments',
        message: 'Updated cart successfully! 👍',
      }
    ];

    $( document ).ajaxComplete((event, xhr, settings) => {
      let isNoti = __MESS.map(item => item.url).includes(settings.url);
      if(isNoti) {
        // apply tooltip
        setTimeout(() => { // delay append HTMl
          doTippyGlobal();
        }, 100)

        // updated cart successfully
        notificationGlobal(__MESS.find(item => item.url == settings.url).message, 3);
      }
    });
  }

  // Update quantity cart at Button Mini Cart after ajax complete
  $(document).ajaxComplete(function(event, xhr, settings) {
    const miniCartBtn_Top = $('a.__mini-cart-button');
    const miniCartBtn_Sidebar = $('.pp-nav__item.__cart a');
    // The AJAX add to cart request has completed
    if (settings.url.indexOf('wc-ajax=add_to_cart') !== -1
    || settings.url.indexOf('wc-ajax=remove_from_cart') !== -1
    || settings.url.indexOf('wc-ajax=get_refreshed_fragments') !== -1 
    ) {
      $.ajax({
        url: wc_add_to_cart_params.ajax_url, // WooCommerce's default AJAX URL
        type: 'GET',
        data: {
            action: 'pp_ajax_get_cart_contents_count'
        },
        success: function(response) {
          if (response.success) {
            const cartCount = response.data;
            miniCartBtn_Top.find('.quantity-cart').text(cartCount);
            miniCartBtn_Sidebar.find('.quantity-cart').text(cartCount);
          }
          else {
            console.log("Update quantity at button Mini Cart failed!");
          }
        }
      });
    }
  });

  $(() => {
    qttUpdateFn();
    afterAjaxUpdateCart();
    
    // removeCartItemFn();    
  }) 
})(window, jQuery) 