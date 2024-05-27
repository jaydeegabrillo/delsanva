$(document).ready(function(){

    const origin = location.origin;
    var leave_requests_table = $("#leave_requests_table")

    leave_requests_table.DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        pageLength: 50,
        processing: true,
        serverSide: true,
        order: [[0, "asc"]],
        ajax: {
            url: origin + "/leaves/leave-requests-datatable",
            data: function (d) {
                d.leave_type = $('#leave_type').val();
                d.leave_status = $('#leave_status').val();
            }
        },
        columnDefs: [
            { targets: 0, orderable: false }, //first column is not orderable.
        ]
    });

    $(document).on('change', '#toggle', function (event) {
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

    // Event listeners for the dropdowns
    $(document).on("change", "#leave_type, #leave_status", function () {
        leave_requests_table.DataTable().ajax.reload();
    });

    $(document).on("click", "#review_filed_leave", function(){
        var id = $(this).data('id');

        bootbox.dialog({
            title: "Review Filed Leave",
            message: "Please check leave details before confirming",
            buttons: {
                decline: {
                    label: "Decline",
                    className: 'btn-danger',
                    callback: function () {
                        update_leave_status(id, 2);
                    }
                },
                approve: {
                    label: "Approve",
                    className: 'btn-success',
                    callback: function () {
                        update_leave_status(id, 1);
                    }
                },

            }
        });
    });

    function update_leave_status(id, status){
        var data = {id: id, status: status};

        $.ajax({
            type: 'get',
            url: '/leaves/update-leave-status/',
            data: data,
            success: function (result) {
                var res = JSON.parse(result);
                var status = "approved";

                if (res.response) {
                    if(res.status == "2"){
                        status = "declined";
                    }
                    Swal.fire('Requested leave has been ' + status);
                } else {
                    Swal.fire('Error', 'There was an error on the website please try again later or contact administrator.', 'error');
                }

                leave_requests_table.DataTable().ajax.reload();
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
})