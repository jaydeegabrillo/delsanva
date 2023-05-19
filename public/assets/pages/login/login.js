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

    $(document).on('submit','#reset_password_form', function(e){
        e.preventDefault();

        var form_data = $(this).serialize();

        $.ajax({
            type: 'GET',
            url: 'send_forgot_password',
            data: form_data,
            beforeSend: function () {
                $('.send_request').html('Sending email...');
                $('.send_request').prop('disabled', true);
                $('.request_response').html('');
                $('.request_response').removeClass('btn btn-danger');
            },
            success: function (result) {
                if (result == 'true') {
                    $('.send_request').html('Email Sent');
                } else {
                    $('.request_response').addClass('btn btn-danger');

                    var html = 'This email does not exist on the system.';

                    if (result == 'false') {
                        html = 'Error sending email! Please try again later.';
                    }

                    $('.request_response').html(html);
                    $('.send_request').prop('disabled', false);
                    $('.send_request').html('Send Request');
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })
});