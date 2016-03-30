<ul style="list-style: none;"> </br>
<?php 
	if(!empty($results)){
		foreach($results as $item): ?>
		<li>
			<div class="row">
				<div class="col-sm-5">
				<?php 
				  echo $item->homeTeamName . " ";
				?>
				</div>
				<div class="col-sm-2">
				<?php 
				  echo "<b>" . substr($item->date,0,5) . "</b>";
				?>
				</div>
				<div class="col-sm-5">
				<?php
				  echo " " . $item->awayTeamName; 
				?>
				</div>
			</div>
		</li>
		<?php endforeach; 
	}else{
	?>
	<h3>There is no games today</h3>
	<?php } ?>