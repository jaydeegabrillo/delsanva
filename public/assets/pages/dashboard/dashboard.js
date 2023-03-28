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
                        $('p.attendance_status').html("You are not clocked in yet")
                        $('h3.clock_stat').html('Clock In');
                    }else{
                        var time = formatAMPM(new Date());
                        console.log(time, 'toym');
                        $('.clock_status').removeClass('bg-success')
                        $('.clock_status').addClass('bg-danger')
                        $('p.attendance_status').html('Clocked in at ' + time)
                        $('h3.clock_stat').html('Clock Out');
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

function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;

    return strTime;
}
