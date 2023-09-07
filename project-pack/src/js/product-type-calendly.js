;((w, $) => {
  'use strict';
  let currentPID = null;

  const calendlyEvents = {
    'calendly.event_type_viewed': (payload, currentPID) => {
      // init
      console.log(payload, currentPID);


    },
    'calendly.event_scheduled': (payload, currentPID) => {
      // Booking completed
      setTimeout(() => {
        Calendly.closePopupWidget();
      }, 2000)
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