<div class="modal fade show" id="overtime_modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Overtime</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="ot_form" action="javascript:(0);">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date_from">Date:</label>
                        <input type="hidden" name="user_id" value="<?= $id ?>">
                        <input type="date" name="date" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="time_start">Time Start:</label>
                        <input type="time" id="time_start" name="time_start" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="time_end">Time End:</label>
                        <input type="time" id="time_end" name="time_end" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        Total Hours: <span class="ot_hours">0</span>
                        <input type="hidden" name="hours" id="hours">
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