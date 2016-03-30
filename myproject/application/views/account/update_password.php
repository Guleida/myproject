<div class="row">
    <div class='col-md-3'></div>
    <div class="col-md-6">
        <div class="login-box well">
            <div id="update_password_form">
                <form action="<?php echo site_url('account/update_password'); ?>" method="post">
                    <br/><br/><br/>
                    <legend>Update Password</legend>
                    <?php echo validation_errors('<p class ="error">'); ?>

                    <?php

                    $success = $this->session->flashdata('success');
                    $error = $this->session->flashdata('error');

                    if($success) { ?>
                        <div class="alert alert-success">
                            <a href="#" class="close alert-close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?php echo $success; ?>
                        </div>
                    <?php } if($error) { ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close alert-close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <?php if (isset($email_hash, $email_code)) { ?> <!--Check if email hash and email code are set, if true, then two hidden input fields  are created for security-->
                            <input type="hidden" value="<?php echo $email_hash ?>" name="email_hash" />
                            <input type="hidden" value="<?php echo $email_code ?>" name="email_code" />
                        <?php } ?>
                        <input type="email" value="<?php echo (isset($email)) ? $email : ''; ?>" name="email" class="form-control" /> <!-- echo out the user email if it has been set-->
                    </div>

                    <div class="form-group">
                        <label for="password"> New Password: </label>
                        <input type="password" value="" name="password" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="password_conf"> Confirm New Password: </label>
                        <input type="password" value="" name="password_conf" class="form-control" />
                    </div>

                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-default btn-login-submit btn-block m-t-md" value="Update Password" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class='col-md-3'></div>
</div>