<select name="workleave_id" class="form-control select2">
	@foreach($WorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
		<option value="{{$value['id']}}">{{$value['name']}}</option>
	@endforeach
</select>