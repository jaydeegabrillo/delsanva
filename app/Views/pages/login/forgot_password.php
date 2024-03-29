<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="<?= base_url() ?>" class="h1"><b>Delsan</b>VA</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Input your email below to request for password reset.</p>
            <form id="reset_password_form">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="request_response"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block send_request">Send Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>