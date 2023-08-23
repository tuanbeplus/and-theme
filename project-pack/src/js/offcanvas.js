/**
 * Offcanvas 
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

;((w, $) => {
  'use strict';

  const offCanvasFn = function() {

    $(document.body).on('pp:offcanvas_open', function() {
      $(document.body).addClass('__pp-offcanvas-active');

      let focusInterval = setInterval(() => {
        let $closeBtn = document.querySelector('.pp-offcanvas .pp-btn__close');
        $closeBtn.focus();
        setTimeout(() => {
          clearInterval(focusInterval);
        }, 500)
      }, 1)
      
    })

    $(document.body).on('pp:offcanvas_close', function() {
      $(document.body).removeClass('__pp-offcanvas-active');
    })

    document.onkeydown = function(evt) {
      evt = evt || w.event;
      if (evt.keyCode == 27) {
        $(document.body).trigger('pp:offcanvas_close');
      }
    };
  }

  /**
   * Ready
   */
  $(() => {
    offCanvasFn();

    $(document.body).on('click', '.__open-pp-offcanvas', function(e) {
      e.preventDefault();
      $(document.body).trigger('pp:offcanvas_open');
    })

    $(document.body).on('click', '.pp-offcanvas .pp-btn__close', function(e) {
      e.preventDefault();
      $(document.body).trigger('pp:offcanvas_close');
    })

    $(document.body).on('click', function(e) {
      if($(e.target).hasClass('pp-offcanvas')) {
        e.preventDefault();
        $(document.body).trigger('pp:offcanvas_close');
      }
    })

    $(document.body).on('added_to_cart', function() {
      $(document.body).trigger('pp:offcanvas_open');
    });

    $(document.body).on('pp:added_to_cart', () => {
      $(document.body).trigger('pp:offcanvas_open');
    })
  })

})(window, jQuery);