@if (!$widget_error_count)
	@if(isset($CalendarComposer['widget_data']['calendarlist']['calendar']))
		@forelse($CalendarComposer['widget_data']['calendarlist']['calendar'] as $key => $value)
			{{$value['name']}} <br/>
		@empty 
		@endforelse
	@endif
@else
	Error
@endif