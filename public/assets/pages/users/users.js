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
                if(result == 'true'){
                    $('#add_user_modal').modal('toggle');
                    Swal.fire( 'Success!', 'Successfully added new user', 'success' )
                    users_table.DataTable().ajax.reload();
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
    })
});
