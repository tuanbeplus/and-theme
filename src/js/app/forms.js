$(function(){

    var $ajax = '/wp-admin/admin-ajax.php';

    $('select:not([multiple])').selectric(); 

    $(document).on('submit','form#salesforce-login',function(e){

        $form = $(this);
        $formData = new FormData(this);
        $formData.append('action', 'doLogin');

        $.ajax({ 
            type: 'POST',
            dataType: 'json',
            url: $ajax,
            processData: false,
            contentType: false,
            data: $formData,
            success: function(data){
               
            }
        });
        e.preventDefault();
    });

}); 