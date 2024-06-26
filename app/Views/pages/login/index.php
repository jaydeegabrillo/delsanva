<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="<?= base_url() ?>" class="h1"><b>Delsan</b>VA</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form id="login_form">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append show_password" data-action="show">
                        <div class="input-group-text">
                            <span class="fas fa-eye"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12"><a href="<?= base_url('login/forgot_password') ?>">Forgot your password?</a></div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <button class="btn btn-danger btn-block alert hide error_button">Incorrect credentials!</button>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>