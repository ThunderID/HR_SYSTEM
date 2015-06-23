<select name="calendar_id" class="form-control">
	@foreach($CalendarComposer['widget_data']['calendarlist']['calendar'] as $key => $value)
		<option value="{{$value['id']}}" @if($value['id']==$CalendarComposer['widget_data']['calendarlist']['calendar_id']) selected @endif>{{$value['name']}}</option>
	@endforeach
</select>