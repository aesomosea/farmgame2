<div class="jumbotron">
	<h1 class="text-center">FARM GAME</h1>
	<div class="text-center"><button id="btn_next" class="btn btn-primary">START</button></div>
</div>
<script type="text/javascript">
		$('#btn_next').click(function () {
			$('#my_container').load('<?= site_url('Farm/play'); ?>');
		});
</script>