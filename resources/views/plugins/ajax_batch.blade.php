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


	@if (Route::is('hr.calendars.show'))
		var x =0;
		var intervalID	= setInterval(
			$.ajax, 1000, 
			{
				url: '{{ route("hr.batch.schedules") }}',
				success: function(result) {
					var valuemax = result.data.data.total_process;
					var valuenow = x;

					$('.message_batch').html('Progres batch jadwal "'+jQuery.parseJSON(result.data.data.parameter).name+'"');
					$('.alert_batch').removeClass('hide');
					$('.progress-bar').attr('aria-valuemax', valuemax).attr('aria-valuenow', valuenow);
					$('.progress-bar').css('width', ((valuenow/valuemax)*100)+'%');
					$('.progress-bar').html(valuenow+' / '+valuemax+' proses');

					if (x==85)
					{
						$('.progress-bar').attr('aria-valuemax', valuemax).attr('aria-valuenow', valuenow);
						$('.progress-bar').css('width', '100%');
						$('.progress-bar').html(valuemax+' / '+valuemax+' proses');
						clearInterval(intervalID);
					}
					
					x=x+5;
				}
			}
		);
	@endif
	
</script>