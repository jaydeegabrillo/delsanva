<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delsanva HR</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/css/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/css/adminlte.min.css?v=3.2.0">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/css/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/fontawesome/css/all.min.css">
    

    <!-- DataTable -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/buttons.bootstrap4.min.css">
    <!-- Page Specific Script -->
    <?php if(isset($css_scripts)){ ?>
        <?php foreach ($css_scripts as $css_script) { ?>
            <link href="<?= base_url() ?>/assets/<?php echo $css_script ?>" rel="stylesheet">
        <?php } ?>
    <?php } ?>
</head>
