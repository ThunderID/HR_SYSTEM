@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ $PersonComposer['widget_data']['personlist']['person-pagination']->total() }}</small>
	<?php
		$PersonComposer['widget_data']['personlist']['person-pagination']->setPath('hr.report.activities.index');
	 ?>

	 @if(isset($PersonComposer['widget_data']['personlist']['active_filter']) && !is_null($PersonComposer['widget_data']['personlist']['active_filter']))
		<div class="clearfix">&nbsp;</div>
		@foreach($PersonComposer['widget_data']['personlist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif
	
	<div class="btn-group pull-right">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-file"></i> Export to <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="{{route('hr.report.activities.index', array_merge(Input::all(), ['print' => 'yes', 'mode' => 'csv']))}}">CSV</a></li>
			<li><a href="{{route('hr.report.activities.index', array_merge(Input::all(), ['print' => 'yes', 'mode' => 'xls']))}}">XLS</a></li>
		</ul>
	</div>

	@overwrite

	@section('widget_body')
			@if(isset($PersonComposer['widget_data']['personlist']['person']))
				<div class="table-responsive">
					<table class="table table-condensed report">				
						<thead>
							<tr>
								<th class="col-sm-1">No</th>
								<th>Nama</th>
								<th class="hidden-xs">Jabatan</th>
								<th class="hidden-xs">Average Aktif</th>
								<th class="hidden-xs">Total Idle I / Freq</th>
								<th class="hidden-xs">Total Idle II / Freq</th>
								<th class="hidden-xs">Total Idle III / Freq</th>
								<!-- <th>Total Absence</th>
								<th>Possible Total Effective</th> -->
								<th class="hidden-xs">Time Loss Rate</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="table-responsive div-table-content">
					<table class="table table-condensed table-hover report">
						@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
							<tbody>
								<tr>
									<td class="col-sm-1 font-11">
										{{$key+1}}
									</td>
									<td class="font-11">
										{{$value['name']}}
									</td>
									<td class="hidden-xs font-11">
										@if($value['position']!='')
											{{$value['position']}} {{$value['department']}} {{$value['branch']}}
										@elseif(isset($value['works'][0]))
											{{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}}
										@endif
									</td>
									<td class="hidden-xs font-11">
										@if($value['position']!='')
											{{floor($value['total_active']/3600)}} Jam<br/>
											{{floor(($value['total_active']%3600)/60)}} Menit</br/> 
											{{floor(($value['total_active']%3600)%60)}} Detik
										@else
											Tidak ada aktivitas
										@endif
									</td>
									<td class="hidden-xs font-11">
										@if($value['position']!='')
											{{floor($value['total_idle_1']/3600)}} Jam<br/>
											{{floor(($value['total_idle_1']%3600)/60)}} Menit<br/> 
											{{floor(($value['total_idle_1']%3600)%60)}} Detik
											/ {{$value['frequency_idle_1']}}
										@else
											Tidak ada aktivitas
										@endif
									</td>
									<td class="hidden-xs font-11">
										@if($value['position']!='')
											{{floor($value['total_idle_2']/3600)}} Jam<br/>
											{{floor(($value['total_idle_2']%3600)/60)}} Menit<br/> 
											{{floor(($value['total_idle_2']%3600)%60)}} Detik
											/ {{$value['frequency_idle_2']}}
										@else
											Tidak ada aktivitas
										@endif
									</td>
									<td class="hidden-xs font-11">
										@if($value['position']!='')
											{{floor($value['total_idle_3']/3600)}} Jam<br/>
											{{floor(($value['total_idle_3']%3600)/60)}} Menit<br/> 
											{{floor(($value['total_idle_3']%3600)%60)}} Detik
											/ {{$value['frequency_idle_3']}}
										@else
											Tidak ada aktivitas
										@endif
									</td>
									<!-- <td>
										{{gmdate('H:i:s', $value['total_absence'])}}
									</td>
									<td>
										{{gmdate('H:i:s', $value['possible_total_effective'])}}
									</td> -->
									<td class="hidden-xs font-11">
										@if($value['position']!='')
											<?php $tlr = ($value['total_absence']!=0 ? $value['total_absence'] : 1) / ($value['possible_total_effective']!=0 ? $value['possible_total_effective'] : 1);?>
											{{round(abs($tlr) * 100, 2)}} %
										@else
											100 %
										@endif
									</td>
									<td class="text-right font-11">
										@if($value['position']!='')
											<a href="{{route('hr.attendance.persons.index', ['person_id' => $value['id'], 'org_id' => $data['id'], 'start' => $start, 'end' => $end])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
										@endif
									</td>
								</tr>
							</tbody>
						@endforeach
					</table>
				</div>

				<div class="row">
					<div class="col-sm-12 text-center mt-10">
						<p>Menampilkan {!! $PersonComposer['widget_data']['personlist']['person-display']['from']!!} - {!! count($PersonComposer['widget_data']['personlist']['person']) !!}</p>
						{!! $PersonComposer['widget_data']['personlist']['person-pagination']->appends(Input::all())->render()!!}
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