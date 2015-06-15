@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_body')
		@if(isset($widget_data['data']))
			<table class="table table-hover mt-20">
				<thead>
					<th>#</th>
					<th>Name</th>
					<th>No Tlp</th>
					<th>Alamat</th>
					<th></th>
				</thead>
				<tbody>
					@foreach($widget_data['data'] as $key => $value)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ $value['name'] }}</td>
							@if ((isset($value['contacts']))&&count($value['contacts']))
								@foreach ($value['contacts'] as $key2 => $value2)
									@if ($value2['item']=='phone')
										<td><i class="fa fa-mobile fa-fw"></i> {{ $value2['value'] }}</td>
									@elseif ($value2['item']=='email')
										<td><i class="fa fa-envelope fa-fw"></i> {{ $value2['value'] }}</td>
									@endif
								@endforeach
							@endif
							<td>
								<a href="" class="btn btn-default"><i class="fa fa-eye"></i></a>
								<a href="{{ isset($edit) ? $edit : '' }}" class="btn btn-default">
									<i class="fa fa-pencil"></i>
								</a>
								<a href="{{ isset($delete) ? $delete : '' }}" class="btn btn-default" data-toggle="modal" data-target="#delete"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@endif
	@overwrite
@else

	@section('widget_body')
	@overwrite
@endif