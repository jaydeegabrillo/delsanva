    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Payslip</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">Payslip</li>
                            <li class="breadcrumb-item active">Payslip Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= $title ?></h3>
                                <div class="text-right">
                                    <!-- <button type="button" class="btn btn-success" name="button" data-toggle="modal" data-target="#add_payslip_modal"><i class="fa fa-plus"></i> Add Timesheet</button> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="payslip" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="dataTables_wrapper dt-bootstrap4">
                                                <h3>Earnings</h3>
                                                <table id="payslip_details_table" class="table table-bordered table-hover dataTable dtr-inline">
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
                                                            <td><?= $vacation_leaves ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sick Leave</td>
                                                            <td></td>
                                                            <td><?= $sick_hours ?></td>
                                                            <td><?= $sick_leaves ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Holidays</td>
                                                            <td></td>
                                                            <td><?= $holiday_hours ?></td>
                                                            <td><?= "₱ " . number_format($holidays) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Hours Worked</td>
                                                            <td></td>
                                                            <td><?= $hours ?></td>
                                                            <td><?= "₱ " . number_format($user_details->salary / 2) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Total Earnings</strong></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><?= "₱ " . number_format($total_earnings); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <h3>Deductions</h3>

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
                                                        <td><?= "₱ " . number_format($user_details->tax / 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">SSS</td>
                                                        <td><?= "₱ " . number_format($user_details->sss / 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">Philhealth</td>
                                                        <td><?= "₱ " . number_format($user_details->philhealth / 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">HDMF</td>
                                                        <td><?= "₱ " . number_format($user_details->{'pag-ibig'} / 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Unpaid Leave</td>
                                                        <td><?= $unpaid_leave_hours ?></td>
                                                        <td><?= "₱ " . number_format($unpaid_leaves) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Late</td>
                                                        <td><?= $late ?></td>
                                                        <td><?= "₱ " . number_format($late_deductions) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Undertime</td>
                                                        <td><?= $undertime ?></td>
                                                        <td><?= "₱ " . number_format($undertime_deductions) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><strong>Total Deductions</strong></td>
                                                        <td><?= "₱ " . number_format($total_deductions ) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><strong>Net Pay</strong></td>
                                                        <td><?= "₱ " . number_format($net_pay) ?></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot style="border:none">
                                                    <td colspan="3" class="text-right">
                                                        <a href="<?= base_url("payslip/payslip-pdf/{$id}") ?>" target="_blank"><button class="btn btn-primary">Download PDF</button></a>
                                                    </td>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.2.0
        </div>
    </footer>

    <aside class="control-sidebar control-sidebar-dark">

    </aside>