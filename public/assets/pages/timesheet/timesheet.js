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
});
