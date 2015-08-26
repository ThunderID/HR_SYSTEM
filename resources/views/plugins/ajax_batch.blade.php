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

					if ((e.parameter_process).length!=0)
					{
						var batch_template = '';
						for (x=0; x<= (e.parameter_process).length; x++)
						{
					        var max = parseInt(e.total_process);
							var now = parseInt(e.process_number);	
							var name = jQuery.parseJSON(e.parameter_process);

							$('.message_batch').html('Progres batch jadwal "'+name.name+'"');
							$('.alert_batch').removeClass('hide');						

							if (now>=max)
							{
								$('.progress-bar').attr('aria-valuemax', (max/max)*100).attr('aria-valuenow', (now/max)*100);
								$('.progress-bar').css('width', '100%');
								$('.progress').find('span').html(max+' / '+max+' proses');
								setTimeout($('.progress').find('span').html('Proses Selesai'), 200);
								clearInterval(intervalID);
							}

							else if (now<max) 
							{
								$('.progress-bar').attr('aria-valuemax', (max/max)*100).attr('aria-valuenow', (now/max)*100);
								$('.progress-bar').css('width', ((now/max)*100)+'%');
								$('.progress').find('span').html(now+' / '+max+' proses');
								if (((now/max)*100)>='45') 
								{
									$('.progress').find('span').css('color', '#fff');
								}
							}
							
							x=x+2;
						}

					}
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
					if ((e.parameter_process).length!=0)
					{
				        var max = parseInt(e.total_process[0]);
						var now = parseInt(e.process_number[0]);				
						var name = jQuery.parseJSON(e.parameter_process[0]);					

						$('.message_batch').html('Progres batch jadwal "'+name.name+'"');
						$('.alert_batch').removeClass('hide');						

						if (now>=max)
						{
							$('.progress-bar').attr('aria-valuemax', (max/max)*100).attr('aria-valuenow', (now/max)*100);
							$('.progress-bar').css('width', '100%');
							$('.progress').find('span').html(max+' / '+max+' proses');
							setTimeout($('.progress').find('span').html('Proses Selesai'), 200);
							clearInterval(intervalID);
						}

						else if (now<max) 
						{
							$('.progress-bar').attr('aria-valuemax', (max/max)*100).attr('aria-valuenow', (now/max)*100);
							$('.progress-bar').css('width', ((now/max)*100)+'%');
							$('.progress').find('span').html(now+' / '+max+' proses');
							if (((now/max)*100)>='45') 
							{
								$('.progress').find('span').css('color', '#fff');
							}
						}

					}
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
					if ((e.parameter_process).length!=0)
					{
				        var max = parseInt(e.total_process[0]);
						var now = parseInt(e.process_number[0]);				
						var name = jQuery.parseJSON(e.parameter_process[0]);

						$('.message_batch').html('Progres batch cuti "'+name.name+'"');
						$('.alert_batch').removeClass('hide');						

						if (now>=max)
						{
							$('.progress-bar').attr('aria-valuemax', (max/max)*100).attr('aria-valuenow', (now/max)*100);
							$('.progress-bar').css('width', '100%');
							$('.progress').find('span').html(max+' / '+max+' proses');
							setTimeout($('.progress').find('span').html('Proses Selesai'), 200);
							clearInterval(intervalID);
						}

						else if (now<max) 
						{
							$('.progress-bar').attr('aria-valuemax', (max/max)*100).attr('aria-valuenow', (now/max)*100);
							$('.progress-bar').css('width', ((now/max)*100)+'%');
							$('.progress').find('span').html(now+' / '+max+' proses');
							if (((now/max)*100)>='45') 
							{
								$('.progress').find('span').css('color', '#fff');
							}
						}
						
						x=x+2;
					}
				}
			});
	@endif
	
</script>