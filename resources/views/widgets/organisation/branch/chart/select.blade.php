<select name="chart_id" class="form-control select2" tabindex="{{ $ChartComposer['widget_data']['chartlist']['tabindex'] }}">
	@foreach($ChartComposer['widget_data']['chartlist']['chart'] as $key => $value)
		<option value="{{$value['id']}}" @if($value['id']==$ChartComposer['widget_data']['chartlist']['chart_id']) selected @endif>{{$value['name']}} {{$value['tag']}} {{$value['branch']['name']}} </option>
	@endforeach
</select>