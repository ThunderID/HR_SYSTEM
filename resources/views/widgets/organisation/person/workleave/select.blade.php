<select name="person_workleave_id" tabindex = {{$PersonWorkleaveComposer['widget_data']['workleavelist']['tabindex']}} class="form-control select2">
	@foreach($PersonWorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
		<option value="{{$value['id']}}" @if($value['id']==$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave_id']) selected @endif>{{$value['name']}}</option>
	@endforeach
</select>