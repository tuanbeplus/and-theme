;((w, $) => {
  'use strict';
  const { ajax_url } = PP_DATA;
  let currentPID = null;

  const addProductToCart = async (product_id, extra_data) => {
    const responsive = await $.ajax({
      method: 'POST',
      url: ajax_url,
      data: {
        action: 'pp_add_product_calendly_to_cart',
        payload: {
          product_id, extra_data
        }
      },
      error: e => console.log(e)
    });

    return responsive;
  }

  const calendlyEvents = {
    'calendly.event_type_viewed': (payload, currentPID) => { // init
      
    },
    'calendly.event_scheduled': async (payload, currentPID) => { // Booking completed
      
      setTimeout(() => { 
        Calendly.closePopupWidget();
      }, 2000)

      await addProductToCart(currentPID, payload);
      w.location.href = '/cart';
    }
  };

  const calendlyReturn = () => {
    function isCalendlyEvent(e) {
      return e.origin === "https://calendly.com" && e.data.event && e.data.event.indexOf("calendly.") === 0;
    };
    
    w.addEventListener("message", function(e) {
      if(isCalendlyEvent(e)) {
        if(calendlyEvents[e.data.event]) {
          calendlyEvents[e.data.event].call('', e.data.payload, currentPID);
        }

        console.log(e.data)
        /* Example to get the name of the event */
        // console.log("Event name:", e.data.event);
        
        // /* Example to get the payload of the event */
        // console.log("Event details:", e.data.payload);
      }
    });
  }

  const onClickButtonBookingSlot = () => {
    $(document.body).on('click', '.pp-button-book-slot', function() {
      let $btn = $(this);
      let pid = $btn.data('pid');
      currentPID = pid;
    })
  }

  $(() => {
    onClickButtonBookingSlot();
    calendlyReturn();
  })

})(window, jQuery)