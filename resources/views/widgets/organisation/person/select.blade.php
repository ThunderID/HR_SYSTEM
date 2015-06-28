<select name="relative_id" class="form-control select2">
	@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
		<option value="{{$value['id']}}" @if($value['id']==$PersonComposer['widget_data']['personlist']['relative_id']) selected @endif>{{$value['name']}}</option>
	@endforeach
</select>