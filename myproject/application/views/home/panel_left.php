<div class="row" > <!--beginning of row 1  -->
	<div class="col-xs-3">
		<div class="panel panel-default">
			<div class="panel-body">
				<h3 class="padding-bottom"><i class="glyphicon glyphicon-user"></i> <?php echo $user->username ?></h3>
				<div class="padding-bottom">
					<i class="glyphicon glyphicon-globe"></i> <?php echo substr(ucfirst(strtolower($user->firstname)), 0, 1) . '. &nbsp;' . ucfirst(strtolower($user->lastname)); ?>
				</div>
				<div class="padding-bottom">
					<i class="glyphicon glyphicon-envelope"></i> <?php echo $user->email;?>
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
			</div>
		</div>
	</div>
