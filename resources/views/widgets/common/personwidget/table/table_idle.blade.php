@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h4> {{ $IdleLogComposer['widget_data']['widgetlist']['title'] }} </h4>
		<small>Total data {{$IdleLogComposer['widget_data']['widgetlist']['idlelog-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		@if(isset($IdleLogComposer['widget_data']['widgetlist']['idlelog']))
			<table class="table table-affix">
				<thead>
					<tr class="">
						<th class="">No</th>
						<th class="">Nama</th>
						<th class="text-center">Total</th>
					</tr>
				</thead>				
				<tbody>
					<?php $i = $IdleLogComposer['widget_data']['widgetlist']['idlelog-display']['from'];?>
					@forelse($IdleLogComposer['widget_data']['widgetlist']['idlelog'] as $key => $value)
						<tr class="">
							<td>{{ $i }}</td>
							<td>{{ $value['processlog']['person']['name'] }}</td>
							<td></td>
						</tr>
						<?php $i++; ?>
					@empty
						<tr>
							<td class="text-center" colspan="2">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif