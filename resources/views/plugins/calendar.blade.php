{!! HTML::style('plugins/fullcalendar/fullcalendar.min.css') !!}
{!! HTML::script('plugins/fullcalendar/moment.min.js') !!}
{!! HTML::script('plugins/fullcalendar/fullcalendar.js') !!}

<script type="text/javascript">
	if(typeof(cal_link) != "undefined" && cal_link !== null) {
		var curSource = cal_link;			
	}
	else {
		var curSource = '';		
	}
	
	$('#calendar').fullCalendar({
		header: {left:'', center: '', right:  'today prev,next'},
		timeFormat: 'HH:mm',
		displayEventEnd: true,
		dayNamesShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
		drop: function (date, allDay) { // this function is called when something is dropped
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');

			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);

			// assign it the date that was reported
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;
			copiedEventObject.className = originalEventObject.className;
			
			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

			// is the "remove after drop" checkbox checked?
			if ($('#drop-remove').is(':checked')) {
				// if so, remove the element from the "Draggable Events" list
				$(this).remove();
			}
		},
		loading: function(bool) {					
			$('#calendar').toggleClass('calendar-loading');
			$('.sk-spinner').toggle(bool);
		},
		events:  curSource,
		eventRender: function (event, element) {					
			var datetime_start 	= event.start._i.split('T');
			if (event.end != null) {
				var datetime_end 	= event.end._i.split('T');
			}else {
				var datetime_end 	= datetime_start;
			}

			var date_start 		= datetime_start[0].split(/-/);	
			date_start 			= date_start[2]+'-'+date_start[1]+'-'+date_start[0];

			if(typeof(event.data_target) != "undefined" && event.data_target !== null) {
				element.attr('data-target', event.data_target);
				element.attr('data-toggle', 'modal');
				element.attr('href', 'javascript:;');
			} else if (event.mode_info) {
				element.attr('href', 'javascript:;');
			}

			if (event.mode == 'create') {
				element.attr('data-add-action', event.add_action);		
			}
			else {				
				if (event.mode_info != 'log') {
					if (event.status=="presence_indoor") {
						event.status = 'Hadir';
					} 
					else if (event.status=="presence_outdoor") {
						event.status = 'Dinas Luar';	
					}
					else if (event.status=="absence_not_workleave") {
						event.status = 'Absen, Tidak Mengurangi Cuti';		
					}
					else if (event.status=="absence_workleave") {
						event.status = 'Absen, Mengurangi Cuti';	
					}
					element.attr('title', '&nbsp;'+event.status+'&nbsp;');
				}
				else {
					element.attr('title', '&nbsp;'+event.title+'&nbsp;');
					element.attr('style', 'cursor:text');
				}

				element.attr('data-id', event.id);
				element.attr('data-title', event.title);				
				element.attr('data-placement', 'bottom');
				element.attr('data-edit-action', event.ed_action);
				element.attr('data-delete-action', event.del_action);
				// element.find('.fc-title').parent().addClass('border-schedule');

				element.attr('data-show', 'tip');
			 	Tipped.create(element, {size: 'x-small', position: 'bottomleft', skin: 'dark'});
			}

			element.find('#date-title').html(element.find('span.fc-event-title').text());			
			element.find('.fc-title').append('<br>');

			if(typeof(event.top_title) != "undefined" && event.top_title !== null) {
				element.find('.fc-title').prepend(event.top_title+'<br>');
			}

			element.find('.fc-title').addClass(event.label+' font-12');
			element.attr('data-status', event.status);
			element.attr('data-date', date_start);
			element.attr('data-start', datetime_start[1]);
			element.attr('data-end', datetime_end[1]);			
			element.attr('data-is-affect-salary', event.affect_salary);

			if(typeof(event.info_default) != "undefined" && event.info_default == "yes") {
				element.find('.fc-title').removeClass('fc-title');
			} 
		},
		viewRender: function (view, element) {			
	        //The title isn't rendered until after this callback, so we need to use a timeout.	        
	        window.setTimeout(function(){
	            $("h4.title-calendar").empty().append(view.title);
	        },0);

	    },
	});
</script>