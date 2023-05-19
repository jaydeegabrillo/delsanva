<div class="modal fade show" id="update_password_modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="update_password_form" action="javascript:(0);">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input type="hidden" name="id" class="form-control" value="<?= $id ?>">
                        <input type="password" name="password" class="form-control" value="">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>