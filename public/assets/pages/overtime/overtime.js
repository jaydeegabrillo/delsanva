$(document).ready(function(){
  let overtime_requests_table = $("#overtime_requests_table")

  overtime_requests_table.DataTable({
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    responsive: true,
    pageLength: 50,
    processing: true,
    serverSide: true,
    order: [[0, "asc"]],
    ajax: {
      url: origin + "/overtime/overtime-requests-datatable",
      data: function (d) {

      }
    },
    columnDefs: [
      { targets: 0, visible: false }, //first column is not visible.
    ]
  });

  $("#time_start, #time_end").on("change", function(){
    let time_start = $("#time_start").val()
    let time_end = $("#time_end").val()

    if (time_start && time_end) {
      // Convert time strings to Date objects
      var start = new Date('1970-01-01T' + time_start + 'Z');
      var end = new Date('1970-01-01T' + time_end + 'Z');

      // Calculate the difference in milliseconds
      var diff = end - start;

      // Convert the difference to hours
      var diffHours = diff / (1000 * 60 * 60);

      // Handle case where end time is on the next day
      if (diffHours < 0) {
        diffHours += 24;
      }

      // Display the result
      $('.ot_hours').text(diffHours);
      $("#hours").val(diffHours)
    } else {
      $('.ot_hours').text(0);
    }

  });

  $(document).on("submit", "#ot_form", function(e){
    e.preventDefault();

    var self = $(this)
    var data = self.serialize();

    bootbox.confirm({
      title: "File OT",
      message: "Please check OT details before confirming",
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
            url: '/overtime/apply_ot',
            data: data,
            success: function (result) {
              console.log("Res", result);
              if (result) {
                Swal.fire('Success', 'OT has been filed!', 'success');
              } else {
                Swal.fire('Error', 'There was an error on the website please try again later or contact administrator.', 'error');
              }

              $("#overtime_modal").modal('toggle')
            },
            error: function (err) {
              console.log(err);
            }
          });
        }
      }
    });
  });

  $(document).on("click", "#review_filed_ot", function () {
    var id = $(this).data('id');

    bootbox.dialog({
      title: "Review Filed OT",
      message: "Please check leave details before confirming",
      buttons: {
        decline: {
          label: "Decline",
          className: 'btn-danger',
          callback: function () {
            update_ot_status(id, 2);
          }
        },
        approve: {
          label: "Approve",
          className: 'btn-success',
          callback: function () {
            update_ot_status(id, 1);
          }
        },

      }
    });
  });

  function update_ot_status(id, status) {
    var data = { id: id, status: status };

    $.ajax({
      type: 'get',
      url: '/overtime/update-ot-status/',
      data: data,
      success: function (result) {
        var res = JSON.parse(result);
        var status = "approved";

        if (res.response) {
          if (res.status == "2") {
            status = "declined";
          }
          Swal.fire('Requested leave has been ' + status);
        } else {
          Swal.fire('Error', 'There was an error on the website please try again later or contact administrator.', 'error');
        }

        leave_requests_table.DataTable().ajax.reload();
      },
      error: function (err) {
        console.log(err);
      }
    });
  }
});