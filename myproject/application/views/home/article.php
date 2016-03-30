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

	<?php if($article) { ?>
		<?php if($article->image) { ?>
		<div class="panel panel-default">
			<div class="panel-body text-center">
				<img class="img-responsive img-article-center" src="<?php echo base_url('images/articles/'.$article->image); ?>" alt="Article Image" />
			</div>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<div><?php echo $article->text; ?></div>
			</div>
		</div>
	<?php } ?>
</div>