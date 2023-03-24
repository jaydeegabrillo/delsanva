$(document).ready(function(){
    var users_table = $("#users_table")

    users_table.DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        pageLength: 50,
        processing: true,
        serverSide: true,
        order: [[1, "asc"]],
        ajax: "users/users-datatable",
        columnDefs: [
            { targets: 0, orderable: false }, //first column is not orderable.
        ]
    });

    $(document).on('submit', '#add_user_form', function(e){
        e.preventDefault();

        var form_data = $(this).serialize()

        $.ajax({
            type: 'GET',
            url: 'users/add_user',
            data: form_data,
            success: function (result) {
                if(result){
                    var alerts = JSON.parse(result)
                    $('#add_user_modal').modal('toggle');
                    Swal.fire( 'Success!', 'Successfully added new user', 'success' )
                    users_table.DataTable().ajax.reload();

                    Swal.fire(
                        alerts.header,
                        alerts.message,
                        alerts.type
                    )
                } else {
                    Swal.fire( 'Error!', 'There was an error adding the user', 'error' )
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    });

    $(document).on('hidden.bs.modal', '#add_user_modal', function(e){
        $('#add_user_form').find('input').val('');
        $('input[name="id"]').val('');
    })

    $(document).on('click','.view_user', function(e){
        var self = $(this);

        var id = self.data('id');
        $('input').prop('disabled',true);
        $('select').prop('disabled',true);
        $('button[type="submit"]').prop('disabled',true);
        $('.modal-title').html('User Details');

        $.ajax({
            type: 'get',
            url: '/users/get_user',
            data: {'id': id},
            success: function (result) {
                if (result) {
                    $.each(JSON.parse(result), function (index, value) {
                        $("input[name='"+index+"']").val(value)
                        $("select[name='"+index+"']").val(value).change()
                    });
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })

    $(document).on('click','.edit_user', function(e){
        var self = $(this);

        var id = self.data('id');
        $('input').prop('disabled', false);
        $('.modal-title').html('Edit User');
        $('button[type="submit"]').prop('disabled',false);

        $.ajax({
            type: 'get',
            url: '/users/get_user',
            data: {'id': id},
            success: function (result) {
                if (result) {
                    $.each(JSON.parse(result), function (index, value) {
                        if(index === 'password'){
                            $("input[name='"+index+"']").val('')
                        }else{
                            $("input[name='"+index+"']").val(value)
                        }
                    });
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })
});
