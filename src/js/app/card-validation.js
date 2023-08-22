
function formatString(e) {
    var inputChar = String.fromCharCode(event.keyCode);
    var code = event.keyCode;
    var allowedKeys = [8];
    if (allowedKeys.indexOf(code) !== -1) {
      return;
    }
  
    event.target.value = event.target.value.replace(
      /^([1-9]\/|[2-9])$/g, '0$1/' // 3 > 03/
    ).replace(
      /^(0[1-9]|1[0-2])$/g, '$1/' // 11 > 11/
    ).replace(
      /^([0-1])([3-9])$/g, '0$1/$2' // 13 > 01/3
    ).replace(
      /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2' // 141 > 01/41
    ).replace(
      /^([0]+)\/|[0]+$/g, '0' // 0/ > 0 and 00 > 0
    ).replace(
      /[^\d\/]|^[\/]*$/g, '' // To allow only digits and `/`
    ).replace(
      /\/\//g, '/' // Prevent entering more than 1 `/`
    );
}

$('input#card_holder').keyup(function(event){
    if($(this).val().length < 5) {
        $(this).removeClass('required'); 
    }
});
$('input#card_holder').keypress(function(event){
    var inputValue = event.which;
    if($(this).val().length > 5) {
        $(this).removeClass('required'); 
    }
    // allow letters and whitespaces only.
    if(!(inputValue >= 65 && inputValue <= 121) && (inputValue != 32 && inputValue != 0) && (inputValue !== 45)) { 
        event.preventDefault(); 
    }
});

$('input#card_number').keypress(function(event){
    var inputValue = event.which,
        value = $(this).val();

    if(inputValue >= 48 && inputValue <= 57) { 
        if($(this).val().length == 19) {
            event.preventDefault();
            $('input#expiry').focus();
        }
    } else {
        event.preventDefault(); 
    }

});

if($('input#card_number').length > 0) {
document.getElementById('card_number').oninput = function() {
    this.value = cc_format(this.value);
    if($('#card_number').val().length == 19) {
        $('#card_number').removeClass('required'); 
    } else {
        $('#ccard_number').addClass('required'); 
    }
  }
}

  function cc_format(value) {
    var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
    var matches = v.match(/\d{4,16}/g);
    var match = matches && matches[0] || ''
    var parts = []
    for (i=0, len=match.length; i<len; i+=4) {
      parts.push(match.substring(i, i+4))
    }
    if (parts.length) {
      return parts.join(' ')
    } else {
      return value
    }
  }
  

$('input#expiry_date').keyup(function(event){
    if($(this).val().length == 5) {
        $(this).removeClass('required'); 
    } else {
        $(this).addClass('required'); 
    }
});
$('input#expiry_date').keypress(function(event){
    
    if($(this).val().length == 5) {
        event.preventDefault();
        $('input#security_code').focus();
    }
    formatString(event);
});

$('input#security_code').keypress(function(event){
    var inputValue = event.which;
    if($(this).val().length == 2) {
        $(this).removeClass('required'); 
    }
    // allow letters and whitespaces only.
    if(inputValue >= 48 && inputValue <= 57) { 
           if($(this).val().length > 3) {
               event.preventDefault(); 
           }
    } else {
        $(this).addClass('required'); 
         event.preventDefault(); 
    }
}); 