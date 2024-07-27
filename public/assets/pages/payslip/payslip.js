$(document).ready(function(){
  var payslip_table = $("#payslips_table")

  payslip_table.DataTable({
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    responsive: true,
    pageLength: 50,
    processing: true,
    serverSide: true,
    order: [[1, "asc"]],
    ajax: "payslip/payslips-datatable",
    columnDefs: [
      { targets: 0, visible: false }, //first column is not orderable.
    ]
  });

  $(document).on('click', '.release_payslip', function(e){
    e.preventDefault();

    bootbox.confirm({
      title: "Release Payslips",
      message: "Make sure all the employees attendance is okay before releasing payroll",
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
            url: '/payslips/release_payslips',
            data: data,
            success: function (res) {
              if (res) {
                Swal.fire('Success', 'Payslips have been released!', 'success');
              } else {
                Swal.fire('Error', 'There was an error on the website please try again later or contact administrator.', 'error');
              }
            },
            error: function (err) {
              console.log(err);
            }
          });
        }
      }
    });
  });
});