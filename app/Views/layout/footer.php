
        </div>
        <script src="assets/jquery/jquery.min.js"></script>

        <script src="assets/jquery/jquery-ui.min.js"></script>

        <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- <script src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script> -->

        <!-- <script src="https://adminlte.io/themes/v3/plugins/sparklines/sparkline.js"></script> -->

        <!-- <script src="https://adminlte.io/themes/v3/plugins/jqvmap/jquery.vmap.min.js"></script>
        <script src="https://adminlte.io/themes/v3/plugins/jqvmap/maps/jquery.vmap.usa.js"></script> -->

        <!-- <script src="https://adminlte.io/themes/v3/plugins/jquery-knob/jquery.knob.min.js"></script> -->

        <script src="assets/js/moment.min.js"></script>
        <script src="assets/js/daterangepicker.js"></script>

        <script src="assets/js/tempusdominus-bootstrap-4.min.js"></script>

        <script src="assets/js/summernote-bs4.min.js"></script>

        <script src="assets/js/jquery.overlayScrollbars.min.js"></script>

        <script src="assets/js/adminlte.js?v=3.2.0"></script>

        <script src="assets/js/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/js/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="assets/js/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/js/sweetalert2.all.min.js"></script>
        
        <!-- <script src="https://adminlte.io/themes/v3/dist/js/demo.js"></script> -->

        <!-- <script src="https://adminlte.io/themes/v3/dist/js/pages/dashboard.js"></script> -->
        <!-- Page Specific Script -->
        <?php if(isset($js_scripts)){ ?>
            <?php foreach ($js_scripts as $js_script) { ?>
                <script type="text/javascript" src="assets/<?php echo $js_script ?>"></script>
            <?php } ?>
        <?php } ?>
    </body>
</html>
