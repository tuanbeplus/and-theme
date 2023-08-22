(function( $ ){

  $(document).ready( function() {
    // validate_field();
    $('.gform_next_button, #gform_submit_button').click( function(e) {
      let $btn_next = $(this);
      $btn_next.parents('.gform_page').find('.gform_page_fields .gfield.gfield_contains_required').each( function() {

        let field_radio = $(this).find('.gfield_radio');
        let field_textarea = $(this).find('.ginput_container textarea');
        // console.log(field_textarea.val());

        let $validate_mess = $('<div class="validation_message">This field is required.</div>');

        if ( field_radio.length > 0 ) {
          for (var i = 0; i < field_radio.length; i++) {
            let radio_f = field_radio.find('input[type="radio"]');
            let radio_val = field_radio.find('input[type="radio"]:checked').val();

            if ( radio_val == undefined ) {
              if ( radio_f.parents('.gfield').find('.validation_message').length == 0 ) {
                radio_f.parents('.gfield').append($validate_mess);
              }
            }else{
              radio_f.parents('.gfield').find('.validation_message').remove();
            }
          }
        }

        if ( field_textarea.length > 0) {
          for (var i = 0; i < field_textarea.length; i++) {
            let textarea_val = field_textarea.val();
            // console.log(field_textarea.parents('.gfield '));
            if ( textarea_val == undefined || textarea_val == '' ) {
              if ( field_textarea.parents('.gfield ').find('.validation_message').length == 0 ) {
                field_textarea.parents('.gfield ').append($validate_mess);
              }
            }else{
              field_textarea.parents('.gfield').find('.validation_message').remove();
            }
          }
        }


      });

      if ( $('.gform_page.active').find('.validation_message').length == 0 ) {
        let next_page = $btn_next.data('page');
        let prev_page = $btn_next.data('page') - 1;
        if ( next_page ) {
          $('.gform_page').removeClass('active');
          $('#gform_page_'+ next_page).addClass('active');
          $('#gf_step_'+ prev_page).addClass('gf_step_completed');

          $('#gf_step_'+ next_page).removeClass('gf_step_pending');
          $('.gf_step').removeClass('gf_step_active');
          $('#gf_step_'+ next_page).addClass('gf_step_active');
        }
        if ( prev_page ) {
          // $('#gf_step_'+ next_page).removeClass('gf_step_completed');
          // $('#gf_step_'+ next_page).removeClass('gf_step_active');
        }
        // $('#gform_submit_button').prop('disabled', false);
      }else{
        // $('#gform_submit_button').prop('disabled', true);
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        return false;
      }

    });

    $('.gform_previous_button').click( function() {
      let prev_page = $(this).data('page');
      if ( prev_page ) {
        $('.gform_page').removeClass('active');
        $('#gform_page_'+ (prev_page) ).addClass('active');

        $('#gf_step_'+ prev_page).removeClass('gf_step_completed');
        $('#gf_step_'+ prev_page).addClass('gf_step_active');
      }
    });

    $(document).on('change', 'input, textarea', function() {
      if ( $(this).val() != undefined || $(this).val() != '' ) {
        $(this).parents('.gfield').find('.validation_message').remove();
      }
    });

    // Choose Step
    $(document).on('click', '.gf_step.gf_step_completed', function() {
      var $step = $(this).find('.gf_step_number').text();
      var $current_step = $(this).index();

      $('.gf_step').each( function(index) {
        var g_step = $(this);
        if ( index >= $current_step  ) {
          g_step.removeClass('gf_step_completed');
          g_step.addClass('gf_step_pending');
        }else{
          g_step.addClass('gf_step_completed');
        }
      });

      // console.log($(this).index());

      $(this).removeClass('gf_step_completed');

      $('.gform_body .gform_page').removeClass('active');
      $('.gform_body').find( '#gform_page_' + $step ).addClass('active');

    });
    // $('.gf_step.gf_step_completed').click( function() {
    //
    // });

  });

})( jQuery );
