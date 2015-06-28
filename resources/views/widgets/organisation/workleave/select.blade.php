<select name="workleave_id" tabindex = $WorkleaveComposer['widget_data']['tabindex'] class="form-control">
	@foreach($WorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
		<option value="{{$value['id']}}" @if($value['id']==$WorkleaveComposer['widget_data']['workleavelist']['workleave_id']) selected @endif>{{$value['name']}}</option>
	@endforeach
</select>