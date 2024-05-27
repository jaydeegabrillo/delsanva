    <div class="content-wrapper">
        <div class="content-header">
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>Leave Types</h3>
                                <table class="table table-bordered table-hover dataTable dtr-inline">

                                    <thead>
                                        <th>Type</th>
                                        <th>Units</th>
                                        <th>Available Balance</th>
                                        <th>Pending Approvals</th>
                                        <th>Approved</th>
                                        <th>Action</th>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>Sick Leave</td>
                                            <td>Days</td>
                                            <td><?= $user_info->sick_leave - $approved_sick_leave ?></td>
                                            <td><?= $pending_sick_leave_count ?></td>
                                            <td><?= $approved_sick_leave ?></td>
                                            <td>
                                                <button id="sick_leave" class="btn btn-primary" data-toggle="modal" data-target="#sick_leave_modal" >Apply</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Vacation Leave</td>
                                            <td>Days</td>
                                            <td><?= $user_info->vacation_leave - $approved_vacation_leave ?></td>
                                            <td><?= $pending_vacation_leave_count ?></td>
                                            <td><?= $approved_vacation_leave ?></td>
                                            <td>
                                                <button id="vacation_leave" class="btn btn-primary" data-toggle="modal" data-target="#vacation_leave_modal" >Apply</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h3>Leave History (YTD)</h3>
                                
                                <table class="table table-bordered table-hover dataTable dtr-inline">
    
                                    <thead>
                                        <th>Type</th>
                                        <th>Period</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                    </thead>
    
                                    <tbody>
                                        <?php foreach ($filed_leaves as $leave) { ?>
                                            <tr>
                                                <td><?= $leave->type == 'sick_leave' ? 'Sick Leave' : 'Vacation Leave' ?></td>
                                                <td><?= date("M d, Y", strtotime($leave->date_from)) ?> - <?= date("M d, Y", strtotime($leave->date_to)) ?></td>
                                                <td>
                                                    <?php
                                                    if($leave->status == 1){
                                                        echo "<span class='btn btn-success'>Approved</span>";
                                                    } else if($leave->status == 0) {
                                                        echo "<span class='btn btn-warning'>Pending</span>";
                                                    } else {
                                                        echo "<span class='btn btn-danger'>Declined</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= $leave->reason ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
    
                                </table>

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
