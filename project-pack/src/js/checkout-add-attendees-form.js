/**
 * Add Attendees
 */

((w, $) => {
  'use strict';
  
  const { ajax_url } = PP_DATA;
  const FORM_ID = '#ADD_ATTENDEES_FORM';

  function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
  }

  const findEmailSalesforceContacts = async (email) => {
    return await $.ajax({
      type: 'POST',
      url: ajax_url,
      data: {
        action: 'pp_ajax_find_contact_sf_by_email',
        email
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

  const resetSlotItem = ($tr) => {
    const organisationIdDefault = $tr.find('input[name^="organisation"]').data('default-value');
    const organisationTextDefault = $tr.find('.organisation-text').data('default-text');

    $tr.find('input[name^="firstname"]').val('').prop('readonly', false);
    $tr.find('input[name^="lastname"]').val('').prop('readonly', false);
    $tr.find('input[name^="organisation"]').val(organisationIdDefault);
    $tr.find('input[name^="contact_id"]').val('');
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

  const onEmailUpdate = () => {
    $('body').on('change', `${ FORM_ID } input[name^="email"]`, async function(e) {
      const email = e.target.value;
      const isEmail = validateEmail(email);
      const $table = $(this).closest('table');
      const $tr = $(this).closest('tr');

      if(isEmail != true) {
        resetSlotItem($tr);
        setStatus($tr, false);
        return;
      }

      let dup = checkDuplicateEmail(email, $table);
      console.log(dup);

      $tr.addClass('__loading')
      const { contact } = await findEmailSalesforceContacts(email);
      $tr.removeClass('__loading')
      
      if(contact) {
        updateSlotItem($tr, contact);
        setStatus($tr, true);
      } else {
        resetSlotItem($tr);
        setStatus($tr, false);
      }
    })
  }

  const init = () => {
    onEmailUpdate();
  }

  $(init)

})(window, jQuery)