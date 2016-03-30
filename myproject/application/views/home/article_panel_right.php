	<div class="col-xs-3" >
		<div class="panel panel-default">
			<div class="panel-body">
				<?php if($article) { ?>
					<div class="padding-bottom">
						<i class="glyphicon glyphicon-user"></i> <a href="<?php echo site_url('home/profile/'.$article->user_id); ?>" ><?php echo $article->username; ?></a>
					</div>
					<div class="padding-bottom">
						<i class="glyphicon glyphicon-calendar"></i> <?php echo $article->date_formatted; ?>
					</div>
					<div class="text-center padding-bottom">
						<span class="col-lg-6"><i class="glyphicon glyphicon-thumbs-up"></i>: <?php echo $article->likes_total; ?></span>
						<span class="col-lg-6"><i class="glyphicon glyphicon-comment"></i> <?php echo $article->comments_total; ?></span>
						<span class="clearfix"></span>
					</div>
					<div class="text-center padding-bottom">
						<?php if($article->liked) {?>
							<a href="<?php echo site_url('home/dislike/'.$article->article_id); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-thumbs-down"></i> Remove Like</button></a>
						<?php } else { ?>
							<a href="<?php echo site_url('home/like/'.$article->article_id); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-thumbs-up"></i> Like</button></a>
						<?php } ?>
					</div>
					<div class="padding-bottom text-center ">
						<a href="<?php echo site_url('home/comments/'.$article->article_id); ?>"><button class="btn btn-success"><i class="glyphicon glyphicon-comment"></i> Comments</button></a>
					</div>
					<?php if($user->userID == $article->user_id) { ?>
						<div class="padding-bottom text-center ">
							<a href="<?php echo site_url('home/remove/'.$article->article_id); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Remove</button></a>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>