<script type="text/javascript">
$('.thumb').change(function(e){
		var x = $(this).attr('data-checked-action');
		var y = $(this).attr('data-unchecked-action');
		$(this).prop('disabled', true);
		
		if ($(this).attr('checked')==='checked') {
			$('.check').attr('action', y);
			$('.check').submit();
			console.log('checked');
		}
		else {
			$('.check').attr('action', x);
			$('.check').submit();
			console.log('unchecked');
		}
	});
</script>
