;((w, $) => {
  'use strict';

  const calendlyEvents = {
    'calendly.event_scheduled': (payload) => {
      console.log(payload)
    }
  };

  const calendlyReturn = () => {
    function isCalendlyEvent(e) {
      return e.origin === "https://calendly.com" && e.data.event && e.data.event.indexOf("calendly.") === 0;
    };
    
    w.addEventListener("message", function(e) {
      if(isCalendlyEvent(e)) {
        /* Example to get the name of the event */
        if(calendlyEvents[e.data.event]) {
          calendlyEvents[e.data.event].call('', e.data.payload);
        }
        
        console.log("Event name:", e.data.event);
        
        // /* Example to get the payload of the event */
        console.log("Event details:", e.data.payload);
      }
    });
  }

  const onClickButtonBookingSlot = () => {
    $(document.body).on('click', '.pp-button-book-slot', function() {
      let $btn = $(this);
      let pid = $btn.data('pid');
      localStorage.setItem('__product_booking_current_id', pid);
    })
  }

  $(() => {
    onClickButtonBookingSlot();
    calendlyReturn();
  })

})(window, jQuery)