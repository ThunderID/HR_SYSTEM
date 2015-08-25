{!! HTML::script('plugins/ajax-progress/jquery.ajax-progress.js') !!}

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


	@if ((Route::is('hr.calendars.show')))
		var x =0;
		// var intervalID	= setInterval(
		// 	$.ajax, 1000, 
		$.ajax(
			{
				url: '{{ route("hr.batch.schedules") }}',
				dataType: 'json',
				success: function(e) {
			        var max = parseInt(e.total_process[0]);
					var now = parseInt(e.process_number[0]);					

					$('.message_batch').html('Progres batch jadwal "'+e.message+'"');
					$('.alert_batch').removeClass('hide');
					$('.progress-bar').attr('aria-valuemax', max).attr('aria-valuenow', (now+x));
					$('.progress-bar').css('width', (((now+x)/max)*100)+'%');
					$('.progress-bar').html((now+x)+' / '+max+' proses');

					if ((now+x)>=max)
					{
						$('.progress-bar').attr('aria-valuemax', max).attr('aria-valuenow', now+x);
						$('.progress-bar').css('width', '100%');
						$('.progress-bar').html(max+' / '+max+' proses');
						setTimeout($('.progress-bar').html('Proses Selesai'), 200);
						clearInterval(intervalID);
					}
					
					x=x+2;
				}
			});
	@elseif ((Route::is('hr.person.schedules.index')))
		var x =0;
		// var intervalID	= setInterval(
		// 	$.ajax, 1000, 
		$.ajax(
			{
				url: '{{ route("hr.batch.person.schedules") }}',
				dataType: 'json',
				success: function(e) {
			        var max = parseInt(e.total_process[0]);
					var now = parseInt(e.process_number[0]);				
					var name = jQuery.parseJSON(e.parameter_process[0]);					

					$('.message_batch').html('Progres batch jadwal "'+name.name+'"');
					$('.alert_batch').removeClass('hide');
					$('.progress-bar').attr('aria-valuemax', max).attr('aria-valuenow', (now+x));
					$('.progress-bar').css('width', (((now+x)/max)*100)+'%');
					$('.progress-bar').html((now+x)+' / '+max+' proses');

					if ((now+x)>=max)
					{
						$('.progress-bar').attr('aria-valuemax', max).attr('aria-valuenow', now+x);
						$('.progress-bar').css('width', '100%');
						$('.progress-bar').html(max+' / '+max+' proses');
						setTimeout($('.progress-bar').html('Proses Selesai'), 200);
						clearInterval(intervalID);
					}
					
					x=x+2;
				}
			});
	@elseif ((Route::is('hr.workleaves.index')))
		var x =0;
		// var intervalID	= setInterval(
		// 	$.ajax, 1000, 
		$.ajax(
			{
				url: '{{ route("hr.batch.workleaves") }}',
				dataType: 'json',
				success: function(e) {
			        var max = parseInt(e.total_process[0]);
					var now = parseInt(e.process_number[0]);				
					var name = jQuery.parseJSON(e.parameter_process[0]);					

					$('.message_batch').html('Progres batch cuti "'+name.name+'"');
					$('.alert_batch').removeClass('hide');
					$('.progress-bar').attr('aria-valuemax', max).attr('aria-valuenow', (now+x));
					$('.progress-bar').css('width', (((now+x)/max)*100)+'%');
					$('.progress-bar').html((now+x)+' / '+max+' proses');

					if ((now+x)>=max)
					{
						$('.progress-bar').attr('aria-valuemax', max).attr('aria-valuenow', now+x);
						$('.progress-bar').css('width', '100%');
						$('.progress-bar').html(max+' / '+max+' proses');
						setTimeout($('.progress-bar').html('Proses Selesai'), 200);
						clearInterval(intervalID);
					}
					
					x=x+2;
				}
			});
	@endif
	
</script>