$(document).ready(function(){

    $(document).on('change', '#toggle', function(event){
        if (event.target.checked) {
            console.log('Toggle is ON');
        } else {
            console.log('Toggle is OFF');
        }
    })

    $(document).on('submit', '#leave_form', function(e){
        e.preventDefault()
        var self = $(this);

        var type = $(this).find("input[name='type']").val();
        var data = $(this).serialize();
        console.log('Type',type);

        bootbox.confirm({
            title: "File Leave",
            message: "Please check leave details before confirming",
            buttons: {
                cancel: {
                    label: ' Cancel'
                },
                confirm: {
                    label: ' Confirm'
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: 'post',
                        url: '/leaves/apply_leave',
                        data: data,
                        success: function (result) {
                            if (result) {
                                Swal.fire('Success', 'Leave has been filed!', 'success');
                            } else {
                                Swal.fire('Error', 'There was an error on the website please try again later or contact administrator.', 'error');
                            }
                            
                            type == 'sick_leave' ? $('#sick_leave_modal').modal('toggle') : $('#vacation_leave_modal').modal('toggle');

                            window.location.reload()
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }
            }
        });
    });
})