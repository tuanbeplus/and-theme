/**
 * Product variations form 
 * 
 * @version 1.0.0
 * @since 1.0.0
 */
import { notificationGlobal } from "./general";

;((w, $) => {
  'use strict';

  const ProductVariationsForm = class {

    constructor($form) {
      this.$form = $form;
      this.productParentID = $form.data('product-id');
      this.variations = $form.data('variations');

      const self = this;

      this.getDataForm = () => {
        const data = self.$form.serializeArray();
        return self._makeData(data);
      }

      this.onUpdateForm();
      this.onSubmitForm();
      this.onKeypressControl();

      return this;
    }

    onKeypressControl() { 
      document.body.onkeyup = function(e){
      if(e.keyCode == 32 || e.keyCode == 13){
          // spacebar or enter 
          try {
            if(document.activeElement.classList.contains('product-variation-item-label')) {
              document.activeElement.click();
            }
          }
          catch (e) {
            console.log(e);
          }            
        }
      };
    }

    onUpdateForm () {
      const self = this;
      self.$form.on('change', function(e) {
        const $_form = $(this);
        $_form.trigger(
          'pp:product_variant_update_form', [
            self.getDataForm(), 
            self.variations, 
            self.productParentID
          ]);
      })
    } 

    onSubmitForm () {
      const self = this;
      self.$form.on('submit', function(e) {
        e.preventDefault();
        const $_form = $(this);
        $_form.trigger(
          'pp:product_variant_submit_form', [
            self.getDataForm(), 
            self.variations, 
            self.productParentID
          ]);
      })
    }

    _makeData (data) {
      return [...data].reduce((accumulator, currentValue) => {
        if(currentValue.name.indexOf('[]') === -1) {
          accumulator[currentValue.name] = currentValue.value;
        } else {
          let _name = currentValue.name.replace('[]', '');
          if(accumulator[_name]) {
            accumulator[_name].push(currentValue.value);
          } else {
            accumulator[_name] = [currentValue.value];
          }
        }
        
        return accumulator;
      }, {});
    }
  }

  $(() => {

    const displayGroup = () => {
      const $monthBucket = $('.month-bucket');
      if($monthBucket.length == 0) return;
      
      const isShow = $('#VARIABLE_OLD_EVENT_TOGGLE_INPUT').prop('checked');
      // console.log($monthBucket.length, isShow);
      $monthBucket.each(function() {
        const $self = $(this);
        const eventLength = $self.find('.product-variable-item').length;
        const oldEventLength = $self.find('.__old-event').length;
        // console.log(eventLength, oldEventLength);
        if(eventLength == oldEventLength) {
          if(isShow) {
            $self.css('display', 'block');
          } else {
            $self.css('display', 'none');
          }
        }
      })
    }

    displayGroup();

    $('body').on('change', '#VARIABLE_OLD_EVENT_TOGGLE_INPUT', e => {
      e.preventDefault();
      const isShow = e.target.checked;

      if(isShow) {
        $('.__old-event').addClass('__show')
      } else {
        $('.__old-event').removeClass('__show')
      }

      displayGroup();
    })

    $('form.pp-form-product-variations').each(function() {
      const $form = $(this);
      const $buttonSubmit = $form.find('button[type=submit]');
      const $btnWidgetBuy = $('.pp-button-choose-slots');
      // const btnWidgetBuy_initText = $btnWidgetBuy.text();

      new ProductVariationsForm($form);

      const updateFormUI = (_, formData, variations, productParentID) => {
        if(!formData.product_variation) {
          $buttonSubmit.text($buttonSubmit.data('template-no-item'));

          $btnWidgetBuy.each(function() {
            $(this).text($(this).data('text-init'))
          })
          // $btnWidgetBuy.text(btnWidgetBuy_initText);
          return;
        }
        
        let variationsSelected = variations.reduce((accumulator, currentValue) => {
          if(formData.product_variation.includes(currentValue.variation_id + '')) {
            accumulator.push(currentValue);
          }
          return accumulator;
        }, [])

        // console.log(variationsSelected.map((i) => i.variation_id), formData.product_variation);

        let __hasItemText = $buttonSubmit.data('template-has-item').replace('%CHECKED_NUMBER%', variationsSelected.length);

        if(parseInt(variationsSelected.length) > 1) {
          __hasItemText = $buttonSubmit.data('template-has-items').replace('%CHECKED_NUMBER%', variationsSelected.length);
        }

        $buttonSubmit.text(__hasItemText);
        $btnWidgetBuy.text(__hasItemText);
      }

      const submitFormUI = async (_, formData, variations, productParentID) => {
        if(!formData.product_variation) {  
          alert('Please select a slot.');
          return;
        }

        const $btnWidgetBuy = $('.pp-widget__buy .pp-button-choose-slots');
        $form.addClass('pp__loading');
        $btnWidgetBuy.css({
          opacity: .7,
          pointerEvents: 'none',
        })

        notificationGlobal('Loading...', 5)

        const result = await $.ajax({
          type: 'POST',
          url: PP_DATA.ajax_url,
          data: {
            action: 'pp_ajax_woo_product_variation_add_to_cart',
            data: {
              product: productParentID,
              variations: formData.product_variation,
            }
          },
          error: e => {
            console.error(e);
          }
        });

        $form.removeClass('pp__loading');
        $btnWidgetBuy.css({
          opacity: 1,
          pointerEvents: 'auto',
        })
        
        $('.pp-minicart').addClass('pp__loading');
        $(document.body).trigger('wc_fragment_refresh');
        $(document.body).trigger('pp:added_to_cart', [ result ]);
      } 

      $form.on('pp:product_variant_update_form', updateFormUI);
      $form.on('pp:product_variant_submit_form', submitFormUI);
    }) 
  })
})(window, jQuery);