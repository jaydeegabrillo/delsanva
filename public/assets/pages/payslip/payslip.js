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
});