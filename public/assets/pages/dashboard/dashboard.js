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
                    var time = formatAMPM(new Date());

                    $('#for_in').removeClass('clock_in');
                    $('#for_out').addClass('clock_out');
                    $('p.attendance_status').html('Clocked in at ' + time)
                    $('p.attendance_out_status').html('You have not clocked out yet')
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })

    $(document).on('click', '.clock_out', function(e){
        e.preventDefault();

        var form_data = $(this).serialize()

        $.ajax({
            type: 'GET',
            url: 'dashboard/log',
            data: form_data,
            success: function (result) {
                if (result) {
                    $('#for_out').removeClass('clock_out');
                    $('#for_in').addClass('clock_in');
                    $('p.attendance_status').html('You have not clocked in yet')
                    $('p.attendance_out_status').html('You are clocked out')
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })

    $(document).on('submit', '#update_password_form', function(e){
        e.preventDefault();

        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'dashboard/update_password',
            data: data,
            success: function (result) {
                var alerts = JSON.parse(result);
                
                if (result) {
                    $('#update_password_modal').modal('toggle');
                    $('input').val('');

                    Swal.fire(
                        alerts.header,
                        alerts.message,
                        alerts.type
                    )
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


//for clock
function updateTime() {
  const now = new Date();
  const time = now.toLocaleTimeString([], { hour12: true, hour: 'numeric', minute: '2-digit', second: '2-digit', hourCycle: 'h12' });
  const [hours, minutes, seconds] = time.split(/:|\s/);

  document.querySelector('.hours').textContent = hours;
  document.querySelector('.minutes').textContent = minutes;
  document.querySelector('.seconds').textContent = seconds;

  const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
  document.querySelector('.ampm').textContent = ampm;
}

setInterval(updateTime, 1000);
