<select name="work_id" tabindex = {{$WorkComposer['widget_data']['worklist']['tabindex']}} class="form-control select2">
	@foreach($WorkComposer['widget_data']['worklist']['work'] as $key => $value)
		<option value="{{$value['id']}}" @if($value['id']==$WorkComposer['widget_data']['worklist']['work_id']) selected @endif>{{$value['person']['name']}} - {{$value['person']['organisation']['name']}}</option>
	@endforeach
</select>