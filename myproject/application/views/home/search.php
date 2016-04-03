<div class="col-xs-9" >

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

	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-lg-12">
				<form action="<?php echo site_url('home/search'); ?>" method="post" class="form-horizontal">
					<div class="form-group">
						<input type="text" class="form-control" name="query" id="query" value="<?php echo (set_value('query'))?set_value('query'):""; ?>"/>
					</div>
					<div class="form-group text-right">
						<input type="submit" class="btn btn-info" value="Search" />
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php if(set_value('query')) { // if any value vas received in POST or GET query with name "query" ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<h3 class="text-center">Found <?php echo count($articles); //number of articles found ?> article(s).</h3>
			</div>
		</div>

		<?php if($articles && count($articles) > 0) { //list all articles as on timeline ?>
			<?php foreach($articles as $article) { ?>
				<div class="panel panel-default">
					<div class="panel-body">
						<?php if($article->image) { ?>
							<div class="col-lg-3 padding-left-zero">
								<img class="img-responsive" src="<?php echo base_url('images/articles/'.$article->image); ?>" alt="Article Image" />
							</div>
						<?php } ?>
						<div>
							<h4><a href="<?php echo site_url('home/profile/'.$article->user_id); ?>" ><?php echo $article->username; ?></a> <span class="grey">(<?php echo $article->date_formatted; ?>)</span></h4>
						</div>
						<div><h1> <?php echo $article->title; ?></h1> </div>
						<div><?php echo word_limiter($this->typography->auto_typography($article->text, 100)); ?></div>
						<div class="text-right">
							<span><?php echo $article->likes_total; ?></span>
							<?php if($article->liked) {?>
								<a href="<?php echo site_url('home/dislike/'.$article->article_id); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-thumbs-down"></i></button></a>
							<?php } else { ?>
								<a href="<?php echo site_url('home/like/'.$article->article_id); ?>"><button class="btn btn-danger"><i class="glyphicon glyphicon-thumbs-up"></i></button></a>
							<?php } ?>
							<span><?php echo $article->comments_total; ?></span>
							<a href="<?php echo site_url('home/comments/'.$article->article_id); ?>"><button class="btn btn-success"><i class="glyphicon glyphicon-comment"></i></button></a>
							<a href="<?php echo site_url('home/article/'.$article->article_id); ?>"><button class="btn btn-info"><i class="glyphicon glyphicon-zoom-in"></i></button></a>
							<?php if($user->userID == $article->user_id) { ?>
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

	<?php } ?>
</div>