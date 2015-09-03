@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {!! $widget_title  or 'Otentikasi Group' !!} </h1>
		<small>Total data {{$QueueComposer['widget_data']['queue']['queue-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')	
		@if(isset($QueueComposer['widget_data']['queue']['queue']))
			<div class="clearfix">&nbsp;</div>
				<table class="table table-hover table-affix">
					<thead>
						<tr>
							<th>No</th>
							<th class="">Message</th>			
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $prev = null; $count = 0;?>
						<?php $i = $QueueComposer['widget_data']['queue']['queue-display']['from'];?>
							@forelse($QueueComposer['widget_data']['queue']['queue'] as $key => $value)
								<tr>
									<td>{{ $i }}</td>
									<td>{{ $value['message'] }}</td>
								</tr>		
								<?php $i++; ?>
							@empty 
								<tr>
									<td class="text-center" colspan="3">Tidak ada data</td>
								</tr>
							@endforelse

					</tbody>
				</table>
		
				<div class="row">
					<div class="col-sm-12 text-center">					
						<p>Menampilkan {!!$QueueComposer['widget_data']['queue']['queue-display']['from']!!} - {!!$QueueComposer['widget_data']['queue']['queue-display']['to']!!}</p>
						{!!$QueueComposer['widget_data']['queue']['queue-pagination']->appends(Input::all())->render()!!}					
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