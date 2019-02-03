<div class="container">
    <form class="form-horizontal" role="form" method="POST" action="/security/sendpassword">
        <div class="row mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h2>Forgot Password</h2>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                Enter your e-mail address and instructions will be sent to you on how to recover a password.
            </div>
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
                <button type="submit" class="btn btn-success"><i class="fas fa-key"></i> Recover</button>
                <a class="btn btn-link" href="/security">Back to Login</a>
            </div>
        </div>
    </form>
</div>