;((w, $) => {
  'use strict';

  class landingProduct {

    constructor( $element ) {
      this.$wrapper = $element;
      this.defineElem();

      this.maxPage = this.$wrapper.data('max-numpage');
      this.currentPage = this.$wrapper.data('current-page');
      this.items = this.$wrapper.data('items');
      this.s = '';
      this.cat = '';

      this.searchText();
      this.selectCat();
      this.loadMore();
      this.submitFormFilter();
    }

    defineElem() {
      this.$productCardElem = this.$wrapper.find('.product-card');
      this.$loopWrap = this.$wrapper.find('.product-card-style');
      this.$searchTextField = this.$wrapper.find('input[name=product_search]');
      this.$filterCatField = this.$wrapper.find('select[name=product_cat]');
      this.$btnLoadmore = this.$wrapper.find('.btn-loadmore');
      this.$formFilter = this.$wrapper.find('form.product-card-filter-form');
      this.$termContent = this.$wrapper.find('.product-term-content');
    }

    async __request(action, data) {
      return await $.ajax({
        type: 'POST',
        url: PP_DATA.ajax_url,
        data: {
          action,
          data
        },
        error: (e) => {
          console.error(e);
        }
      })
    }

    async get_products(append = true) {
      const self = this;
      const result = await self.__request('pp_get_product_card_style', {
        s: self.s,
        cat: self.cat,
        currentPage: self.currentPage,
        items: self.items,
        append,
      })


      if(result.success != true) {
        return;
      }

      self.maxPage = result.max_num_pages;

      if(result.append == true) {
        self.$loopWrap.append(result.content); // append item loadmore
      } else {
        self.$productCardElem.html($(result.content).html());
        self.defineElem();
      }

      /**
       * Term html before loop
       */
      if(result.term_html) {
        self.$termContent.html(result.term_html)
      } else {
        self.$termContent.empty();
      }

      if(result.max_num_pages > 1 && self.currentPage < self.maxPage) {
        self.$btnLoadmore.css({
          visibility: 'visible'
        })
      } else {
        self.$btnLoadmore.css({
          visibility: 'hidden'
        })
      }
    }

    loadMore() {
      const self = this;
      self.$btnLoadmore.on('click', async function(e) {
        e.preventDefault();
        self.currentPage += 1;

        self.$btnLoadmore.text('Loading...');
        self.$btnLoadmore.css({
          opacity: .7,
          pointerEvents: 'none'
        })

        await self.get_products(true);

        self.$btnLoadmore.text('View more');
        self.$btnLoadmore.css({
          opacity: 1,
          pointerEvents: 'auto'
        })
      })
    }

    searchText() {
      const self = this;
      self.$searchTextField.on('change', function() {
        self.currentPage = 1;
        self.s = this.value;
        // self.get_products(false);
      })
    }

    selectCat() {
      const self = this;
      self.$filterCatField.on('change', function() {
        self.currentPage = 1;
        self.cat = this.value; 
        // self.get_products(false);
      })
    }

    submitFormFilter() {
      const self = this;
      self.$formFilter.on('submit', async function(e) {
        e.preventDefault();
        let $btnSubmit = $(this).find('.btn-submit');

        self.$formFilter.css({
          opacity: .7,
          pointerEvents: 'none'
        })
        $btnSubmit.text('Searching...');

        await self.get_products(false);

        $btnSubmit.text('Submit');
        self.$formFilter.css({
          opacity: 1,
          pointerEvents: 'auto'
        })

        $('html, body').animate({
          scrollTop: self.$termContent.offset().top - 100
        }, 200)
      })
    }
  }

  $(() => {
    $('.landing-product').each(function() {
      new landingProduct( $(this) );
    })
    
  })
})(window, jQuery);