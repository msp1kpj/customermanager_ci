<div class="container">
    <form class="form-horizontal" role="form" method="POST" action="/security/authenticate">
        <div class="row mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h2>Sign In</h2>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <?php $this->load->view('Main/Elements/message'); ?>
                <div class="form-group has-danger">
                    <label class="sr-only form-control-label" for="email">E-Mail Address</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-at"></i></span>
                        </div>
                        <input type="text" name="email" class="form-control" id="email" placeholder="you@example.com" required autofocus value="<?php echo $currentUser->emailAddress;?>"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="form-group has-danger">
                    <label class="sr-only" for="password">Password</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-control-feedback">
                    <span class="text-danger align-middle">
                    <!-- Put password error message here -->
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6" style="padding-top: .35rem">
                <div class="form-check mb-2 mr-sm-2 mb-sm-0">
                    <label class="form-check-label">
                        <input class="form-check-input" name="remember"
                                type="checkbox" >
                        <span style="padding-bottom: .15rem">Remember me</span>
                    </label>
                </div>
            </div>
        </div>
        <?php
        echo "<div class='error_msg'>";
        if (isset($error_message)) {
        echo $error_message;
        }
        echo validation_errors();
        echo "</div>";
        ?>
        <div class="row" style="padding-top: 1rem">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-success"><i class="fas fa-user-lock"></i> Login</button>
                <a class="btn btn-link" href="/security/forgot">Forgot Your Password?</a>
            </div>
        </div>
    </form>
</div>