<?php foreach($categories as $catKey => $catItem): ?>
<ul style="list-style: none;"> <b><?php echo substr($catKey,0,10); ?></b></br>
	<?php foreach($catItem as $matchItem): ?>
		<li>
		<div class="row" style="font-size: 13px;">
			<div class="col-sm-5">
			<?php 
			  echo $matchItem->homeTeamName . " ";
			?>
			</div>
			<div class="col-sm-2">
			<?php
				if(!is_null($matchItem->result->goalsHomeTeam) ){
					echo "<b>" . $matchItem->result->goalsHomeTeam . "</b>:";
				}else if(is_null($matchItem->result->goalsHomeTeam) && isset($matchItem->time)){
					echo "<b><i>" . substr($matchItem->time,0,5) . "</i></b>";
				}else{
					echo "<b>vs</b>";
				}
		      echo !is_null($matchItem->result->goalsAwayTeam) ? "<b>" . $matchItem->result->goalsAwayTeam . "</b>" : " ";  
		    ?>
		    </div>
		    <div class="col-sm-5">
		    <?php
			  echo " " . $matchItem->awayTeamName; 
			?>
			</div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>	
<?php endforeach; ?>