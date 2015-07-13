<select name="tmp_auth_group_id" tabindex = {{$AuthGroupComposer['widget_data']['authgrouplist']['tabindex']}} class="form-control">
	@foreach($AuthGroupComposer['widget_data']['authgrouplist']['authgroup'] as $key => $value)
		<option value="{{$value['id']}}" @if($value['id']==$AuthGroupComposer['widget_data']['authgrouplist']['tmp_auth_group_id']) selected @endif>{{$value['name']}}</option>
	@endforeach
</select>