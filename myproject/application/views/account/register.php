<div class="row">
    <div class='col-md-3'></div>
    <div class="col-md-6">
        <div class="login-box well">
            <form action="<?php site_url('acccount/register'); ?>" method="post">
                <br/><br/><br/>
                <legend>Register</legend>
                <?php echo validation_errors('<p class ="error">');?>

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
                    <label for="first">First Name</label>
                    <input type="text" value="<?php echo set_value('firstname');?>" name="firstname" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="last">Last Name</label>
                    <input type="text" value="<?php echo set_value('lastname');?>" class="form-control" name="lastname"/>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" value="<?php echo set_value('email');?>" class="form-control" name="email" />
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" value="<?php echo set_value('username'); ?>" class="form-control" name="username" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password"/>
                </div>
                <div class="form-group">
                    <label for="cpassword">Confirm Password</label>
                    <input type="password" class="form-control" name="cpassword" />
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-default btn-login-submit btn-block m-t-md" value="Register" />
                </div>

                <div class="form-group">
                    <p class="text-center m-t-xs text-sm">Already registered?</p>
                    <a href="<?php echo site_url('account/login'); ?>" class="btn btn-default btn-block m-t-md">Login Here!</a>
                </div>
            </form>

        </div>
    </div>
    <div class='col-md-3'></div>
</div>





