@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title  or 'Kalender' !!} </h1>
	<small>Total data {{$CalendarComposer['widget_data']['calendarlist']['calendar-pagination']->total()}}</small>
	
	<div class="clearfix">&nbsp;</div>
	@if(!is_null($CalendarComposer['widget_data']['calendarlist']['active_filter']))
		@foreach($CalendarComposer['widget_data']['calendarlist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif

	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')<=3)
			<a href="{{ $CalendarComposer['widget_data']['calendarlist']['route_create'] }}" class="btn btn-primary">Tambah</a>
		@endif
		@if(isset($CalendarComposer['widget_data']['calendarlist']['calendar']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr class="row">
						<th class="col-sm-1">No</th>
						<th class="col-sm-2">Nama Kalender</th>
						<th class="col-sm-4">Hari Kerja</th>
						<th class="col-sm-2">Jam Kerja</th>
						<th class="col-sm-3">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $CalendarComposer['widget_data']['calendarlist']['calendar-display']['from'];?>
					@forelse($CalendarComposer['widget_data']['calendarlist']['calendar'] as $key => $value)
						<tr class="row">
							<td>
								{{$i}}
							</td>
							<td>
								{{$value['name']}}
							</td>
							<td>
								{{str_replace(',', ', ', $value['workdays'])}}
							</td>
							<td>
								{{ date('H:i', strtotime($value['start'])) }} -  {{ date('H:i', strtotime($value['end'])) }}
							</td>
							<td class="text-right">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pengaturan <span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-right">
										@if((int)Session::get('user.menuid')<=2)
											<li>
												<a href="javascript:;" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.calendars.delete', [$value['id'], 'org_id' => $data['id']]) }}"><i class="fa fa-trash fa-fw"></i> Hapus</a>
											</li>
										@endif
										@if((int)Session::get('user.menuid')<=3)
											<li>
												<a href="{{route('hr.calendars.edit', [$value['id'], 'org_id' => $data['id']])}}"><i class="fa fa-pencil fa-fw"></i> Ubah</a>
											</li>
										@endif
										<li>
											<a href="{{route('hr.calendars.show', [$value['id'], 'org_id' => $data['id']])}}"><i class="fa fa-eye fa-fw"></i> Detail</a>
										</li>
										<li>
											<a href="{{route('hr.calendar.charts.index', ['cal_id' => $value['id'], 'org_id' => $data['id']])}}"><i class="fa fa-list fa-fw"></i> Kalendar Struktur Organisasi</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<?php $i++;?>
					@empty 
						<tr>
							<td class="text-center" colspan="5">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
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
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif