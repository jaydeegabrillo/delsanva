<div class="modal fade show" id="sick_leave_modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sick Leave</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="leave_form" action="javascript:(0);">
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Sick Leave Balance <span class="sick_leave_balance"><?= $user_info->sick_leave ?></span> days </strong>
                    </div>
                    <!-- <div class="form-group">
                        <label class="switch">
                            <input type="checkbox" id="toggle">
                            <span class="slider"></span>
                        </label>
                        Half Day
                    </div> -->
                    <div class="form-group">
                        <label for="date_from">Date From:</label>
                        <input type="hidden" name="user_id" value="<?= $id ?>">
                        <input type="hidden" name="type" value="sick_leave">
                        <input type="hidden" name="status" value="0">
                        <input type="date" name="date_from" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="date_to">Date To:</label>
                        <input type="date" name="date_to" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        Total Leave Requested: <span class="leaves_requested">0</span>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea name="reason" id="reason" class="form-control"></textarea>
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

<div class="modal fade show" id="vacation_leave_modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Vacation Leave</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="leave_form" action="javascript:(0);">
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Vacation Leave Balance <span class="vacation_leave_balance"><?= $user_info->vacation_leave ?></span> days </strong>
                    </div>
                    <!-- <div class="form-group">
                        <label class="switch">
                            <input type="checkbox" id="toggle">
                            <span class="slider"></span>
                        </label>
                        Half Day
                    </div> -->
                    <div class="form-group">
                        <label for="date_from">Date From:</label>
                        <input type="hidden" name="user_id" value="<?= $id ?>">
                        <input type="hidden" name="type" value="vacation_leave">
                        <input type="hidden" name="status" value="0">
                        <input type="date" name="date_from" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="date_to">Date To:</label>
                        <input type="date" name="date_to" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        Total Leave Requested: <span class="leaves_requested">0</span>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea name="reason" id="reason" class="form-control"></textarea>
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