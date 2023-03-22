    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <div class="small-box <?= ($clock_status['status'] == 'in') ? 'bg-danger' : 'bg-success' ; ?> clock_status">
                            <div class="inner">
                                <h3 class="clock_stat"><?= ($clock_status['status'] == 'in') ? 'Clock Out' : 'Clock In'; ?></h3>
                                <p class="attendance_status">
                                    <?php if($clock_status['status'] == 'in'){ ?>
                                        Clocked in at <?= date('h:i a', strtotime($clock_status['time'])) ?>
                                    <?php } else { ?>
                                        You are not clocked in yet
                                    <?php } ?>
                                </p>
                            </div>
                            <div class="icon clock_in" style="cursor:pointer">
                                <i class="ion ion-clock"></i>
                                <?php if(!$clock_status || $clock_status == 'in'){ ?>
                                <?php } ?>
                            </div>
                            <a href="#" class="small-box-footer"></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>Logs</h3>
                                <p>You have <?= $missing_logs ?> missing log</p>
                            </div>
                            <a href="#" class="small-box-footer"></a>
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
