/**
 * FAQs Content of table 
 */

;(() => {
  'use strict';

  const FAQsContentOfTableFn = class {
    
    constructor ($wrapper) {
      this.$wrapper = $wrapper;
      this.navList = [];
      this.buildNavigation();

      // console.log(this.navList);
      let content = this.buildNavigationHTML();
      this.$wrapper.parent().find('.faqs-nav').append(content);
    }

    randID () {
      return "key__" + Math.random().toString(36).replace('0.', '');
    } 

    buildNavigation () {
      let self = this;
      self.$wrapper.find('.faqs-block__item').each(function() {
        let _QID = self.randID();
        $(this).attr('id', _QID);

        let q = $(this).find('.faqs-block__item-heading h4').text();
        let a = $(this).find('.faqs-block__item-body');
        let child = [];

        a.find('h1, h2, h3, h4, h5, h6').each(function() {
          let _ID = self.randID();
          $(this).attr('id', _ID)
          let text = $(this).text();
          child.push({
            heading: text,
            selector: `#${ _ID }`,
          });
        })

        let item = {
          heading: q,
          selector: `#${ _QID }`
        }

        if(child.length > 0) {
          item.child = child;
        }

        self.navList.push(item);
      })
    }

    buildNavigationHTML () {
      const self = this;
      let $_HTML = $('<ul>');

      self.navList.map((item) => {
        $_HTML.append(`<li>
          <a href="${ item.selector }">${ item.heading }</a>
          ${ ((item) => {
            if(!item.child) return '';
            let _child = item.child;
            
            return `<ul>
              ${ _child.map((_item) => {
                return `<li><a href="${ _item.selector }">${ _item.heading }</a></li>`
              }).join('') }
            </ul>`
          })(item) }
        </li>`)
      }).join('')

      return $_HTML;
    }
  }

  $(() => {
    $('.faq-content-of-table-target').each(function() {
      new FAQsContentOfTableFn($(this));
    })
  });
})(window, jQuery)