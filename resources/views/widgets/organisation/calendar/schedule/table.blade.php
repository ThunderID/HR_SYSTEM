@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	<h1> Jadwal </h1>
	<small>Total data {{$ScheduleComposer['widget_data']['schedulelist']['schedule-pagination']->total()}}</small>
@overwrite

@section('widget_body')	
	@if(isset($ScheduleComposer['widget_data']['schedulelist']['schedule']))
		<div class="clearfix">&nbsp;</div>
		<table class="table table-affix">
			<thead>
				<tr>
					<th>Label</th>
					<th>Tanggal</th>
					<th>Jam Kerja</th>
					<th>Status</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				@forelse($ScheduleComposer['widget_data']['schedulelist']['schedule'] as $key => $value)
					<tr>
						<td>
							{{$value['name']}}
						</td>
						<td>
							@date_indo($value['on'])
						</td>
						<td>
							@time_indo($value['start']) -
							@time_indo($value['end'])
						</td>
						<td>
							{{str_replace('_', ' ', $value['status'])}}
						</td>
						<td class="text-right">
							<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.calendar.schedules.delete', [$value['id'], 'org_id' => $data['id'], 'cal_id' => $calendar['id']]) }}"><i class="fa fa-trash"></i></a>
							<a href="{{route('hr.calendar.schedules.edit', [$value['id'], 'org_id' => $data['id'], 'cal_id' => $calendar['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<a href="{{route('hr.calendar.schedules.show', [$value['id'], 'org_id' => $data['id'], 'cal_id' => $calendar['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				@empty
					<tr>
						<td class="text-center" colspan="5">Tidak ada data</td>
					</tr>
				@endforelse
			</tbody>
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$ScheduleComposer['widget_data']['schedulelist']['schedule-display']['from']!!} - {!!$ScheduleComposer['widget_data']['schedulelist']['schedule-display']['to']!!}</p>
				{!!$ScheduleComposer['widget_data']['schedulelist']['schedule-pagination']->appends(Input::all())->render()!!}
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite
