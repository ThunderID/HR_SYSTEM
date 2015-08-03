<script>
	$('form.formbatch').on('submit', function()
	{
		var wlid 		= $('.workleaveid').val();
		var orgid 		= $('.orgid').val();
		var start 		= $('.start').val();
		var jointstart	= $('.jointstart').val(); 
		var jointend 	= $('.jointend').val();

		$.ajax({
			url: '{{ route("hr.ajax.batch") }}',
			type: 'POST',
			formData: { workleave_id: wlid, org_id: orgid, start: start, joint_start: jointstart, joint_end: jointend },
			success: function(result) {
				console.log(result);
			},
			timeout: 0

		});
	});
</script>