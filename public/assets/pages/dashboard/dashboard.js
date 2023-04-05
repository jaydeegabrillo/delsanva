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
  const hours = now.getHours().toString().padStart(2, '0');
  const minutes = now.getMinutes().toString().padStart(2, '0');
  const seconds = now.getSeconds().toString().padStart(2, '0');

  document.querySelector('.hours').textContent = hours;
  document.querySelector('.minutes').textContent = minutes;
  document.querySelector('.seconds').textContent = seconds;
}

setInterval(updateTime, 1000);
