   
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Leave</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Leaves</li>
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
                            <!-- <div class="card-header">
                                <h3 class="card-title">Filters</h3>
                            </div> -->
                            <div class="card-body" >
                                <div class="col-sm-4" style="display:inline-block;">
                                    Leave type
                                    <select name="leave_type" id="leave_type" class="form-control">
                                        <option value=""></option>
                                        <option value="sick_leave">Sick Leave</option>
                                        <option value="vacation_leave">Vacation Leave</option>
                                    </select>
                                </div>
                                <div class="col-sm-4" style="display:inline-block;">
                                    Status
                                    <select name="leave_status" id="leave_status" class="form-control">
                                        <option value=""></option>
                                        <option value="1">Approved</option>
                                        <option value="2">Declined</option>
                                        <option value="0">Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="display:block">
                            <div class="card-header">
                                <h3 class="card-title">Employee Leave Requests</h3>
                            </div>
                            <div class="card-body">
                                <div id="users" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="dataTables_wrapper dt-bootstrap4">
                                                <table id="leave_requests_table" class="table table-bordered table-hover dataTable dtr-inline">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Employee</th>
                                                            <th>Type</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Status</th>
                                                            <th>Reason</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfooter>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Employee</th>
                                                            <th>Type</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Status</th>
                                                            <th>Reasons</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfooter>
                                                </table>
                                            </div>
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
