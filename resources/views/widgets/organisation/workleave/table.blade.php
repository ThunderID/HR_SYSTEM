@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title  or 'Template Cuti' !!} </h1>
	<small>Total data {{$WorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->total()}}</small>
	
	<div class="clearfix">&nbsp;</div>
	@if(!is_null($WorkleaveComposer['widget_data']['workleavelist']['active_filter']))
		@foreach($WorkleaveComposer['widget_data']['workleavelist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif

	@overwrite

	@section('widget_body')
		<a href="{{ $WorkleaveComposer['widget_data']['workleavelist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($WorkleaveComposer['widget_data']['workleavelist']['workleave']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Cuti</th>
							<th>Quota Cuti</th>
							<th>Jenis</th>
							<th>Aktif</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<?php $i = $WorkleaveComposer['widget_data']['workleavelist']['workleave-display']['from'];?>
					@foreach($WorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
						<tbody>
							<tr>
								<td>
									{{$i}}
								</td>
								<td>
									{{$value['name']}}
								</td>
								<td>
									{{$value['quota']}}
								</td>
								<td>
									{{$value['status']}}
								</td>
								<td>
									@if($value['is_active'])
										<i class="fa fa-check"></i>
									@else
										<i class="fa fa-minus"></i>
									@endif
								</td>
								<td class="text-right">
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.workleaves.delete', [$value['id'], 'org_id' => $data['id']]) }}"><i class="fa fa-trash"></i></a>
									<a href="{{route('hr.workleaves.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
									<?php //<a href="{{route('hr.workleave.charts.create', ['workleave_id' => $value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-plus"></i></a>;?>
								</td>
							</tr>
						</tbody>
						<?php $i++;?>
					@endforeach
				</table>
			</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$WorkleaveComposer['widget_data']['workleavelist']['workleave-display']['from']!!} - {!!$WorkleaveComposer['widget_data']['workleavelist']['workleave-display']['to']!!}</p>
					{!!$WorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->appends(Input::all())->render()!!}
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