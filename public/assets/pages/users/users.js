$(document).ready(function(){
    const origin = location.origin;
    var users_table = $("#users_table")
    var archives_table = $("#archives_table")

    users_table.DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        pageLength: 50,
        processing: true,
        serverSide: true,
        order: [[0, "asc"]],
        ajax: origin+"/users/users-datatable",
        columnDefs: [
            { targets: 0, orderable: false }, //first column is not orderable.
        ]
    });

    archives_table.DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        pageLength: 50,
        processing: true,
        serverSide: true,
        order: [[0, "asc"]],
        ajax: origin+"/users/archives-datatable",
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

    $(document).on('change','input[name="check_location"]', function(){
        var self = $(this);
        var checked = $(this).prop('checked');

        if(checked){
            self.val(1)
        } else {
            self.val(0)
        }
    });


    $(document).on('hidden.bs.modal', '#add_user_modal', function(e){
        $('#add_user_form').find('input').val('');
        $('input[name="id"]').val('');
        $('select[name="state"], select[name="country"]').val('').change();
        $('input[name="check_location"]').attr('checked', false);
        $('input[name="check_location"]').val('1');
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
                        if(index == 'check_location'){
                            if(value == 0){
                                $("input[name='"+index+"']").attr('checked', false);
                            } else {
                                $("input[name='"+index+"']").attr('checked', true);
                            }
                        } else {
                            $("input[name='" + index + "']").val(value)
                            $("select[name='" + index + "']").val(value).change()
                        }
                    });
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })

    $(document).on('click','.delete_user', function(e){
        e.preventDefault();

        var id = $(this).data('id');

        $('input[name="user_id"]').val(id);
    })

    $(document).on('click','.unarchive_user', function(e){
        e.preventDefault();

        var id = $(this).data('id');

        $('input[name="user_id"]').val(id);
        bootbox.confirm({
            title: "Unarchive User",
            message: "Are you sure you want to unarchive this user?",
            buttons: {
                cancel: {
                    label: ' Cancel'
                },
                confirm: {
                    label: ' Save'
                }
            },
            callback: function (result) {
                if(result){
                    $.ajax({
                        type: 'get',
                        url: '/users/unarchive_user',
                        data: { 'id': id },
                        success: function (result) {
                            if (result) {
                                Swal.fire('Success', 'User has been unarchived!', 'success');
                            } else {
                                Swal.fire('Error', 'There was an error on the website please try again later or contact administrator.', 'error');
                            }

                            archives_table.DataTable().ajax.reload();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }
            }
        });
    })

    $(document).on('submit', '#delete_user_form', function(e){
        e.preventDefault();

        var id = $('input[name="user_id"]').val();

        $.ajax({
            type: 'get',
            url: '/users/delete_user',
            data: {'id': id},
            success: function (result) {
                if (result) {
                    Swal.fire('Success', 'User has been archived!', 'success');
                } else {
                    Swal.fire('Error', 'There was an error on the website please try again later or contact administrator.', 'error');
                }

                users_table.DataTable().ajax.reload();
                $('#delete_user_modal').modal('toggle')
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
        $('select').prop('disabled', false);
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
                            if (index == 'check_location') {
                                if (value == 0) {
                                    $("input[name='" + index + "']").attr('checked', false);
                                } else {
                                    $("input[name='" + index + "']").attr('checked', true);
                                }
                            } else {
                                $("input[name='" + index + "']").val(value)
                                $("select[name='" + index +"']").val(value).change()
                            }
                        }
                    });
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })

    $(document).on('change', '#select_country', function(e){
        e.preventDefault();
        
        var value = $(this).val();
        var states = [];
        
        if(value == 'usa'){
            states = ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"];
        } else if(value == 'nigeria') {
            states = ["Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", "Borno", "Cross River", "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", "Gombe", "Imo", "Jigawa", "Kaduna", "Kano", "Katsina", "Kebbi", "Kogi", "Kwara", "Lagos", "Nasarawa", "Niger", "Ogun", "Ondo", "Osun", "Oyo", "Plateau", "Rivers", "Sokoto", "Taraba", "Yobe", "Zamfara"]
        } 
        
        if(value == 'philippines') {
            // states = ["Abra", "Agusan del Norte", "Agusan del Sur", "Aklan", "Albay", "Antique", "Apayao", "Aurora", "Basilan", "Bataan", "Batanes", "Batangas", "Benguet", "Biliran", "Bohol", "Bukidnon", "Bulacan", "Cagayan", "Camarines Norte", "Camarines Sur", "Camiguin", "Capiz", "Catanduanes", "Cavite", "Cebu", "Compostela Valley", "Cotabato", "Davao del Norte", "Davao del Sur", "Davao Occidental", "Davao Oriental", "Dinagat Islands", "Eastern Samar", "Guimaras", "Ifugao", "Ilocos Norte", "Ilocos Sur", "Iloilo", "Isabela", "Kalinga", "La Union", "Laguna", "Lanao del Norte", "Lanao del Sur", "Leyte", "Maguindanao", "Marinduque", "Masbate", "Misamis Occidental", "Misamis Oriental", "Mountain Province", "Negros Occidental", "Negros Oriental", "Northern Samar", "Nueva Ecija", "Nueva Vizcaya", "Occidental Mindoro", "Oriental Mindoro", "Palawan", "Pampanga", "Pangasinan", "Quezon", "Quirino", "Rizal", "Romblon", "Samar", "Sarangani", "Siquijor", "Sorsogon", "South Cotabato", "Southern Leyte", "Sultan Kudarat", "Sulu", "Surigao del Norte", "Surigao del Sur", "Tarlac", "Tawi-Tawi", "Zambales", "Zamboanga del Norte", "Zamboanga del Sur", "Zamboanga Sibugay", "Metro Manila"]
            $('input[name="address"]').parent().css('width','100%');
            $('input[name="apt"]').parent().hide();
            $('input[name="city"]').parent().parent().hide();
        } else {
            $('input[name="address"]').parent().css('width', '75%');
            $('input[name="apt"]').parent().show();
            $('input[name="city"]').parent().parent().show();
        }

        var state_options = '<option></option>';
        
        $.each(states, function (index, value) {
            state_options += '<option value="'+value+'">'+value+'</option>'
        });
        $('select[name="state"]').html(state_options)

    })
});
