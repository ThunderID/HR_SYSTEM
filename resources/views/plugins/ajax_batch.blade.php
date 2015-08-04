<script>
	$('.batchsimpan').on('click', function()
	{
		$('form.formbatch').attr('action', 'javascript:;');
		var wlid 		= $('.workleaveid').val();
		var orgid 		= $('.orgid').val();
		var start 		= $('.start').val();
		var jointstart	= $('.jointstart').val(); 
		var jointend 	= $('.jointend').val();
		var action 		= $('form.formbatch').attr('data-action');

		$.ajax({
			url: action,
			method: 'POST',
			dataType: 'json',
			data: { workleave_id: wlid, org_id: orgid, start: start, joint_start: jointstart, joint_end: jointend },
			beforeSend: function(e) {
				console.log(e);
			},
			success: function(result) {
				if (result.message) {
					$('.message_batch').html('batch cuti sedang diproses..');
					$('.alert_batch').removeClass('hide');
					$('.alert_batch').addClass(result.mode);
					$('.icon').addClass(result.icon);
					$('.start').val('');
					$('.jointstart').val('');
					$('.jointend').val('');
				}
			}
		});

	});

	// var x = 0;
	// var intervalID	= setInterval(
	// 	$.ajax, 10000, 
	// 	{
	// 		url: '/coba1',
	// 		success: function(result) {
	// 			x=x+1;
	// 			if (x==4)
	// 			{
	// 				clearInterval(intervalID);
	// 				alert('interval selesai');
	// 			}

	// 		}
	// 	}
	// );
	
</script>