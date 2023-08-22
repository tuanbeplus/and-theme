(function( $ ){

  $(document).ready( function() {

    //get cookie
    var params = getUrlVars();
    if(params.length > 0 || $('.gform_wrapper').length > 0){
      // var userID = '';
      $(window).on('load',function(){
        var dataCookie = {
          'action': 'get_cookie_share',
        };

        var clientid = window.ga.getAll()[0].get('clientId');
        dataCookie['clientid'] = clientid;

        for (let key of params) {
          dataCookie[key] = params[key];
        }

        $.ajax({
          url: PJ_Global.ajax_url,
          data: dataCookie,
          type : 'POST',
          dataType: "json",
          cache:  'false',
          success: function success(cookie) {
            console.log(cookie);
            for (const key in cookie) {
              // $('#userId').val(cookie[key]);
            }
          }
        });
      });

    }
    function getUrlVars(){
        var vars = [], hash;
        var hashes = window.location.href.indexOf('?') > 0 ? window.location.href.slice(window.location.href.indexOf('?') + 1).split('&') : [];
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    var $arrEntry = [];
    $(document).on('gform_post_render', function(event, form_id, current_page){
      if ( form_id != 9 ) {
        return;
      }
      // console.log(current_page);
      $('#complatedPage').val( current_page );

      // Trigger Form by Steps
      $('#gform_wrapper_9 .gf_page_steps .gf_step.gf_step_completed').click( function() {
        let step_number = $(this).find('.gf_step_number').text();
        $("#gform_target_page_number_9").val(step_number);
        $("#gform_9").trigger("submit",[true]);
      });
      // End Trigger Form by Steps

      // Render Question
      let $text_question = $('#gform_wrapper_9 .gf_page_steps .gf_step.gf_step_active').text();
      $('#gform_9 .__title-quiz').text($text_question);
      // End Render Question

      // Control Data GForm
      var array_page = [];
      var array_field = [];

      $('#gform_wrapper_9 .gfield').each( function(index) {

        let radio_f = $(this).find('.gfield_radio');
        if ( radio_f.length && radio_f.find('input[type="radio"]:checked').val() != undefined ) {

          let array_radio = [];

          for (var i = 0; i < radio_f.length; i++) {
            let radio_name = radio_f.find('input[type="radio"]:checked').attr('name');
            let radio_id = radio_f.find('input[type="radio"]:checked').attr('id');
            let radio_val = radio_f.find('input[type="radio"]:checked').val();

            if ( radio_val != undefined ) {
              array_radio.push(radio_id);
              array_radio.push(radio_name);
              array_radio.push(radio_val);
            }
          }
          array_field.push(array_radio);

          array_page.push(array_radio);
        }

        let textArea_f = $(this).find('textarea');
        if ( textArea_f.length && textArea_f.val() != '' ) {
          let arrayTextArea = [];
          for (var i = 0; i < textArea_f.length; i++) {
            let textArea_name = textArea_f.attr('name');
            let textArea_id = textArea_f.attr('id');
            let textArea_value = textArea_f.val();

            arrayTextArea.push(textArea_id);
            arrayTextArea.push(textArea_name);
            arrayTextArea.push(textArea_value);
          }
          array_field.push(arrayTextArea);

          array_page.push(arrayTextArea);
        }

      });
      // End Control Data GForm
      $arrEntry.push(array_field);

      // var $dataEntryNext = array_page[array_page.length - 1];
      if ( array_page != undefined && array_page.length > 0 && current_page > 1 ) {
          // console.log(current_page);
          $.ajax({
            url: PJ_Global.ajax_url,
            type: 'POST',
            data: {
              'action' : 'and_save_data_next_step',
              'userId' : $('#userId').val(),
              'data'   : array_page,
              'currentPage' : current_page,
            },
            beforeSend: function() {
              $('html, body').animate({
                 scrollTop: $(".wrapper-quiz").offset().top - 80
              }, 250);
              $('.wrapper-quiz ._top .save_progress').addClass('progress');
              setTimeout( function() {
                $('.wrapper-quiz .wrap-quiz ._top .__right').find('span').fadeIn('slow');
                $('.wrapper-quiz ._top .save_progress').removeClass('progress');
              }, 3000);
              setTimeout(function(){
                $('.wrapper-quiz .wrap-quiz ._top .__right').find('span').fadeOut('fast');
              }, 4500);
            },
            success: function(response){
              $('.gf_page_steps .gf_step_completed').each( function(index) {
                let step_length = $('.gf_page_steps .gf_step_completed').length;
                if ( index ==  step_length - 1 ) {
                  // console.log( $(this).position().left );
                  let position_left = $(this).position().left;
                  $('.gf_page_steps').scrollLeft(position_left);
                }
              });
            }
          });
      }

    });

    $(document).on('gform_confirmation_loaded', function(event, formId){

      if ( formId != 9 ) {
        return;
      }

      $('.wrapper-quiz .wrap-quiz ._top').remove();
      $('.wrapper-quiz').addClass('confirm_loaded');
      $('.wrapper-quiz .on-this-page').addClass('active');

      function printData(){
         let home_url = window.location.origin;

         var divToPrint=document.getElementById("wrapperTablePrint");
         newWin= window.open("");
         newWin.document.write(divToPrint.outerHTML);
         newWin.print();
         newWin.close();
      }
      $('.submission-success-wrapper .submission-content a#printEntry').on('click',function(e){
        e.preventDefault();
        printData();
      });

      // Get username to remove in database
      let userID = $('#userId').val();
      $.ajax({
        url: PJ_Global.ajax_url,
        type: 'POST',
        data: {
          'action' : 'and_clear_data_gform',
          'userID' : userID
        },
        success: function( response ){
        }
      });

    });

    $(document).on('click', '.wrapper-quiz ._top .save_progress' ,function(e) {
      e.preventDefault();

      if ( $('#complatedPage').val() > 1 ) {
        $(this).addClass('progress');
        $.ajax({
          url: PJ_Global.ajax_url,
          type: 'POST',
          data: {
            'action' : 'control_data_entry_gform',
            'userId' : $('#userId').val(),
            'step'   : $('#complatedPage').val(),
            'arrayEntry' : $arrEntry[$arrEntry.length - 1]
          },
          success: function(response){

            setTimeout(function() {
              $('.wrapper-quiz .wrap-quiz ._top .__right').find('span').fadeIn('slow');
              $('.wrapper-quiz ._top .save_progress').removeClass('progress');
            }, 3000 );
            setTimeout(function(){
              $('.wrapper-quiz .wrap-quiz ._top .__right').find('span').fadeOut('fast');
            }, 4500);
          }
        });
      }

    });

    apply_data_for_gform();
    function apply_data_for_gform(){
      $(window).on('load', function() {
          $.ajax({
              url: PJ_Global.ajax_url,
              type: "POST",
              data: {
                'action' : 'and_apply_data_to_form',
                'usernameId' : $('#userId').val(),
              },
              success: function success( response ){
                // console.log(response);
                if ( response != '' ) {
                  let username = response['username'];
                  let step = response['step'];

                  if ( username != $('#userId').val() ) {
                    return;
                  }

                  $("#gform_target_page_number_9").val( step );
                  $("#gform_9").trigger("submit",[true]);

                  $('html, body').animate({
                     scrollTop: $(".wrapper-quiz").offset().top - 80
                  }, 250);

                }
              },
              error: function (error) {
                console.log(error);
              }

          });
      });
    }

  });

})( jQuery );
