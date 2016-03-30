<div class="row" > <!--beginning of row 1  -->
	<div class="col-xs-3">
		<div class="panel panel-default">
			<div class="panel-body">
				<?php if($user_details->image) { ?>
				<div>
					<img class="img-responsive" src="<?php echo base_url('images/users/'.$user_details->image); ?>" />
				</div>
				<?php } ?>
				<h3 class="padding-bottom"><i class="glyphicon glyphicon-user"></i> <?php echo $user_details->username ?></h3>
				<div class="padding-bottom">
					<i class="glyphicon glyphicon-globe"></i> <?php echo substr(ucfirst(strtolower($user_details->firstname)), 0, 1) . '. &nbsp;' . ucfirst(strtolower($user_details->lastname)); ?>
				</div>
				<div class="padding-bottom">
					<i class="glyphicon glyphicon-envelope"></i> <?php echo $user_details->email;?>
				</div>
				<div class="padding-bottom">
					<i class="glyphicon glyphicon-comment"></i> Articles: <?php echo $user->articles;?>
				</div>
				<div class="padding-bottom">
					<i class="glyphicon glyphicon-eye-open"></i> Following: <?php echo $user->following;?>
				</div>
				<div class="padding-bottom">
					<i class="glyphicon glyphicon-eye-open"></i> Followers: <?php echo $user->followers;?>
				</div>
				<?php if($user->userID != $user_details->userID) {?>
					<?php if($is_follower) {?>
						<div class="padding-bottom text-center">
							<a href="<?php echo site_url('home/unfollow/'.$user_details->userID); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-eye-close"></i> Unfollow</button></a>
						</div>
					<?php } else { ?>
						<div class="padding-bottom text-center">
							<a href="<?php echo site_url('home/follow/'.$user_details->userID); ?>"><button class="btn btn-success"><i class="glyphicon glyphicon-eye-open"></i> Follow</button></a>
						</div>
					<?php } ?>
				<?php } ?>
				<h5></h5>
			</div>
		</div>
	</div>
