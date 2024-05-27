<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index3.html" class="brand-link">
        <img src="<?= base_url() ?>/assets/images/delsanva.jpg" alt="DelsanVA Logo" class="brand-image elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Delsan VA</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user" style="color: white; font-size: 30px;"></i>
                <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $name ?></a>
            </div>
        </div>

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="<?= base_url() ?>" class="nav-link <?= ($title == 'Dashboard') ? 'active' : '' ; ?>">
                        <i class="nav-icon fas fa-tachometer"></i> <p> Dashboard </p>
                    </a>
                </li>

                <?php if($position_id == 1){ ?>
                    <li class="nav-item">
                        <a href="<?= base_url('users') ?>" class="nav-link <?= ($title == 'Users') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-user"></i> <p> Staff </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('users/archive') ?>" class="nav-link <?= ($title == 'Archive') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-archive"></i> <p> Archive </p>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a href="<?= base_url('timesheet') ?>" class="nav-link <?= ($title == 'Timesheet') ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-hourglass"></i> <p> Timesheet </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-plane"></i> <p> Leaves </p><i class="right fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="<?= base_url('leaves') ?>" class="nav-link <?= ($title == 'Leaves') ? 'active' : ''; ?>"> <i class="far fa-circle nav-icon"></i> <p>File</p> </a>
                        </li>
                        <?php if($position_id == 1){ ?>
                            <li>
                                <a href="<?= base_url('leaves/leave_requests') ?>" class="nav-link <?= ($title == 'Leave_requests') ? 'active' : ''; ?>"> <i class="far fa-circle nav-icon"></i> <p>Leave Requests</p> </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </nav>

    </div>

</aside>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <!-- <img class="animation__shake" src="assets/images/delsanva.jpg" alt="DelsanVA Logo" style="height:100; min-width:100; width:100%;"> -->
    </div>

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link update_password" href="javascript:void(0)" data-toggle="modal" data-target="#update_password_modal" >Update Password <i class="fa fa-lock"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('login/logout') ?>">Logout <i class="fa fa-sign-out"></i></a>
            </li>
        </ul>
    </nav>
