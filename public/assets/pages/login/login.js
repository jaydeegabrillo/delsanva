$(document).ready(function(){

    $(document).on('submit','#login_form', function(e){
        e.preventDefault();

        var form_data = $(this).serialize()

        $.ajax({
            type: 'GET',
            url: 'login/login',
            data: form_data,
            success: function (result) {
                console.log(result,'res');
                if (result == 'true') {
                    window.location.href = window.location.origin;
                } else {
                    $('button.alert').removeClass('hide');
                    $('button.alert').addClass('show');
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    });
});