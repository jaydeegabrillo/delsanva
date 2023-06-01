<div class="modal fade show" id="add_user_modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="add_user_form" action="javascript:(0);">
                <div class="modal-body">
                    <h3>Personal Information</h3>
                    <div class="form-group">
                        <input type="hidden" name="id" class="id" value="">
                        <div style="display:inline-block; width: 50%">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="">
                        </div>
                        <div style="display:inline-block; width: 49%">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display:inline-block; width: 50%">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="">
                        </div>
                        <div style="display:inline-block; width: 49%">
                            <label for="phone">Phone</label>
                            <input type="number" name="phone" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display:inline-block; width: 50%">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" value="">
                        </div>
                        <div style="display:inline-block; width: 49%">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="check_location" class="" value="1"> 
                        <label for="check_location">Check Location</label>
                    </div>
                    <hr>
                    <h3>Address</h3>
                    <div class="form-group">
                        <div style="display:inline-block; width: 75%">
                            <label for="address">Street Address</label>
                            <input type="text" name="address" class="form-control" value="">
                        </div>
                        <div style="display:inline-block; width: 24%">
                            <label for="apt">Apt No.</label>
                            <input type="text" name="apt" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display:inline-block; width: 33%">
                            <label for="state">State</label>
                            <input type="text" name="state" class="form-control" value="">
                        </div>
                        <div style="display:inline-block; width: 33%">
                            <label for="city">City</label>
                            <input type="text" name="city" class="form-control" value="">
                        </div>
                        <div style="display:inline-block; width: 33%">
                            <label for="zip">Zip</label>
                            <input type="text" name="zip" class="form-control" value="">
                        </div>
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
