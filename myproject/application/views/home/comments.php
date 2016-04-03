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
						<img class="img-responsive" src="<?php echo base_url('images/articles/'.$article->image); ?>" alt="Article Image" />
					</div>
					</div>
				<?php } ?>
				<div class="panel panel-default">
			<div class="panel-body text-center">
				<h1><?php echo $article->title ?></h1>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<div><?php echo $this->typography->auto_typography($article->text); ?></div>
			</div>
		</div>
		
			
		<div class="panel panel-default">
			<div class="panel-body">
				<?php if($comments && is_array($comments) && count($comments) > 0) { ?>
					<?php foreach($comments as $comment) { ?>
						<div>
							<div>
								<span class="grey">
									(<?php echo $comment->date_formatted; ?>)
								</span>
								<span class="bold"><i class="glyphicon glyphicon-user"></i> <a href="<?php echo site_url('home/profile/'.$comment->user_id); ?>" ><?php echo $comment->username; ?></a></span>:
								<?php echo $comment->text; ?>
								<?php if($comment->user_id == $user->userID) {?>
									<a href="<?php echo site_url('home/remove_comment/'.$article->article_id.'/'.$comment->comment_id); ?>"> - delete </a>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				<?php } else { ?>
					<div>
						No comments found.
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="col-lg-12">
					<form action="<?php echo site_url('home/add_comment/'.$article->article_id); ?>" method="post" class="form-horizontal">
						<div class="form-group">
							<textarea class="form-control" name="comment" id="comment"></textarea>
						</div>
						<div class="form-group text-right">
							<span id="words"></span>
							<input type="submit" class="btn btn-info" disabled id="add-btn" value="Add Comment" />
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php } ?>
</div>


<script>
	var min = <?php echo $comment_words_min; ?>;
	var max = <?php echo $comment_words_max; ?>;

	function wordCount( val ){
		var wom = val.match(/\S+/g);
		return {
			charactersNoSpaces : val.replace(/\s+/g, '').length,
			characters         : val.length,
			words              : wom ? wom.length : 0,
			lines              : val.split(/\r*\n/).length
		};
	}

	function wordCountTextatrea()
	{
		var text = $('#comment').val();
		if(text.length > 0) {
			var count = wordCount(text).words;

			if(count < min) {
				$('#words').html('<span class="red">' + count + '</span>');

				if(!$('#add-btn').prop('disabled')) {
					$('#add-btn').attr('disabled', 'disabled');
				}
			} else if(count > max) {
				$('#words').html('<span class="red">' + count + '</span>');

				if(!$('#add-btn').prop('disabled')) {
					$('#add-btn').attr('disabled', 'disabled');
				}
			} else {
				$('#words').html('<span class="green">' + count + '</span>');

				if($('#add-btn').prop('disabled')) {
					$('#add-btn').removeAttr('disabled');
				}
			}

		} else {
			if(!$('#add-btn').prop('disabled')) {
				$('#add-btn').attr('disabled', 'disabled');
			}
			$('#words').html('<span class="black">0</span>');
		}
	}

	$(function(){
		wordCountTextatrea();

		$('#comment').keyup(function(){
			wordCountTextatrea();
		});
	});
</script>