<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Timesheet PDF</title>
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
        .timesheet_table{
            width: 100%;
            border: 1px solid #20aee3;
            padding: 0;
            margin: 0;
        }

        .timesheet_data td{
            text-align:center;
            border: 1px solid #20aee3;
        }

        .timesheet_header {
            background-color: #20aee3;
            color: #fff;
        }
    </style>
</head>
<?php
$counter = 0;
$array_count = count($timesheet);
?>
<body>
    <h2>Timesheet PDF</h2>
    <?php foreach($timesheet as $key => $array){ $counter++; ?>
        <?php
        $rendered = '00:00';
        ?>
        <table class="timesheet_table" style="<?= ($counter < $array_count) ? 'page-break-after:always' : '' ; ?>">
            <thead class="timesheet_header">
                <th>Date</th>
                <th>In</th>
                <th>Out</th>
                <th>Hours</th>
            </thead>
            <tbody>
                <?php foreach($array as $key2 => $value){ ?>
                    <tr class="timesheet_data <?= ($counter % 2 == 0) ? 'evenrow' : 'oddrow' ?>">
                        <td><?= date('F d, Y' ,strtotime($value->date))?></td>
                        <td><?= ($value->clock_in == NULL) ? '-' : date('h:i a', strtotime($value->clock_in)) ?></td>
                        <td><?= ($value->clock_out == NULL || $value->clock_out == '0000-00-00 00:00:00') ? '-' : date('h:i a', strtotime($value->clock_out)) ?></td>
                        <td>
                            <?php
                            if($value->clock_in != NULL && $value->clock_out != NULL){
                                $start = strtotime($value->clock_in);
                                $end = strtotime($value->clock_out);
                                
                                // $hours = date('H:i:s', $end-$start);
                                $duration = $end-$start;
                                $hours = ($duration <= 0 ) ? '0' : (int)($duration/60/60);
                                $minutes = ($duration <= 0 ) ? '0' : (int)($duration/60)-$hours*60;
                                $hours = ($hours <= 9) ? '0'.$hours : $hours;
                                $minutes = ($minutes <= 9) ? '0'.$minutes : $minutes;
                                $rendered = date('H:i', strtotime($rendered."+$hours hours +$minutes minutes"));
                                
                                echo $hours." hrs ".$minutes." mins";
                            }else{
                                echo "-";
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="timesheet_data evenrow" style="text-align:right !important">
                    <td colspan="3">Total</td>
                    <td><?= $rendered ?></td>
                </tr>
            </tbody>
        </table>
    <?php } ?>
  </div>
</body>
</html>
