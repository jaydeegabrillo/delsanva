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

    $(document).on('keyup', '#salary', function(){
        var salary = $(this).val();

        // For Philhealth
        var philhealth_deduction = (salary * 0.05) / 2
        $("input[name='philhealth']").val(philhealth_deduction)

        // For Pag-ibig
        var pag_ibig = 200;
        $("input[name='pag-ibig']").val(pag_ibig)

        // For SSS
        let sss_deduction = 0;
        sss_deduction = get_sss_contribution(salary)
        $("input[name='sss']").val(sss_deduction)

        // For Tax
        let tax_deduction = 0;
        tax_deduction = get_tax_contribution(salary)
        $("input[name='tax']").val(tax_deduction)
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

function get_sss_contribution(salary) {
    let sss_contribution = 0;

    if(salary < 24750 && salary >= 24250) {
        sss_contribution = 1102.5;
    } else if (salary < 24250 && salary >= 23750) {
        sss_contribution = 1080;
    } else if (salary < 23750 && salary >= 23250) {
        sss_contribution = 1057.5;
    } else if (salary < 23250 && salary >= 22750) {
        sss_contribution = 1035;
    } else if (salary < 22750 && salary >= 22250) {
        sss_contribution = 1012.5;
    } else if (salary < 22250 && salary >= 21750) {
        sss_contribution = 990;
    } else if (salary < 21750 && salary >= 21250) {
        sss_contribution = 967.5;
    } else if (salary < 21250 && salary >= 20750) {
        sss_contribution = 945;
    } else if (salary < 20750 && salary >= 20250) {
        sss_contribution = 922.5;
    } else if (salary < 20250 && salary >= 19750) {
        sss_contribution = 900;
    } else if (salary < 19750 && salary >= 19250) {
        sss_contribution = 877.5;
    } else if (salary < 19250 && salary >= 18750) {
        sss_contribution = 855;
    } else if (salary < 18750 && salary >= 18250) {
        sss_contribution = 832.5;
    } else if (salary < 18250 && salary >= 17750) {
        sss_contribution = 810;
    } else if (salary < 17750 && salary >= 17250) {
        sss_contribution = 787.5;
    } else if (salary < 17250 && salary >= 16750) {
        sss_contribution = 765;
    } else if (salary < 16750 && salary >= 16250) {
        sss_contribution = 742.5;
    } else if (salary < 16250 && salary >= 15750) {
        sss_contribution = 720;
    } else if (salary < 15750 && salary >= 15250) {
        sss_contribution = 697.5;
    } else if (salary < 15250 && salary >= 14750) {
        sss_contribution = 675;
    } else if (salary < 14750 && salary >= 14250) {
        sss_contribution = 652.5;
    } else if (salary < 14250 && salary >= 13750) {
        sss_contribution = 630;
    } else if (salary < 13750 && salary >= 13250) {
        sss_contribution = 607.5;
    } else if (salary < 13250 && salary >= 12750) {
        sss_contribution = 585;
    } else if (salary < 12750 && salary >= 12250) {
        sss_contribution = 562.5;
    } else if (salary < 12250 && salary >= 11750) {
        sss_contribution = 540;
    } else if (salary < 11750 && salary >= 11250) {
        sss_contribution = 517.5;
    } else if (salary < 11250 && salary >= 10750) {
        sss_contribution = 495;
    } else if (salary < 10750 && salary >= 10250) {
        sss_contribution = 500; // Why is this higher 
    } else if (salary < 10250 && salary >= 9750) {
        sss_contribution = 450;
    } else if (salary < 9750 && salary >= 9250) {
        sss_contribution = 427.5;
    } else if (salary < 9250 && salary >= 8750) {
        sss_contribution = 405;
    } else if (salary < 8750 && salary >= 8250) {
        sss_contribution = 382.5;
    } else if (salary < 8250 && salary >= 7750) {
        sss_contribution = 360;
    } else if (salary < 7750 && salary >= 7250) {
        sss_contribution = 337.5;
    } else if (salary < 7250 && salary >= 6750) {
        sss_contribution = 315;
    } else if (salary < 6750 && salary >= 6250) {
        sss_contribution = 292.5;
    } else if (salary < 6250 && salary >= 5750) {
        sss_contribution = 270;
    } else if (salary < 5750 && salary >= 5250) {
        sss_contribution = 247.5;
    } else if (salary < 5250 && salary >= 4750) {
        sss_contribution = 225;
    } else if (salary < 4750 && salary >= 4250) {
        sss_contribution = 202.5;
    } else if (salary < 4250 && salary >= 3750) {
        sss_contribution = 180;
    } else if (salary < 3750 && salary >= 3250) {
        sss_contribution = 157.5;
    } else if (salary < 3250 && salary >= 1000) {
        sss_contribution = 135;
    } else {
        sss_contribution = 1125;
    }

    return sss_contribution;
}

function get_tax_contribution(salary){
    var tax_deduction = 0;

    if (salary >= 20833 && salary <= 33332) {
        tax_deduction = (salary - 20833) * 0.15;
    } else if (salary >= 33333 && salary <= 66666) {
        tax_deduction = (salary - 33333) * 0.20;
    } else if (salary >= 66667 && salary <= 166666) {
        tax_deduction = (salary - 66667) * 0.25;
    } else if (salary >= 166667 && salary <= 666666) {
        tax_deduction = (salary - 166667) * 0.30;
    } else if (salary >= 666667) {
        tax_deduction = (salary - 666667) * 0.35;
    }

    return tax_deduction;
}