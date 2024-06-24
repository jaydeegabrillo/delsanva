<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Payslip Details</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- <link rel="stylesheet" href="assets/plugins/css/all.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/plugins/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/plugins/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="assets/plugins/css/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/css/adminlte.min.css?v=3.2.0">
    <link rel="stylesheet" href="assets/plugins/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/plugins/css/daterangepicker.css">

    <!-- DataTable -->
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css?v=3.2.0">
    <style>
        #earnings_table, #deductions_table{
            width: 100%;
            border: 1px solid #20aee3;
            padding: 0;
            margin: 0;
        }

        #earnings_table td, #deductions_table td{
            /* text-align:center; */
            border: 1px solid #20aee3;
        }

        #earnings_table thead, #deductions_table thead {
            background-color: #20aee3;
            color: #fff;
        }

        .company_logo {
            width: 200px;
        }

        .header_box {
            display: flex;
            justify-content: space-between;
            align-items:center;
        }

        /* .image_box, .text_box {
            display: inline-flex;
            width: 100%;
        } */

        .image_box {
            width: 30%;
        }

        .text_box {
            text-align:justify;
            width: 70%;
        }

        body {
            font-family: monospace
        }

        .red {
            color: #f04242
        }

        .green {
            color: #18bf2f
        }
    </style>
</head>

<body>
    <div class="header_box">
        <div class="image_box">
            <img src="<?= $image ?>" class="company_logo" alt="Delsan VA Logo">
        </div>
        <div class="text_box">
            <p>
                Unit 1702B The Meridian <br>
                Golam Drive, Pope John Paul II Ave. <br>
                Kasambagan, Cebu City, Cebu 6000
            </p>
        </div>
    </div>
    <div class="payslip_earnings">
      <h2>Earnings</h2>
      <table id="earnings_table" class="table table-bordered table-hover dataTable dtr-inline">
          <thead>
              <tr>
                  <th>Base Pay Type</th>
                  <th>Date</th>
                  <th>Hours</th>
                  <th>Amount</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>Vacation Leave</td>
                  <td></td>
                  <td><?= $vacation_hours ?></td>
                  <td><?= "Php " . number_format($vacation_leaves) ?></td>
              </tr>
              <tr>
                  <td>Sick Leave</td>
                  <td></td>
                  <td><?= $sick_hours ?></td>
                  <td><?= "Php " . number_format($sick_leaves) ?></td>
              </tr>
              <tr>
                  <td>Holidays</td>
                  <td></td>
                  <td><?= $holiday_hours ?></td>
                  <td><?= "Php " . number_format($holidays) ?></td>
              </tr>
              <tr>
                  <td>Total Hours Worked</td>
                  <td></td>
                  <td><?= $hours ?></td>
                  <td><?= "Php " . number_format($user_details->salary / 2) ?></td>
              </tr>
              <tr>
                  <td><strong>Total Earnings</strong></td>
                  <td></td>
                  <td></td>
                  <td class="green"><?= "Php " . number_format($total_earnings); ?></td>
              </tr>
          </tbody>
      </table>
    </div>
    <div class="payslip_deductions">
        <h2>Deductions</h2>
        <table id="deductions_table" class="table table-bordered table-hover dataTable dtr-inline">
            <thead>
                <tr>
                    <th colspan="2">Deduction Type</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Withholding Tax</td>
                    <td><?= "Php " . number_format($user_details->tax / 2) ?></td>
                </tr>
                <tr>
                    <td colspan="2">SSS</td>
                    <td><?= "Php " . number_format($user_details->sss / 2) ?></td>
                </tr>
                <tr>
                    <td colspan="2">Philhealth</td>
                    <td><?= "Php " . number_format($user_details->philhealth / 2) ?></td>
                </tr>
                <tr>
                    <td colspan="2">HDMF</td>
                    <td><?= "Php " . number_format($user_details->{'pag-ibig'} / 2) ?></td>
                </tr>
                <tr>
                    <td>Unpaid Leave</td>
                    <td><?= $unpaid_leave_hours ?> hours</td>
                    <td><?= "Php " . number_format($unpaid_leaves) ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Deductions</strong></td>
                    <td><strong class="red"><?= "Php " . number_format($total_deductions ) ?></strong></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Net Pay</strong></td>
                    <td><strong class="green"><?= "Php " . number_format($net_pay) ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
