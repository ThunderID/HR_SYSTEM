@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Kalender </h1>
<small>Total data {{$CalendarComposer['widget_data']['calendarlist']['calendar-pagination']->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($CalendarComposer['widget_data']['calendarlist']['calendar']))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr>
					<th>Nama Kalender</th>
					<th>Hari Kerja</th>
					<th>Jam Kerja</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($CalendarComposer['widget_data']['calendarlist']['calendar'] as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$value['name']}}
						</td>
						<td>
							{{str_replace(',', ', ', $value['workdays'])}}
						</td>
						<td>
							@time_indo($value['start']) - 
							@time_indo($value['end'])
						</td>
						<td class="text-right">
							<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
							<a href="{{route('hr.calendars.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<a href="#" class="btn btn-default"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$CalendarComposer['widget_data']['calendarlist']['calendar-display']['from']!!} - {!!$CalendarComposer['widget_data']['calendarlist']['calendar-display']['to']!!}</p>
				{!!$CalendarComposer['widget_data']['calendarlist']['calendar-pagination']->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
