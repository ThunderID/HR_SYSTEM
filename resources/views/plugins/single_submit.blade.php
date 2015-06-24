<script>
	$('form').on('submit', '.selector', function(event) {
		$(this).find('button[type=submit]').disable();
		$(this).attr('onSubmit', 'return false;')
	});
</script>
