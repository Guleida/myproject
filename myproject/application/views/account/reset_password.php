    <div class="row">
        <div class='col-md-3'></div>
        <div class="col-md-6">
            <div class="login-box well">
            	<div id="reset_password_form">
                    <form action="<?php echo site_url('account/reset_password'); ?>" method="post">
                    	<br/><br/><br/>
                        <legend>Reset Password</legend>
                        	<?php echo validation_errors('<p class ="error">');
                        		if (isset($error)) {
									echo '<p class ="error">' . $error . '</p>';
								}
                        	?>

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
                        
                        <caption>Forgot your password?</caption>
						<p>Enter your email address to reset your password. Don't forget to check your spam folder! </p>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" value="<?php echo set_value('email');?>" name="email" class="form-control" />
                        </div>

                        <div class="form-group">
                        	<input type="submit" name="submit" class="btn btn-default btn-login-submit btn-block m-t-md" value="Reset Password" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class='col-md-3'></div>
    </div>
    <br/><br/><br/><br/><br/>