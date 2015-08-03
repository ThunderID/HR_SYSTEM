@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ count($PersonComposer['widget_data']['personlist']['person']) }}</small>
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
			<table class="table table-affix">				
				<thead>
					<tr>
						<th rowspan="2" class="text-center font-11" style="width:4%">No<br/>&nbsp;</th>
						<th rowspan="2" class="text-left font-11">Nama <br/>(Jabatan)</th>
						<th rowspan="2" class="text-center font-11">Total Aktif <br/>(Hari)</th>
						<th rowspan="2" class="text-center font-11">Total Idle I <br/>(Freq)</th>
						<th rowspan="2" class="text-center font-11">Total Idle II <br/>(Freq)</th>
						<th rowspan="2" class="text-center font-11">Total Idle III <br/>(Freq)</th>
						<!-- <th rowspan="2" class="text-center font-11">Performance Rate</th> -->
						<th rowspan="2">&nbsp;</th>
					</tr>
					<tr></tr>
				</thead>
				<tbody>
					@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
						<tr>
							<td class="col-sm-1 font-11 text-center">
								{{$key+1}}
							</td>
							<td class="font-11">
								{{$value['name']}}<br/>
								@if($value['position']!='')
									({{$value['position']}} {{$value['department']}} {{$value['branch']}})
								@elseif(isset($value['works'][0]))
									({{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}})
								@endif
							</td>
							<td class="font-11 text-center">
								@if($value['position']!='')
									{{floor($value['total_presence']/3600)}} Jam<br/>
									{{floor(($value['total_presence']%3600)/60)}} Menit<br/> 
									({{$value['total_days']}})
								@else
									Tidak ada aktivitas
								@endif
							</td>
							<td class="font-11 text-center">
								@if($value['position']!='')
									{{floor($value['total_idle_1']/3600)}} Jam<br/>
									{{floor(($value['total_idle_1']%3600)/60)}} Menit<br/> 
									({{$value['frequency_idle_1']}})
								@else
									Tidak ada aktivitas
								@endif
							</td>
							<td class="font-11 text-center">
								@if($value['position']!='')
									{{floor($value['total_idle_2']/3600)}} Jam<br/>
									{{floor(($value['total_idle_2']%3600)/60)}} Menit<br/> 
									({{$value['frequency_idle_2']}})
								@else
									Tidak ada aktivitas
								@endif
							</td>
							<td class="font-11 text-center">
								@if($value['position']!='')
									{{floor($value['total_idle_3']/3600)}} Jam<br/>
									{{floor(($value['total_idle_3']%3600)/60)}} Menit<br/> 
									({{$value['frequency_idle_3']}})
								@else
									Tidak ada aktivitas
								@endif
							</td>
							<!-- <td class="font-11 text-center">
								@if($value['position']!='')
									<?php $pr = ($value['total_active']!=0 ? $value['total_active'] : 1) / ($value['total_presence']!=0 ? $value['total_presence'] : 1);?>
									{{round(abs($pr) * 100, 2)}} %
								@endif
							</td> -->
							<td class="text-center font-11">
								@if($value['position']!='')
									<a href="{{route('hr.report.activities.show', ['person_id' => $value['id'], 'org_id' => $data['id'], 'start' => $start, 'end' => $end])}}" class="btn btn-sm btn-default"><i class="fa fa-eye"></i></a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

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