<div class="col-xs-6" >

	<?php

	//getting values from flashdata
	$success = $this->session->flashdata('success');
	$error = $this->session->flashdata('error');

	//if any values available - show in special "alert" design
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

	<?php if($articles && count($articles) > 0) { //if articles getted from database and number of them more than 0 - display them ?>
		<?php foreach($articles as $article) { // for every article ?>
			<div class="panel panel-default">
				<div class="panel-body">
					<?php if($article->image) { ?>
						<div class="col-lg-7 padding-left-zero">
							<img class="img-responsive" src="<?php echo base_url('images/articles/'.$article->image); //display image ?>" alt="Article Image" />
						</div>
					<?php } ?>
					<div>
						<h4><a href="<?php echo site_url('home/profile/'.$article->user_id); ?>" ><?php echo $article->username; //display username with link to profile page ?></a> <span class="grey">(<?php echo $article->date_formatted; //date formatted (in sql) ?>)</span></h4>
					</div>
					<div><?php echo word_limiter($article->text, 100); //show 100 words of article ?></div>
					<div class="text-right">
						<span><?php echo $article->likes_total; //show number of likes ?></span>
						<?php if($article->liked) { //if article already liked by user - show "Remove Like" button, otherwise - "Like" button ?>
							<a href="<?php echo site_url('home/dislike/'.$article->article_id); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-thumbs-down"></i></button></a>
						<?php } else { ?>
							<a href="<?php echo site_url('home/like/'.$article->article_id); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-thumbs-up"></i></button></a>
						<?php } ?>
						<span><?php echo $article->comments_total; //show number of comments ?></span>
						<a href="<?php echo site_url('home/comments/'.$article->article_id); //show link to comments page ?>"><button class="btn btn-success"><i class="glyphicon glyphicon-comment"></i></button></a>
						<a href="<?php echo site_url('home/article/'.$article->article_id); //show link to full article ?>"><button class="btn btn-info"><i class="glyphicon glyphicon-zoom-in"></i></button></a>
						<?php if($user->userID == $article->user_id) { //if user is article author - show delete button ?>
							<a href="<?php echo site_url('home/remove/'.$article->article_id); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button></a>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php } else { ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<p>No articles found</p>
			</div>
		</div>
	<?php } ?>
</div>