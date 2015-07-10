<script>
	$('.open-filter').on('click', function()
	{
		$('.form_filter').toggleClass('hide');
	});

	$(function () {
		$('[data-tooltip="true"]').tooltip();
		$('a[data-tooltip="true"]').tooltip();
	});
</script>