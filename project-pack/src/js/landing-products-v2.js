import { notificationGlobal } from "./general";

;((w, $) => {
  'use strict';

  class LandingProductV2 {

    constructor($element) {
      this.$container = $element;
      this.__s = '';
      this.__termID = '';
      this.triggerActions();
      this.loadMore();
      this.filterForm();
    }

    async __request(action, data) {

      const _n = notificationGlobal('Loading...', false);
      const result = await $.ajax({
        type: 'POST',
        url: PP_DATA.ajax_url,
        data: {
          action,
          data
        },
        error: (e) => {
          console.log(e);
        }
      })

      _n.remove();
      return result;
    }

    triggerActions() {
      const self = this;

      // Loadmore
      // termID, paged, numberPerPage
      $(document.body).on('LandingProductV2:loadmore', async (e, params, callback) => {
        const result = await self.__request('pp_ajax_product_loadmore', params);
        callback.call('', result);
      })
      
      // Filter & search 
      $(document.body).on('LandingProductV2:filter', async (e, params, callback) => {
        const result = await self.__request('pp_ajax_product_filter_v2', params);
        callback.call('', result);
      })
    }

    getMetaPaged($termContainer) {
      return $termContainer.data();
    }

    setMetaPaged($termContainer, name, value) {
      $termContainer.data(name, value);
    }
  
    loadMore() {
      const self = this;

      // No term 
      this.$container.on('click', '.product-landing-v2-no-term .btn-loadmore', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const $wrapElem = $btn.parents('.product-landing-v2-no-term');
        const $productContainer = $wrapElem.find('.product-card-style');
        const metaPaged = self.getMetaPaged($wrapElem);
        const nextPaged = (metaPaged.currentPage + 1);

        $btn.addClass('pp__loading');
        $(document.body).trigger('LandingProductV2:loadmore', [
          {
            s: self.__s,
            paged: nextPaged, 
            numberPerPage: metaPaged.items,
          },
          (result) => {
            const { success, content } = result;

            $btn.removeClass('pp__loading');

            if(success != true) return;
            $productContainer.append(content);
            self.setMetaPaged($wrapElem, 'current-page', nextPaged);

            if(nextPaged >= metaPaged.maxNumpage ) {
              $btn.remove();
            }
          }
        ])
      })

      // With term
      this.$container.on('click', '.product-by-group-term .btn-loadmore', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const $productContainer = $btn.closest('.product-by-group-term').find('.product-card-style');
        const $wrapElem = $btn.parents('.product-by-group-term');
        const metaPaged = self.getMetaPaged($wrapElem);
        const nextPaged = (metaPaged.currentPage + 1);
        // console.log(metaPaged);

        $btn.addClass('pp__loading');
        
        $(document.body).trigger('LandingProductV2:loadmore', [
          { 
            s: self.__s,
            termID: metaPaged.termId,
            paged: nextPaged,
            numberPerPage: metaPaged.items
          },
          (result) => {
            const { success, content } = result;

            $btn.removeClass('pp__loading');

            if(success != true) return;
            $productContainer.append(content);
            self.setMetaPaged($wrapElem, 'current-page', nextPaged);

            if(nextPaged >= metaPaged.maxNumpage ) {
              $btn.remove();
            }
          }
        ])
      })
    }

    filterForm() {
      const self = this;
      self.$container.on('submit', 'form.product-card-filter-form', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btnSubmit = $form.find('button[type="submit"]');
        const postPerPage = self.$container.data('post-per-page-for-search');
        const loadmore = self.$container.data('loadmore');

        self.__s = $form.find('input[name="product_search"]').val();
        self.__termID = $form.find('select[name="product_cat"]').val();

        $form.addClass('pp__loading');
        $btnSubmit.text('Searching...')

        $(document.body).trigger('LandingProductV2:filter', [{
          's': self.__s,
          'term': self.__termID,
          'posts_per_page': postPerPage,
          'paged': 1,
          'loadmore': loadmore
        }, (result) => {
          // console.log(result)
          // .landing-product__entry

          $form.removeClass('pp__loading');
          $btnSubmit.text('Search');

          if(result.success != true) return;
          self.$container.find('.landing-product__entry').html(result.content);
          
        }])
      })
    }
  }

  $(() => {
    $('.landing-product-v2').each(function() {
      new LandingProductV2($(this));
    })
  })

})(window, jQuery);