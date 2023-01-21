$(document).ready(function(e){
    
    $(document).on('click', '.clock_in', function(e){
        e.preventDefault();

        var form_data = $(this).serialize()

        $.ajax({
            type: 'GET',
            url: 'dashboard/log',
            data: form_data,
            success: function (result) {
                if (result) {
                    if($('.clock_status').hasClass('bg-danger')){
                        $('.clock_status').removeClass('bg-danger')
                        $('.clock_status').addClass('bg-success')
                        $('p.attendance_status').html("Today's Attendance Completed")
                        $('div.clock_in').hide()
                    }else{
                        $('.clock_status').removeClass('bg-success')
                        $('.clock_status').addClass('bg-danger')
                        $('p.attendance_status').html('You are now clocked in')
                    }
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