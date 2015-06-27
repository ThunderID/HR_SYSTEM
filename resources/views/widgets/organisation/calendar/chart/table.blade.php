@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php
		$FollowComposer['widget_data']['followlist']['follow-pagination']->setPath(route('hr.calendar.charts.index'));
	?>

	@section('widget_title')
	<h1> {!! $widget_title or 'Kalender Kerja' !!} </h1>
	<small>Total data {{$FollowComposer['widget_data']['followlist']['follow-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		<a href="{{route('hr.calendar.charts.create', ['cal_id' => $calendar['id'], 'org_id' => $data['id']])}}" class="btn btn-primary">Tambah</a>
		@if(isset($FollowComposer['widget_data']['followlist']['follow']))
			<div class="clearfix">&nbsp;</div>
			<table class="table">
				<thead>
					<tr>
						<th>Jabatan</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				@foreach($FollowComposer['widget_data']['followlist']['follow'] as $key => $value)
					<tbody>
						<tr>
							<td>
								{{$value['chart']['name']}} departemen {{$value['chart']['tag']}} cabang {{$value['chart']['branch']['name']}}
							</td>
							<td class="text-right">
								<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.calendar.charts.delete', [$value['id'], 'org_id' => $data['id'], 'cal_id' => $calendar['id']]) }}"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					</tbody>
				@endforeach
			</table>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$FollowComposer['widget_data']['followlist']['follow-display']['from']!!} - {!!$FollowComposer['widget_data']['followlist']['follow-display']['to']!!}</p>
					{!!$FollowComposer['widget_data']['followlist']['follow-pagination']->appends(Input::all())->render()!!}
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