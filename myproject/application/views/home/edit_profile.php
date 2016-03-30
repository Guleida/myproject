<div class="col-xs-9" >
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-12">
                <legend><i class="glyphicon glyphicon-pencil"></i> Edit Profile</legend>
                <?php echo validation_errors('<div class ="alert alert-danger">', '</div>');?>

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
                <?php } if(isset($upload_error) && $upload_error) { ?>
                    <div class="alert alert-danger">
                        <a href="#" class="close alert-close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php echo $upload_error; ?>
                    </div>
                <?php } ?>

                <form action="<?php echo site_url('home/edit_profile'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo (set_value('firstname'))?set_value('firstname'):$user->firstname; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo (set_value('lastname'))?set_value('lastname'):$user->lastname; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" readonly class="form-control" value="<?php echo $user->email; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" readonly class="form-control" value="<?php echo (set_value('username'))?set_value('username'):$user->username; ?>" />
                    </div>
                    <div class="form-group">
                        <?php if($user->image) { ?>
                            <div class="col-lg-3">
                                <img class="img-responsive" src="<?php echo base_url('images/users/'.$user->image); ?>" />
                            </div>
                        <?php } ?>
                        <label for="file">Profile Image (max 512x512, 1024kb)</label>
                        <input type="file" class="" id="file" name="file" placeholder="Article Image" />
                    </div>

                    <div class="form-group text-right">
                        <input type="hidden" name="action" value="info" />
                        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-12">
                <legend><i class="glyphicon glyphicon-pencil"></i> Change Password</legend>

                <form action="<?php echo site_url('home/edit_profile'); ?>" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" value="" />
                    </div>
                    <div class="form-group">
                        <label for="password2">Repeat Password</label>
                        <input type="password" id="password2" name="password2" class="form-control" value="" />
                    </div>

                    <div class="form-group text-right">
                        <input type="hidden" name="action" value="password" />
                        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>