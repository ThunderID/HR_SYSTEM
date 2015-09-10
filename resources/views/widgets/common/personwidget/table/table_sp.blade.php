@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h4> {{ $AttendanceDetailComposer['widget_data']['widgetlist']['title'] }} </h4>
		<small>Total data {{$AttendanceDetailComposer['widget_data']['widgetlist']['attendancedetail-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		@if(isset($AttendanceDetailComposer['widget_data']['widgetlist']['attendancedetail']))
			<table class="table table-affix">
				<thead>
					<tr class="">
						<th class="">No</th>
						<th class="">Nama</th>
					</tr>
				</thead>				
				<tbody>
					<?php $i = $AttendanceDetailComposer['widget_data']['widgetlist']['attendancedetail-display']['from'];?>
					@forelse($AttendanceDetailComposer['widget_data']['widgetlist']['attendancedetail'] as $key => $value)
						<tr class="">
							<td>{{ $i }}</td>
							<td>{{ $value['name'] }}</td>
						</tr>
						<?php $i++; ?>
					@empty
						<tr>
							<td class="text-center" colspan="2">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>
			<div class="row">
				<div class="col-sm-12 text-right">
					<a href="javascript:;" class="btn btn-primary">Lihat Semua</a>
				</div>
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif