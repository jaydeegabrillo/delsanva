<div class="modal fade show" id="timesheet_modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Timesheet PDF</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="timesheet_pdf_form" action="javascript:(0);">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date_from">Date From:</label>
                        <input type="date" name="date_from" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="date_to">Date To:</label>
                        <input type="date" name="date_to" class="form-control" value="" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="add_timesheet_modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Timesheet</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="add_timesheet_form" action="javascript:(0);">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user">Time in for user:</label>
                        <select class="form-control" name="user_id">
                            <option></option>
                            <?php foreach ($users as $user) { ?>
                                <option value="<?= $user['id'] ?>"><?= $user['first_name'] . " " . $user['last_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_from">Clock In:</label>
                        <input type="datetime-local" name="clock_in" class="form-control" value="">
                        <input type="hidden" name="date" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="date_to">Clock Out:</label>
                        <input type="datetime-local" name="clock_out" class="form-control" value="">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="edit_attendance" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Log</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="update_attendance_form" action="javascript:(0);">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="clock_in">Time In:</label>
                        <input type="time" name="clock_in" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="clock_out">Time Out:</label>
                        <input type="time" name="clock_out" class="form-control" value="">
                        <input type="hidden" name="id" class="form-control" value="">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
