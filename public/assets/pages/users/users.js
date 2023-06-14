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

    $(document).on('submit', '#delete_user_form', function(e){
        e.preventDefault();

        var id = $('input[name="user_id"]').val();

        $.ajax({
            type: 'get',
            url: '/users/delete_user',
            data: {'id': id},
            success: function (result) {
                console.log(result);
                if (result) {
                    Swal.fire('Success', 'User has been deleted!', 'success');
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
        var cities, states = [];
        
        if(value == 'usa'){
            // cities = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming' ];
            states = ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"];
        } else if(value == 'nigeria') {
            // cities = ["Lagos", "Abuja", "Kano", "Ibadan", "Kaduna", "Port Harcourt", "Benin City", "Maiduguri", "Zaria", "Aba", "Jos", "Ilorin", "Oyo", "Enugu", "Abeokuta", "Onitsha", "Warri", "Sokoto", "Calabar", "Katsina", "Akure", "Bauchi", "Ebute Ikorodu", "Makurdi", "Minna", "Effon Alaiye", "Ilesa", "Owo", "Umuahia", "Ondo", "Damaturu", "Ikot Ekpene", "Iwo", "Gombe", "Jimeta", "Gusau", "Mubi", "Osogbo", "Sagamu", "Ijebu Ode", "Ugep", "Nnewi", "Ise Ekiti", "Ila Orangun", "Saki", "Bida", "Awka", "Ijero Ekiti", "Inisa", "Suleja", "Sapele", "Omu-Aran", "Oturkpo", "Ikirun", "Obosi", "Idanre", "Lafia", "Ilobu", "Garki", "Funtua", "Gbongan", "Ejigbo", "Fiditi", "Okpoko", "Akpawfu", "Abakaliki", "Billiri", "Igboho", "Ilorin West", "Apomu", "Kuje", "Lere", "Kabba", "Yola", "Geidam", "Aku", "Deba", "Mokwa", "Nsukka", "Nkpor", "Ikerre", "Kontagora", "Yenagoa", "Jega", "Ijebu Igbo", "Buguma", "Uromi", "Nkwerre", "Lafiagi", "Fiditi", "Wukari", "Nasarawa", "Ado Odo", "Igbo Ora", "Gummi", "Ode", "Obonoma", "Gwadabawa", "Kisi", "Okrika", "Eket", "Gboko", "Ikom", "Yola", "Birnin Kebbi", "Gumel", "Gwaram", "Yenagoa", "Kontagora", "Gusau", "Birnin Kudu", "Azare", "Jalingo", "Kukawa", "Gashua", "Gombi", "Bama", "Konduga", "Takum", "Geidam", "Nguru", "Miringa", "Damboa", "Askira", "Kaga", "Marte", "Monguno", "Mafa", "Guzamala", "Dikwa", "Shani"];
            states = ["Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", "Borno", "Cross River", "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", "Gombe", "Imo", "Jigawa", "Kaduna", "Kano", "Katsina", "Kebbi", "Kogi", "Kwara", "Lagos", "Nasarawa", "Niger", "Ogun", "Ondo", "Osun", "Oyo", "Plateau", "Rivers", "Sokoto", "Taraba", "Yobe", "Zamfara"]
        } else if(value == 'philippines') {
            // cities = ["Quezon City", "Manila", "Caloocan", "Davao City", "Cebu City", "Zamboanga City", "Taguig", "Pasig", "Valenzuela", "Makati", "Cagayan de Oro", "Parañaque", "Las Piñas", "General Santos", "Bacoor", "Muntinlupa", "Pasay", "Tacloban", "Malabon", "Cainta", "Iloilo City", "Mandaluyong", "Marikina", "San Juan", "Bacolod", "Meycauayan", "Dagupan", "Iligan", "Lapu-Lapu", "Naga", "Lipa", "Lucena", "Binan", "Antipolo", "Calamba", "Mabalacat", "San Pedro", "Santa Rosa", "Bacoor", "Imus", "General Trias", "Angeles", "Tarlac City", "Puerto Princesa", "Kawit", "Santiago", "Legazpi", "Toledo", "Baliuag", "Rodriguez", "Meycauayan", "Tuguegarao", "Olongapo", "San Jose del Monte", "Silang", "Ormoc", "Kabankalan", "San Carlos", "Polomolok", "Cotabato City", "San Fernando", "Panabo", "Valencia", "Sorsogon City", "Kidapawan", "Calapan", "Alaminos", "Mati", "Tabaco", "Bayawan", "Cadiz", "Digos", "Roxas City", "Laoag", "Maramag", "Surigao City", "Bislig", "Tanauan", "Talisay", "Guihulngan", "Gingoog", "Dumaguete", "Himamaylan", "Dipolog", "Dasmariñas", "Trece Martires", "Tagum", "Bogo", "Masbate City", "Nasugbu", "Mataram", "Maasin", "Isabela City", "Marawi", "Gapan", "Hagonoy", "General Mariano Alvarez", "Meycauayan", "San Ildefonso", "Baguio", "San Miguel", "Cabanatuan", "Lucban", "Baler", "San Fernando", "Vigan", "Albay", "Cotabato City", "Iriga", "Koronadal", "Sorsogon City", "Gingoog", "Oroquieta", "Midsayap", "Zamboanga del Sur", "Dapitan", "Valencia", "Zamboanga del Norte", "Pilar", "Tandag", "Taytay", "Bislig", "Tacurong", "Malaybalay", "Lamitan", "Tagbilaran", "Iligan", "Bacolod", "Naga", "Butuan", "Kabankalan", "Pagadian", "San Carlos", "Surigao City", "Dipolog", "Iloilo City", "Cagayan de Oro", "Davao City", "General Santos", "Zamboanga City", "Baguio", "Olongapo", "Angeles", "Batangas City", "Lucena", "Puerto Princesa", "Tacloban", "Cebu City", "Mandaue", "Lapu-Lapu", "Taguig", "Pasay", "Valenzuela", "Parañaque", "Las Piñas", "Makati", "Malabon", "Muntinlupa", "Navotas", "Pasig", "Pateros", "Quezon City", "Marikina", "San Juan", "Caloocan", "Manila"];
            states = ["Abra", "Agusan del Norte", "Agusan del Sur", "Aklan", "Albay", "Antique", "Apayao", "Aurora", "Basilan", "Bataan", "Batanes", "Batangas", "Benguet", "Biliran", "Bohol", "Bukidnon", "Bulacan", "Cagayan", "Camarines Norte", "Camarines Sur", "Camiguin", "Capiz", "Catanduanes", "Cavite", "Cebu", "Compostela Valley", "Cotabato", "Davao del Norte", "Davao del Sur", "Davao Occidental", "Davao Oriental", "Dinagat Islands", "Eastern Samar", "Guimaras", "Ifugao", "Ilocos Norte", "Ilocos Sur", "Iloilo", "Isabela", "Kalinga", "La Union", "Laguna", "Lanao del Norte", "Lanao del Sur", "Leyte", "Maguindanao", "Marinduque", "Masbate", "Misamis Occidental", "Misamis Oriental", "Mountain Province", "Negros Occidental", "Negros Oriental", "Northern Samar", "Nueva Ecija", "Nueva Vizcaya", "Occidental Mindoro", "Oriental Mindoro", "Palawan", "Pampanga", "Pangasinan", "Quezon", "Quirino", "Rizal", "Romblon", "Samar", "Sarangani", "Siquijor", "Sorsogon", "South Cotabato", "Southern Leyte", "Sultan Kudarat", "Sulu", "Surigao del Norte", "Surigao del Sur", "Tarlac", "Tawi-Tawi", "Zambales", "Zamboanga del Norte", "Zamboanga del Sur", "Zamboanga Sibugay", "Metro Manila"]
        }

        var city_options, state_options = '<option></option>';
        
        $.each(states, function (index, value) {
            state_options += '<option value="'+value+'">'+value+'</option>'
        });
        $('select[name="state"]').html(state_options)

    })
});
