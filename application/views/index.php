<!DOCTYPE html>
<html>
<head>
	<title>Farm Game</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url("bootstrap/css/bootstrap.min.css"); ?>">
	<script src="<?php echo base_url("assets/js/jquery-2.2.3.min.js"); ?>"></script>
	<script src="<?php echo base_url("bootstrap/js/bootstrap.min.js"); ?>"></script>
</head>
<body>
	<div class="container" id="my_container">
		
	</div>

	<script type="text/javascript">
		$(document).ready(function () {
			$('#my_container').load('<?= site_url('Farm/start'); ?>');
		});	
	</script>
</body>
</html>
