<script>
	$('form').on('submit', '.selector', function(event) {
		$(this).find('input[type=submit]').disable();
		$(this).attr('onSubmit', 'return false;')
	});
</script>
