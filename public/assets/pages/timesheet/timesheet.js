$(document).ready(function(){
    const slug = 'timesheet';
    var timesheet_table = $("#timesheet_table")
    var timesheet_form = $('#timesheet_pdf_form');

    timesheet_table.DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        pageLength: 50,
        processing: true,
        serverSide: true,
        order: [[1, "asc"]],
        ajax: "timesheet/timesheet-datatable",
        columnDefs: [
            { targets: 0, orderable: false }, //first column is not orderable.
        ],
        fnRowCallback: function(nRow, aData){
            if (aData[2] == '00:00 AM' || aData[3] == '00:00 AM'){
                $('td', nRow).css(
                    {
                        'color': 'white',
                        'background-color': '#ff6855',
                    }
                );
            }
        }
    });

    timesheet_form.submit(function(e){
        e.preventDefault();
        var data = $(this).serialize();
        var url = slug + "/timesheet_pdf?" + data;
        window.open(url, '_blank');
    });

    $(document).on('click', '.edit_attendance', function(e){
        e.preventDefault();

        $('input[name="clock_in"]').val($(this).data('in'));
        $('input[name="clock_out"]').val($(this).data('out'));
        $('input[name="id"]').val($(this).data('id'));
    });

    $(document).on('hidden.bs.modal', '#edit_attendance', function(e){
        $('input[name="clock_in"]').val('');
        $('input[name="clock_out"]').val('');
        $('input[name="id"]').val('');
    })

    $(document).on('submit', '#update_attendance_form', function(e){
        e.preventDefault()

        var self = $(this);
        var data = self.serialize();
        
        $.ajax({
            url: 'timesheet/update_log/',
            type: 'get',
            data: data,
            success: function (result) {
                console.log(result);

                var success = JSON.parse(result).success;
                if (success) {
                    $('#edit_attendance').toggle()
                    $('.modal-backdrop').remove();

                    Swal.fire(
                        'Update',
                        'Log has been updated',
                    )

                    timesheet_table.DataTable().ajax.reload()
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    })

    $(document).on('click', '.delete_attendance', function(e){
        e.preventDefault()


    })
});
