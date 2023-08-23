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

      $(document.body).trigger('wc_fragment_refresh');

    });

    $(document.body).on('click', '.pp-product-qtt-update-ui .__increase', function(e) {
      e.preventDefault();
      const $wrap = $(this).parent();
      let key = $wrap.data('cart-item-key');
      let $qttInput = $wrap.find('input[type=number]');
      let oldValue = parseInt($qttInput.val());
      let newValue = oldValue += 1;

      $qttInput.val(newValue);
      $(document.body).trigger('pp:woo_update_qtt', [key, newValue])
    })

    $(document.body).on('click', '.pp-product-qtt-update-ui .__decrease', function(e) {
      e.preventDefault();
      const $wrap = $(this).parent();
      let key = $wrap.data('cart-item-key');
      let $qttInput = $wrap.find('input[type=number]');
      let oldValue = parseInt($qttInput.val());
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

      if(value <= 0) {
        this.value = 1
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

  $(() => {
    qttUpdateFn();
    afterAjaxUpdateCart();
    
    // removeCartItemFn();    
  }) 
})(window, jQuery) 