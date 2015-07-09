@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php
		$ApiComposer['widget_data']['apilist']['api-pagination']->setPath(route('hr.branch.apis.index'));
	?>

	@section('widget_title')
		<h1> {!! $widget_title or 'Api' !!} </h1>
		<small>Total data {{$ApiComposer['widget_data']['apilist']['api-pagination']->total()}}</small>
	
		@if(isset($ApiComposer['widget_data']['apilist']['active_filter']) && !is_null($ApiComposer['widget_data']['apilist']['active_filter']))
			 <div class="clearfix">&nbsp;</div>
			@foreach($ApiComposer['widget_data']['apilist']['active_filter'] as $key => $value)
				<span class="active-filter">{{$value}}</span>
			@endforeach
		@endif
	@overwrite

	@section('widget_body')
		<a href="{{ $ApiComposer['widget_data']['apilist']['route_create'] }}" class="btn btn-primary">Tambah</a>
		@if(isset($ApiComposer['widget_data']['apilist']['api']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>No</th>
							<th>CLIENT</th>
							<th>SECRET</th>
							<th>APP ID</th>
							<th>PC Name</th>
							<th>Aktif</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<?php $i = $ApiComposer['widget_data']['apilist']['api-display']['from'];?>
					@foreach($ApiComposer['widget_data']['apilist']['api'] as $key => $value)
						<tbody>
							<tr>
								<td>
									{{$i}}
								</td>
								<td>
									{{$value['client']}}
								</td>
								<td>
									{{$value['secret']}}
								</td>
								<td>
									{{$value['workstation_address']}}
								</td>
								<td>
									{{$value['workstation_name']}}
								</td>
								<td>
									@if($value['is_active'])
										<i class="fa fa-check"></i>
									@else
										<i class="fa fa-minus"></i>
									@endif
								</td>
								<td class="text-right">
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.branch.apis.delete', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id'] ]) }}"><i class="fa fa-trash"></i></a>
									<a href="{{route('hr.branch.apis.edit', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
								</td>
							</tr>
						</tbody>
						<?php $i++;?>
					@endforeach
				</table>
			</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$ApiComposer['widget_data']['apilist']['api-display']['from']!!} - {!!$ApiComposer['widget_data']['apilist']['api-display']['to']!!}</p>
					{!!$ApiComposer['widget_data']['apilist']['api-pagination']->appends(Input::all())->render()!!}
				</div>
			</div>

			<div class="clearfix">&nbsp;</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif