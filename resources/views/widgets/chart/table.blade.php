@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ $widget_title or 'Jabatan' }} </h1>
		<small>Total data {{$ChartComposer['widget_data']['chartlist']['chart-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		@if(isset($ChartComposer['widget_data']['chartlist']['chart']))
			<div class="clearfix">&nbsp;</div>
			<table class="table">
				<thead>
					<tr>
						<th class="text-center">Min Pegawai</th>
						<th class="text-center">Ideal Pegawai</th>
						<th class="text-center">Total Pegawai</th>
						<th class="text-center">Max Pegawai</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center">
							{{ $ChartComposer['widget_data']['chartlist']['chart']['min_employee'] }}
						</td>
						<td class="text-center">
							{{ $ChartComposer['widget_data']['chartlist']['chart']['ideal_employee'] }}
						</td>
						<td class="text-center">
							{{ $ChartComposer['widget_data']['chartlist']['chart']['current_employee'] }}
						</td>
						<td class="text-center">
							{{ $ChartComposer['widget_data']['chartlist']['chart']['max_employee'] }}
						</td>
					</tr>
				</tbody>
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