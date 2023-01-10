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
});