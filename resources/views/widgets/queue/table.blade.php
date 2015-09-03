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
									<td>
										<?php 
											$msg 			= json_decode($value['message']);
											$messagepure	= json_decode(json_encode($msg), true);
											$message 		= $messagepure['message'];
											$errors 		= isset($messagepure['errors']) ? $messagepure['errors'] : null;
										?>	
										@foreach ($message as $key2 => $value2)
											@if (isset($errors[$key2]))
												{{ $value2 }} <br> Error : <br>
												@foreach ($errors[$key2]['Batch'] as $key3 => $value3)
													@if(is_array($value3))
														@foreach ($value3 as $x => $y)
															@if(is_array($y))
																@foreach($y as $xx => $yy)
																	<span class="ml-10 label label-danger text-left">
																		{{ $x }} :  {{ $yy }}
																	</span>
																@endforeach
															@else
																<span class="ml-10 label label-danger text-left">
																	{{ $x }} : {{ $y }}
																</span>
															@endif
															<br>
														@endforeach
													@else
														<span class="ml-10 label label-danger text-left">
															{{ $value3 }} <br>
														</span>
													@endif
													<br>
												@endforeach
											@else
												{{ $value2 }} <br><br>
											@endif
										@endforeach
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