<?php
	$buttonName = 'Click to feed';
	$buttonID = 'btn_next';
	if($gameInfo['gameOver'] == 1) {
		$buttonName = 'END';
		$buttonID = 'btn_restart';
		if($gameInfo['winStatus'] == 1) {
			$msgType =  'success';
			$winStatus = 'Congratulations, you have won!';
		} else {
			$msgType = 'danger';
			$winStatus = 'You have lost!';
		}
?>
	<div class="alert alert-<?= $msgType ?>">
	  <strong><?= $winStatus ?></strong>
	</div>

<?php
	}
?>

<div class="row">
	<div class="pull-right">
		<table class="table-bordered">
			<thead>
			<tr><th style="padding: 5px" >Name</th><th style="padding: 5px">Alive</th></tr>
			</thead>
			<tbody>
<?php
$animTable = [];
foreach ($gameInfo['entities'] as $key => $rec) {
	$EntityType = $rec['EntityType'];
	if(isset($animTable[$EntityType])) {
		if($rec['alive'] == 1)
			$animTable[$EntityType] += 1;
	} else {
		if($rec['alive'] == 0)
			$animTable[$EntityType] = 0;
		else
			$animTable[$EntityType] = 1;
	}
}

foreach ($animTable as $key => $value) {
	echo "<tr>";
	echo "<td style='padding: 5px'>$key</td>";
	echo "<td style='padding: 5px'>$value</td>";
	echo "</tr>";
}
?>
			</tbody>
		</table>
	</div>
	<div class="text-center"><button id="<?= $buttonID ?>" class="btn btn-primary"><?= $buttonName ?></button>
	</div>
	<div class="clearfix"></div>
	<small style="color:red"> Note: Animals shown in red color are dead animals</small>

</div>

<div class="row">
	<table class="table table-bordered text-center">
		<thead>
			<tr>
				<th class="text-center">#Turns</th>
<?php
				foreach ($gameInfo['entities'] as $key => $rec) {
					if($rec['alive'] == 0) 
						$style = 'style="color: red"';
					else 
						$style = 'style="color: green"';
					echo "<th  class='text-center' {$style}>{$rec['name']}</th>";
				}
?>
			</tr>
		</thead>
		<tbody>
<?php
		for($i = $gameInfo['turns'] - 1; $i >= 0; $i--) {
			echo '<tr>';
			echo '<td>' . ($i + 1) .'</td>';
			foreach ($gameInfo['entities'] as $key => $rec) {
					$feed = ' ';

					$feedHistory = $rec['feedHistory'];
					if(!empty($feedHistory)) {
						$feed = in_array($i + 1, $feedHistory) ? 'Y' : ' ';
					}
					echo '<td>' . $feed . '</td>';
				}
			echo '</tr>';
		}
?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	$('#btn_next').click(function () {
		$('#my_container').load('<?= site_url('Farm/play'); ?>');
	});
	$('#btn_restart').click(function () {
		var yesNo = confirm("Do you really want to end?");
		if(yesNo)
			$('#my_container').load('<?= site_url('Farm/start'); ?>');
	});
</script>