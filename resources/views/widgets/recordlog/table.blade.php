@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php $RecordLogComposer['widget_data']['recordlog']['recordlog-pagination']->setPath(route('hr.recordlogs.index')); ?>
	@section('widget_title')
		<h1> {!! $widget_title  or 'Otentikasi Group' !!} </h1>
		<small>Total data {{$RecordLogComposer['widget_data']['recordlog']['recordlog-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		@if(isset($RecordLogComposer['widget_data']['recordlog']['recordlog']))
			<div class="clearfix">&nbsp;</div>
				<table class="table table-hover table-affix">
					<thead>
						<tr>
							<th>No</th>
							<th class="">Notes</th>			
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $prev = null; $count = 0;?>
						<?php $i = $RecordLogComposer['widget_data']['recordlog']['recordlog-display']['from'];?>
							@forelse($RecordLogComposer['widget_data']['recordlog']['recordlog'] as $key => $value)
								<tr>
									<td>{{$i}}</td>
									<td> 
										{{ isset($value['person']['name']) ? $value['person']['name'] : 'System' }} {{ $value['notes'] }}
									</td>
									<td class="text-center">
										<?php /*
										<!-- @if ($value['action'] && in_array($value['action'], ['save']))
											{!! Form::open(['url' => route('hr.recordlogs.store', ['id' => $value['id']]), 'method' => 'post']) !!}
												<button type="submit" class="btn btn-sm @if($value['action']=='delete') btn-danger @elseif($value['action']=='save') btn-primary @else btn-default @endif">
													{{ ucwords($value['action']) }}
												</button>											
											{!! Form::close() !!}
										@endif -->
											*/;?>
									</td>
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
						<p>Menampilkan {!!$RecordLogComposer['widget_data']['recordlog']['recordlog-display']['from']!!} - {!!$RecordLogComposer['widget_data']['recordlog']['recordlog-display']['to']!!}</p>
						{!!$RecordLogComposer['widget_data']['recordlog']['recordlog-pagination']->appends(Input::all())->render()!!}					
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