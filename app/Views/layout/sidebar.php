<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index3.html" class="brand-link">
        <img src="assets/images/delsanva.jpg" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Delsan VA</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="<?= base_url() ?>" class="nav-link <?= ($title == 'Dashboard') ? 'active' : '' ; ?>">
                        <i class="nav-icon far fa-image"></i> <p> Dashboard </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('users') ?>" class="nav-link <?= ($title == 'Users') ? 'active' : ''; ?>">
                        <i class="nav-icon far fa-image"></i> <p> Staff </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('timesheet') ?>" class="nav-link <?= ($title == 'Timesheet') ? 'active' : ''; ?>">
                        <i class="nav-icon far fa-image"></i> <p> Timesheet </p>
                    </a>
                </li>
            </ul>
        </nav>

    </div>

</aside>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>
    </nav>