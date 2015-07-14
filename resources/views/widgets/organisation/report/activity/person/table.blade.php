<?php
	ini_set('xdebug.max_nesting_level', 200);
?>
@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Akivitas' !!} </h1>
	<small>Total data {{ count($PersonComposer['widget_data']['personlist']['person']['processlogs']) }}</small>
	
	<div class="btn-group pull-right">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-file"></i> Export to <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="{{route('hr.activity.logs.index', array_merge(Input::all(), ['print' => 'yes', 'mode' => 'csv']))}}">CSV</a></li>
			<li><a href="{{route('hr.activity.logs.index', array_merge(Input::all(), ['print' => 'yes', 'mode' => 'xls']))}}">XLS</a></li>
		</ul>
	</div>
	@overwrite

	@section('widget_body')
		@if(isset($PersonComposer['widget_data']['personlist']['person']['processlogs']))
			<table class="table table-condensed report">
				<thead>
					<tr>
						<th rowspan="2" class="text-center" style="width:4%">No<br/>&nbsp;</th>
						<th rowspan="2" class="text-left">Tanggal<br/>&nbsp;</th>
						<th rowspan="2" class="text-center">Total Aktif<br/>&nbsp;</th>
						<th rowspan="2" class="text-center">Total Idle I <br/>(Freq)</th>
						<th rowspan="2" class="text-center">Total Idle II <br/>(Freq)</th>
						<th rowspan="2" class="text-center">Total Idle III <br/>(Freq)</th>
						<th rowspan="2" class="text-center">Performance Rate</th>
						<th rowspan="2">&nbsp;</th>
					</tr>
					<tr></tr>
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
							<td class="hidden-xs font-11 text-center">
								{{floor(($value['total_active']+$value['total_sleep']+$value['total_idle'])/3600)}} Jam<br/>
								{{floor((($value['total_active']+$value['total_sleep']+$value['total_idle'])%3600)/60)}} Menit</br/> 
							</td>
							<td class="hidden-xs font-11 text-center">
								{{floor($value['total_idle_1']/3600)}} Jam<br/>
								{{floor(($value['total_idle_1']%3600)/60)}} Menit<br/> 
								({{$value['frequency_idle_1']}})
							</td>
							<td class="hidden-xs font-11 text-center">
								{{floor($value['total_idle_2']/3600)}} Jam<br/>
								{{floor(($value['total_idle_2']%3600)/60)}} Menit<br/> 
								({{$value['frequency_idle_2']}})
							</td>
							<td class="hidden-xs font-11 text-center">
								{{floor($value['total_idle_3']/3600)}} Jam<br/>
								{{floor(($value['total_idle_3']%3600)/60)}} Menit<br/> 
								({{$value['frequency_idle_3']}})
							</td>
							<td class="hidden-xs font-11 text-center">
								<?php $pr = ($value['total_active']!=0 ? $value['total_active'] : 1) / (($value['total_active']+$value['total_sleep']+$value['total_idle'])!=0 ? ($value['total_active']+$value['total_sleep']+$value['total_idle']) : 1);?>
								{{round(abs($pr) * 100, 2)}} %
							</td>
							<td class="text-right font-11">
								<a href="{{route('hr.activity.logs.index', ['person_id' => $value['person_id'], 'org_id' => $data['id'], 'start' => $start, 'end' => $end, 'ondate' => $value['on']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
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