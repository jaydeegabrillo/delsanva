$(document).ready(function(e){
    getLocation();

    $(document).on('click', '.clock_in', function(e){
        e.preventDefault();

        var check = $(this).data('check')
        var lat = $(this).data('latitude');
        var lon = $(this).data('longitude');
        var status = 0;
        var data =  '&lat='+lat+'&lon='+lon;
        
        if(check == 0){
            status = 1;
        } else {
            if(lat != '' && lon != ''){
                status = 1;
            }
        }
        //AIzaSyCqJUd2VUK0J7Hy0uyVsx7uAyyDS2PhtfU  api key geocoding
        if(status == 0) {
            Swal.fire( 'Error', 'Please enable geolocation', 'error' )
        } else {
            $.ajax({
                type: 'GET',
                url: 'dashboard/log',
                data: data,
                success: function (result) {
                    if (result == 'true') {
                        var now = new Date();
                        var time = now.toLocaleTimeString([], { hour12: true, hour: 'numeric', minute: '2-digit', second: '2-digit', hourCycle: 'h12', timeZone: 'America/Chicago' });
                        const [hours, minutes, seconds, ampm] = time.split(/:|\s/);

                        var curtime = hours + ":" + minutes + " " + ampm;

                        $('#for_in').removeClass('clock_in');
                        $('#for_out').addClass('clock_out');
                        $('p.attendance_status').html('Clocked in at ' + curtime)
                        $('p.attendance_out_status').html('You have not clocked out yet')
                    } else if (result == 'false') {
                        Swal.fire( 'Error', 'There was an error clocking in. Please try again later.', 'error' )
                    } else if(result == 'Invalid') {
                        Swal.fire( 'Error', 'Your address is invalid. Please update.', 'error' )
                    } else if (result == 'Denied') {
                        Swal.fire( 'Error', 'Request was denied. Please contact your administrator.', 'error' )
                    } else {
                        Swal.fire( 'Error', 'You are '+result+' miles away from address.', 'error' )
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

    })

    $(document).on('click', '.clock_out', function(e){
        e.preventDefault();

        var check = $(this).data('check')
        var lat = $(this).data('latitude');
        var lon = $(this).data('longitude');
        var status = 0;
        var data = '&lat=' + lat + '&lon=' + lon;

        if (check == 0) {
            status = 1;
        } else {
            if (lat != '' && lon != '') {
                status = 1;
            }
        }

        if (status == 0) {
            Swal.fire( 'Error', 'Please enable geolocation', 'error' )
        } else {
            $.ajax({
                type: 'GET',
                url: 'dashboard/log',
                data: data,
                success: function (result) {
                    if (result) {
                        $('#for_out').removeClass('clock_out');
                        $('#for_in').addClass('clock_in');
                        $('p.attendance_status').html('You have not clocked in yet')
                        $('p.attendance_out_status').html('You are clocked out')
                    } else if (result == 'false') {
                        Swal.fire('Error', 'There was an error clocking out. Please try again later.', 'error')
                    } else if (result == 'Invalid') {
                        Swal.fire('Error', 'Your address is invalid. Please update.', 'error')
                    } else if (result == 'Denied') {
                        Swal.fire('Error', 'Request was denied. Please contact your administrator.', 'error')
                    } else {
                        Swal.fire('Error', 'You are ' + result + ' miles away from address.', 'error')
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

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

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    // return position.coords;
    $('.clock_in, .clock_out').attr('data-latitude', position.coords.latitude);
    $('.clock_in, .clock_out').attr('data-longitude', position.coords.longitude);
}

function showError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            Swal.fire(
                'Error',
                'You have the denied the request for Geolocation',
                'error'
            );
            break;
        case error.POSITION_UNAVAILABLE:
            Swal.fire(
                'Error',
                'Location information is unavailable',
                'error'
            );
            break;
        case error.TIMEOUT:
            Swal.fire(
                'Error',
                'The request to get location timed out',
                'error'
            );
            break;
        case error.UNKNOWN_ERROR:
            Swal.fire(
                'Error',
                'An unknown error occurred',
                'error'
            );
            break;
    }
}

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
  const time = now.toLocaleTimeString([], { hour12: true, hour: 'numeric', minute: '2-digit', second: '2-digit', hourCycle: 'h12', timeZone: 'America/Chicago' });
  const [hours, minutes, seconds, ampm] = time.split(/:|\s/);

  document.querySelector('.hours').textContent = hours;
  document.querySelector('.minutes').textContent = minutes;
  document.querySelector('.seconds').textContent = seconds;
  document.querySelector('.ampm').textContent = ampm;
}

setInterval(updateTime, 1000);
