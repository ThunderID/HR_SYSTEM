@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h4> {{ $PersonWorkleaveComposer['widget_data']['widgetlist']['title'] }} </h4>
		<small>Total data {{$PersonWorkleaveComposer['widget_data']['widgetlist']['workleave-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		@if(isset($PersonWorkleaveComposer['widget_data']['widgetlist']['workleave']))	
			<table class="table table-affix">
				<thead>
					<tr class="">
						<th class="">No</th>
						<th class="">Nama</th>
						<th class="text-center">Cuti <br> (Hari)</th>
					</tr>
				</thead>				
				<tbody>
					<?php $i = $PersonWorkleaveComposer['widget_data']['widgetlist']['workleave-display']['from'];?>
					@forelse($PersonWorkleaveComposer['widget_data']['widgetlist']['workleave'] as $key => $value)
						<tr class="">
							<td>{{ $i }}</td>
							<td>{{ $value['person']['name'] }}</td>
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
			<div class="row">
				<div class="col-sm-12 text-right">
					<a href="javascript:;">Lihat detail</a>
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