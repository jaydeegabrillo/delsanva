$(document).ready(function(e){
    console.log("test dashboard");
    $(document).on('click', '.clock_in', function(e){
        e.preventDefault();

        var form_data = $(this).serialize()

        $.ajax({
            type: 'GET',
            url: 'dashboard/log',
            data: form_data,
            success: function (result) {
                if (result == 'true') {
                    window.location.href = window.location.origin;
                    // $('.clock_status').removeClass('')
                } else {
                    $('button.alert').removeClass('hide');
                    $('button.alert').addClass('show');
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })
});