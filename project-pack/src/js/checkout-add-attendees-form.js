/**
 * Add Attendees
 */

((w, $) => {
  'use strict';
  
  const { ajax_url } = PP_DATA;
  const FORM_ID = '#ADD_ATTENDEES_FORM';
  let FormAddNewContact = null;
  let $trInprogress = null;

  function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
  }

  const findEmailSalesforceContacts = async (email, event_id) => {
    return await $.ajax({
      type: 'POST',
      url: ajax_url,
      data: {
        action: 'pp_ajax_find_contact_sf_by_email',
        email,
        event_id,
      },
      error: (err) => { console.log(err) }
    })
  }

  const updateSlotItem = ($tr, contact) => {
    const { __Account_Data, Id, FirstName, LastName, AccountId } = contact;
    $tr.find('input[name^="firstname"]').val(FirstName).prop('readonly', true);
    $tr.find('input[name^="lastname"]').val(LastName).prop('readonly', true);
    $tr.find('input[name^="organisation"]').val(AccountId);
    $tr.find('input[name^="contact_id"]').val(Id);
    $tr.find('.organisation-text').text(__Account_Data.Name);
  }

  const resetSlotItem = ($tr, without = []) => {
    const organisationIdDefault = $tr.find('input[name^="organisation"]').data('default-value');
    const organisationTextDefault = $tr.find('.organisation-text').data('default-text');

    $tr.find('input[name^="firstname"]').val('').prop('readonly', false);
    $tr.find('input[name^="lastname"]').val('').prop('readonly', false);
    $tr.find('input[name^="organisation"]').val(organisationIdDefault);
    $tr.find('input[name^="contact_id"]').val('');
    $tr.find('input[name^="relation_id"]').val('');
    $tr.find('input[name^="relation_id_child"]').val('');
    $tr.find('.organisation-text').text(organisationTextDefault);
  }

  const setStatus = ($tr, status) => {
    $tr.find('.__status-icon svg path').css('fill', status ? '#8BC34A' : '#9e9e9e');
  }

  const checkDuplicateEmail = (email, $container) => {
    let loop = 0;
    $container.find('input[name^="email"]').each(function() {
      let value = this.value.toLowerCase().trim();
      loop = ((value == email.toLowerCase().trim()) ? loop += 1 : loop);
    });

    return loop >= 2 ? true : false;
  }

  const errorMessageUI = ($td, message, status) => {
    if(status == true) {
      $td.addClass('show-error-message');
      $td.find('.error-message').text(message);
    } else {
      $td.removeClass('show-error-message');
      $td.find('.error-message').text(message);
    }
  }

  const onEmailUpdate = () => {
    // setTimeout(() => {
    //   console.log($(`${ FORM_ID } input[name^="email"]`).length);
    // }, 1000)

    $('body').on('change', `${ FORM_ID } input[name^="email"]`, async function(e) {
      const email = e.target.value;
      const sfEventID = $(this).data('event-parent-id');
      const isEmail = validateEmail(email);
      const $table = $(this).closest('table');
      const $tr = $(this).closest('tr');
      const $td = $(this).closest('td');

      $tr.removeClass('__invalid__');

      if(isEmail != true) {
        resetSlotItem($tr);
        setStatus($tr, false);
        return;
      }

      let dup = checkDuplicateEmail(email, $table);
      if(dup == true) {
        // Email attendees duplicate!
        errorMessageUI($td, '⚠️ Email attendees duplicate!', true);
        resetSlotItem($tr);
        setStatus($tr, false);
        return;
      }

      errorMessageUI($td, '', false);

      $tr.addClass('__loading')
      const { contact, joined } = await findEmailSalesforceContacts(email, sfEventID);
      $tr.removeClass('__loading');

      if(joined == true) {
        errorMessageUI($td, '⚠️ Email has already been registered for this event!', true);
        resetSlotItem($tr);
        setStatus($tr, false);
        return;
      }
      
      if(contact) {
        updateSlotItem($tr, contact);
        setStatus($tr, true);
      } else {
        FormAddNewContact.updateFields({
          c_email: $tr.find('input[name^="email"]').val(),
          f_name: $tr.find('input[name^="firstname"]').val(),
          l_name: $tr.find('input[name^="lastname"]').val(),
        });
        FormAddNewContact.show();
        $trInprogress = $tr;

        resetSlotItem($tr);
        setStatus($tr, false);
      }
    })
  }

  const popupAddNewContact = ({ onSubmit, onClose, onOpen }) => {
    const self = this;
    const $popup = $('.pp-popup-add-new-contact');
    const $form = $popup.find('form.add-new-contact-form');
    
    $form.on('submit', function(e) {
      onSubmit ? onSubmit($form, e) : '';
    })

    $popup.on('click', '.btn-close', function(e) {
      e.preventDefault();
      $('body').removeClass('__show-pp-popup-add-new-contact')
      onClose ? onClose($popup) : '';
    })

    return { 
      show: () => {
        $('body').addClass('__show-pp-popup-add-new-contact');
        onOpen ? onOpen($popup) : '';
      },
      hide: () => {
        $('body').removeClass('__show-pp-popup-add-new-contact')
        onClose ? onClose($popup) : '';
      },
      updateFields: (fields) => {
        Object.keys(fields).forEach(field => {
          $form.find(`input[name=${ field }]`).val(fields[field]);
        })
      }
    }
  }

  const addAttendeesFormValidates = () => {
    let pass = true;
    return pass;

    $(FORM_ID).find('input[name^=contact_id]').each(function(){ 
      let input = $(this);
      let $tr = input.closest('tr');
      let value = input.val();

      if(value == '') {
        pass = false;
        $tr.addClass('__invalid__');
      } 
    })

    return pass;
  }

  const addAttendeesFormSubmit = () => {
    $('body').on('submit', FORM_ID, async function(e) {
      e.preventDefault();
      const $form = $(this);
      let pass = addAttendeesFormValidates();
      if(pass != true) return;

      console.log(pass, $form.serialize());
      $form.find('button[type="submit"]').css({
        opacity: .1,
        PointerEvent: 'none',
      })
      // let data = $form.serialize() + '&action=pp_ajax_save_attendees_in_cart';
      let data = $form.serialize() + '&action=pp_ajax_save_attendees_to_order';
      //pp_ajax_save_attendees_in_cart
      const { success } = await $.ajax({
        type: 'POST',
        url: ajax_url,
        data: data,
        error: (err) => { console.log(err); }
      });

      if(success == true) {
        // stepUiController(2);
        // alert('Successfully.'); 
        w.location.reload();
      } else {
        alert('External Error: Please try again!');
      }
      
    })
  }

  /**
   * 
   * @param {*} activeStep 1 || 2
   */
  const stepUiController = (activeStep) => {
    switch(activeStep) {
      case 1:
        $('.step-checkout-bar .__step-add_attendees').addClass('__active').siblings().removeClass('__active');
        $('.add-attendees-container').show();
        $('form.checkout.woocommerce-checkout').hide();
        break;

      case 2:
        $('.step-checkout-bar .__step-checkout').addClass('__active').siblings().removeClass('__active');
        $('.add-attendees-container').hide();
        $('form.checkout.woocommerce-checkout').css('display', 'inline-flex');
        break;
    }
  }

  const removeSlot = () => {
    $(document.body).on('attendees:remove_slot', async function(e, order_id, EventRelation_Id, EventRelation_Id_Child, cb) {
      // console.log('__tigger', order_id, EventRelation_Id);
      const res = await $.ajax({
        type: 'POST',
        url: ajax_url,
        data: {
          action: 'pp_ajax_remove_slot_attendees',
          oid: order_id,
          rid: EventRelation_Id,
          rid_child: EventRelation_Id_Child,
        },
        error: (e) => {
          console.log(e);
          alert('Internal Error: Please reload page and try again!')
        }
      });
      cb(res);
    })

    $('body').on('click', 'form#ADD_ATTENDEES_FORM .__remove-item', function(e) {
      e.preventDefault();
      let r = confirm('Are you sure you wish to remove this attendee?');
      if(!r) return;

      let std = $(this).find('.__std').text();
      const { rid, ridChild, orderId } = this.dataset; 
      let $tr = $(this).closest('tr.__slot-item');
      console.log(rid, ridChild, orderId);

      $tr.addClass('__loading')
      $(document.body).trigger('attendees:remove_slot', [orderId, rid, ridChild, ({ success }) => {
        if(success == true) {
          resetSlotItem($tr);
          setStatus($tr, false);

          $tr.find('input[name^="email"]').val('')
          $tr.find('input[name^="email"]').removeAttr('readonly');
          $tr.find('.__slot-number').html(std); 
          $tr.removeClass('__loading')
        }
      }]);
    })
  } 

  const init = () => {
    onEmailUpdate();
    FormAddNewContact = new popupAddNewContact({
      onSubmit: async ($form, event) => {
        event.preventDefault();
        let fields = {
          "LastName": $form.find('input[name=l_name]').val(),
          "FirstName": $form.find('input[name=f_name]').val(),
          "Email": $form.find('input[name=c_email]').val(),
          // "AccountId": "0019q0000045pqRAAQ"
        }

        $form.addClass('pp-form-loading');

        const { success, responses, contact } = await $.ajax({
          type: 'POST',
          url: ajax_url,
          data: {
            action: 'pp_ajax_ppsf_add_new_contact',
            fields
          },
          error: (err) => { console.log(err) }
        });

        $form.removeClass('pp-form-loading');

        if(success != true) {
          alert('Internal Error: Please try again! \n' + JSON.stringify(responses));
          FormAddNewContact.hide();
          return;
        }

        updateSlotItem($trInprogress, contact);
        setStatus($trInprogress, true);
        $trInprogress = null;
        FormAddNewContact.hide();
      },
      onClose: () => {
        if($trInprogress == null) return;

        $trInprogress.find('input[name^=email]').val('')
        setStatus($trInprogress, false);
      }
    });

    removeSlot();
    addAttendeesFormSubmit();
    // stepUiController(2);
  }

  $(init)
  // $(w).on('load', init)

})(window, jQuery)