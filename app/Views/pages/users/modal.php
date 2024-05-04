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
                <div class="modal-body" style="overflow-y:scroll; max-height:700px">
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
                        <div style="display:inline-block; width: 49%">
                            <label for="position_id">Role</label>
                            <select name="position_id" class="form-control" required>
                                <option value=""></option>
                                <?php foreach ($roles as $role) { ?>
                                    <option value="<?php echo $role->id ?>"><?php echo $role->role_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="check_location" class="" value="1">
                        <label for="check_location">Check Location</label>
                    </div>
                    <hr>
                    <h3>Salary</h3>
                    <label for="">Employee Income</label>
                    <div class="form-group">
                        <div style="display:inline-block; width: 50%">
                            <label for="salary">Salary</label>
                            <input type="number" name="salary" class="form-control">
                        </div>
                    </div>
                    <h4><em>Contributions</em></h4>
                    <div class="form-group">
                        <div style="display:inline-block; width: 50%">
                            <label for="sss">SSS</label>
                            <input type="text" name="sss" class="form-control" />
                        </div>
                        <div style="display:inline-block; width: 49%">
                            <label for="philhealth">Philhealth</label>
                            <input type="text" name="philhealth" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display:inline-block; width: 50%">
                            <label for="pag-ibig">Pag-ibig</label>
                            <input type="text" name="pag-ibig" class="form-control" />
                        </div>
                        <div style="display:inline-block; width: 49%">
                            <label for="tax">Tax</label>
                            <input type="text" name="tax" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display:inline-block; width: 50%">
                            <label for="tin">Tin</label>
                            <input type="text" name="tin" class="form-control" />
                        </div>
                    </div>
                    <hr>
                    <h3>Address</h3>
                    <label>Country</label>
                    <select class="form-control" id="select_country" name="country" style="width:40%">
                        <option></option>
                        <option value="usa">USA</option>
                        <option value="philippines">Philippines</option>
                    </select>
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
                            <label for="city">City</label>
                            <!-- <select class="form-control" name="city">
                                <option></option>
                            </select> -->
                            <input type="text" name="city" class="form-control" value="">
                        </div>
                        <div style="display:inline-block; width: 33%">
                            <label for="state">State</label>
                            <select class="form-control" name="state">
                                <option></option>
                            </select>
                            <!-- <input type="text" name="state" class="form-control" value=""> -->
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

<!-- This is modal for archiving. Previously was called delete so I did not update the id and class names -->
<div class="modal fade show" id="delete_user_modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Archive User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="" id="delete_user_form" action="javascript:void(0);">
                <div class="modal-body text-center">
                    <input type="hidden" name="user_id">
                    <h4>Are you sure you want to archive this user?</h4>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>