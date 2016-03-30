<table class="table table-responsive" style="width: 520px">
	<thead>
		<tr>
			<th>#</th>
			<th>Team</th>
			<th>P</th>
			<th>W</th>
			<th>D</th>
			<th>L</th>
			<th>G+</th>
			<th>G-</th>
			<th>GD</th>
			<th>PTS</th>
		</tr>
	</thead>
	<tbody class="table-hover">
		<?php foreach($standing as $item): ?>
		<tr>
			<td><?php echo $item->position; ?></td>
			<td><?php echo $item->teamName; ?></td>
			<td><?php echo $item->playedGames; ?></td>
			<td><?php echo $item->wins; ?></td>
			<td><?php echo $item->draws; ?></td>
			<td><?php echo $item->losses; ?></td>
			<td><?php echo $item->goals; ?></td>
			<td><?php echo $item->goalsAgainst; ?></td>
			<td><?php echo $item->goalDifference; ?></td> 
			<td><?php echo $item->points; ?></td> 
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>