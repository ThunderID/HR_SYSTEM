<select name="chart_id" class="form-control select2 {{ isset($ChartComposer['widget_data']['chartlist']['class_name']) ? $ChartComposer['widget_data']['chartlist']['class_name'] : '' }}" tabindex="{{ $ChartComposer['widget_data']['chartlist']['tabindex'] }}" data-organisationid="{{ isset($ChartComposer['widget_data']['chartlist']['organisation_id']) ? $ChartComposer['widget_data']['chartlist']['organisation_id'] : '' }}">
	@foreach($ChartComposer['widget_data']['chartlist']['chart'] as $key => $value)
		<option value="{{$value['id']}}" @if($value['id']==$ChartComposer['widget_data']['chartlist']['chart_id']) selected @endif data-branchid="{{$value['branch']['id']}}">{{$value['name']}} {{$value['tag']}} {{$value['branch']['name']}} </option>
	@endforeach
</select>