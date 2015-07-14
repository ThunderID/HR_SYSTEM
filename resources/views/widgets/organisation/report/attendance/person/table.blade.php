<?php
	ini_set('xdebug.max_nesting_level', 200);
?>
@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ count($PersonComposer['widget_data']['personlist']['person']['processlogs']) }}</small>
	
	<div class="btn-group pull-right">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-file"></i> Export to <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="{{route('hr.attendance.persons.index', array_merge(Input::all(), ['print' => 'yes', 'mode' => 'csv']))}}">CSV</a></li>
			<li><a href="{{route('hr.attendance.persons.index', array_merge(Input::all(), ['print' => 'yes', 'mode' => 'xls']))}}">XLS</a></li>
		</ul>
	</div>
	@overwrite

	@section('widget_body')
		@if(isset($PersonComposer['widget_data']['personlist']['person']['processlogs']))
			<table class="table table-condensed report">
				<thead>
					<tr>
						<th class="text-center" style="width:5%">No<br/>&nbsp;</th>
						<th style="width:12%">Tanggal<br/>&nbsp;</th>
						<th class="text-center" style="width:12%">Jam Masuk<br/>(Jadwal)</th>
						<th class="text-center" style="width:12%">Jam Keluar<br/>(Jadwal)</th>
						<th class="text-center" style="width:10%">Time Loss Rate</th>
						<th class="text-center" style="width:8%">Status Awal</th>
						<th class="text-center" style="width:8%">Status Modifikasi</th>
						<th class="text-center" style="width:12%">Dimodifikasi Oleh</th>
						<th class="text-center" style="width:12%">Dimodifikasi Tanggal</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				@foreach($PersonComposer['widget_data']['personlist']['person']['processlogs'] as $key => $value)
					<tbody>
						<tr>
							<td class="font-11">
								{{$key+1}}
							</td>
							<td class="font-11">
								{{ date('d-m-Y', strtotime($value['on'])) }}
							</td>
							<td class="font-11 text-center">
								{{ date('H:i:s', strtotime($value['start'])) }}
								({{ date('H:i:s', strtotime($value['schedule_start'])) }})
							</td>
							<td class="font-11 text-center">
								{{ date('H:i:s', strtotime($value['end'])) }}
								({{ date('H:i:s', strtotime($value['schedule_end'])) }})
							</td>
							<td class="font-11">
								<?php
									$margin_start = 0;
									$margin_end = 0;
									if(in_array($value['modified_status'], ['HC', 'AS', 'UL', 'SS', 'LL']) || ($value['modified_status']=='' && in_array($value['actual_status'], ['HC', 'AS'])))
									{
										if($value['margin_start']<0)
										{
											$margin_start=abs($value['margin_start']);
										}
										if($value['margin_end']<0)
										{
											$margin_end=abs($value['margin_end']);
										}
									}

									$total_absence = $margin_end + $margin_start;

									list($hours, $minutes, $seconds) = explode(":", $value['schedule_end']);

									$schedule_end_second	= $hours*3600+$minutes*60+$seconds;

									list($hours, $minutes, $seconds) = explode(":", $value['schedule_start']);

									$schedule_start_second	= $hours*3600+$minutes*60+$seconds;

									$tlr = ($total_absence!=0 ? $total_absence : 1) / (abs($schedule_end_second - $schedule_start_second)!=0 ? abs($schedule_end_second - $schedule_start_second) : 1);?>
								{{round(abs($tlr) * 100, 2)}} %
							</td>

							<td class="hidden-xs font-11 text-center">
								<a href="javascript:;" class="black cursor-text" data-toggle-tooltip="tooltip" data-placement="bottom" title="
									@if ($value['actual_status']=='AS')
										Ketidakhadiran Tanpa Penjelasan
									@elseif ($value['actual_status']=='CB')
										Cuti Bersama
									@elseif ($value['actual_status']=='CI')
										Cuti Istimewa
									@elseif ($value['actual_status']=='CN')
										Cuti Untuk Keperluan Pribadi
									@elseif ($value['actual_status']=='DN')
										Keperluan Dinas
									@elseif ($value['actual_status']=='HC')
										Hadir Cacat Tanpa Penjelasan
									@elseif ($value['actual_status']=='HD')
										Hadir Cacat Dengan Ijin Dinas
									@elseif ($value['actual_status']=='HP')
										Hadir Cacat Dengan Ijin Pulang Cepat
									@elseif ($value['actual_status']=='HT')
										Hadir Cacat Dengan Ijin Datang Terlambat
									@elseif ($value['actual_status']=='SS')
										Sakit Jangka Pendek
									@elseif ($value['actual_status']=='SL')
										Sakit Berkepanjangan
									@elseif ($value['actual_status']=='UL')
										Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia
									@endif
								">
									{{$value['actual_status']}}
								</a>
							</td>
							<td class="hidden-xs font-11 text-center">
								<a href="javascript:;" class="black cursor-text" data-toggle-tooltip="tooltip" data-placement="bottom" title="
									@if ($value['modified_status']=='AS')
										Ketidakhadiran Tanpa Penjelasan
									@elseif ($value['modified_status']=='CB')
										Cuti Bersama
									@elseif ($value['modified_status']=='CI')
										Cuti Istimewa
									@elseif ($value['modified_status']=='CN')
										Cuti Untuk Keperluan Pribadi
									@elseif ($value['modified_status']=='DN')
										Keperluan Dinas
									@elseif ($value['modified_status']=='HC')
										Hadir Cacat Tanpa Penjelasan
									@elseif ($value['modified_status']=='HD')
										Hadir Cacat Dengan Ijin Dinas
									@elseif ($value['modified_status']=='HP')
										Hadir Cacat Dengan Ijin Pulang Cepat
									@elseif ($value['modified_status']=='HT')
										Hadir Cacat Dengan Ijin Datang Terlambat
									@elseif ($value['modified_status']=='SS')
										Sakit Jangka Pendek
									@elseif ($value['modified_status']=='SL')
										Sakit Berkepanjangan
									@elseif ($value['modified_status']=='UL')
										Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia
									@endif
								">
									{{($value['modified_status']!='' ? $value['modified_status'] : '')}}
								</a>
							</td>
							<td class="hidden-xs font-11">
								{{($value['modified_status']!='' ? $value['modifiedby']['name'] : '')}}
							</td>
							<td class="hidden-xs font-11">
								@if($value['modified_status']!='')
									{{ date('d-m-Y', strtotime($value['modified_at'])) }}
								@endif
							</td>
							<td class="text-right">
								<a href="{{route('hr.report.attendances.edit', array_merge(['id' => $value['id'], 'start' => $start, 'end' => $end, 'person_id' => $person['id'], 'org_id' => $data['id'], 'ondate' => $value['on']])) }}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							</td>
						</tr>
					</tbody>
				@endforeach
			</table>
			<div class="clearfix">&nbsp;</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif