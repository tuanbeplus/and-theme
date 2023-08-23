/**
 * Pull users data 
 */

((w, $) => {
  'use strict';

  const AJAX_URL = w.ajaxurl;

  const pullSFUserData = async ({ wpuid, sfuid }) => {
    return await $.ajax({
      type: 'POST',
      url: AJAX_URL,
      data: {
        action: 'pp_ajax_request_sf_user_data',
        wpuid, 
        sfuid
      }, 
      error: (e) => {
        console.log(e); 
      }
    })
  }

  const clickBtnPull = () => {
    $(document.body).on('click', '.pp-pull-sf-user-data', async function(e) {
      e.preventDefault();
      const $btn = $(this);
      const initBtnText = $btn.text();
      const { wpuid, sfuid } = this.dataset;

      $btn
        .css({
          'pointerEvents': 'none',
          'opacity': .7,
        })
        .text(`Pulling...`);

      const { success, response, updated_columns } = await pullSFUserData({ wpuid, sfuid });

      $btn
        .css({ 
          'pointerEvents': '',
          'opacity': 1,
        })
        .text(initBtnText);
       
      /**
       * Error
       */
      if(success != true) {
        alert(`${ response.errorCode }: ${ response.message }`);
        return;
      } 

      $.each(updated_columns, (selector, value) => {
        $(selector).html(value);
      })

      /**
       * Success
       */
      alert('Successfully!')
    })
  }

  $(() => {
    clickBtnPull();
  })
})(window, jQuery)