@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php
		$IdleComposer['widget_data']['idlelist']['idle-pagination']->setPath(route('hr.idles.index'));
	?>

	@section('widget_title')
		<h1> {!! $widget_title or 'Idle' !!} </h1>
		<small>Total data {{$IdleComposer['widget_data']['idlelist']['idle-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		<a href="{{ $IdleComposer['widget_data']['idlelist']['route_create'] }}" class="btn btn-primary">Tambah</a>
		@if(isset($IdleComposer['widget_data']['idlelist']['idle']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Sejak</th>
							<th>Idle Pertama</th>
							<th>Idle Kedua</th>
							<th>Idle Ketiga</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					@foreach($IdleComposer['widget_data']['idlelist']['idle'] as $key => $value)
						<tbody>
							<tr>
								<td>
									@date_indo($value['start'])
								</td>
								<td>
									00:00:00 - {{gmdate('H:i:s', $value['idle_1'])}}
								</td>
								<td>
									{{gmdate('H:i:s', ($value['idle_1'] + 1))}} - {{gmdate('H:i:s', $value['idle_2'])}}
								</td>
								<td>
									{{gmdate('H:i:s', ($value['idle_2'] + 1))}} - Infinite
								</td>
								<td class="text-right">
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.idles.delete', [$value['id'], 'org_id' => $data['id'] ]) }}"><i class="fa fa-trash"></i></a>
									<a href="{{route('hr.idles.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
								</td>
							</tr>
						</tbody>
					@endforeach
				</table>
			</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$IdleComposer['widget_data']['idlelist']['idle-display']['from']!!} - {!!$IdleComposer['widget_data']['idlelist']['idle-display']['to']!!}</p>
					{!!$IdleComposer['widget_data']['idlelist']['idle-pagination']->appends(Input::all())->render()!!}
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