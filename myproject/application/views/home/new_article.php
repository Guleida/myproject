<div class="col-xs-12" >
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-lg-12">
				<legend><i class="glyphicon glyphicon-pencil"></i> Add New Article</legend>
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

				<form action="<?php echo site_url('home/new_article'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
					<div class="form-group">
						<textarea class="form-control article_textarea" id="article" name="article" required placeholder="Article Text"><?php echo (set_value('article'))?set_value('article'):""; ?></textarea>
					</div>
					<div class="form-group">
						<label for="file">Article Image</label>
						<input type="file" class="" id="file" name="file" placeholder="Article Image" />
					</div>

					<div class="form-group text-right">
						<span id="words"></span>
						<button onclick="go(event, '<?php echo site_url('home'); ?>')" class="btn btn-info"><i class="glyphicon glyphicon-remove"></i> Cancel</button>
						<button type="submit" disabled id="add-btn" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Add</button>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>

<script>
	var min = <?php echo $words_min; ?>;
	var max = <?php echo $words_max; ?>;

	function wordCount( val ){
		var wom = val.match(/\S+/g);
		return {
			charactersNoSpaces : val.replace(/\s+/g, '').length,
			characters         : val.length,
			words              : wom ? wom.length : 0,
			lines              : val.split(/\r*\n/).length
		};
	}

	function go(event, href) {
		event.preventDefault();
		window.location.href = href;
	}

	function wordCountTextatrea()
	{
		var text = $('#article').val();
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

		$('#article').keyup(function(){
			wordCountTextatrea();
		});
	});
</script>