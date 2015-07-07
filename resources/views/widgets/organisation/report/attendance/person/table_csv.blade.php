<html>
<body>
	@if(isset($data['processlogs']))
		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal</th>
					
					<th>Time Loss Rate</th>
					<th>Status Awal</th>
					<th>Status Modifikasi</th>
					<th>Dimodifikasi Oleh</th>
					<th>Dimodifikasi Tanggal</th>
				</tr>
			</thead>
			@foreach($data['processlogs'] as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$key+1}}
						</td>
						<td>
							{{ date('d-m-Y', strtotime($value['on'])) }}
						</td>
						<td>
							<?php
								$margin_start = 0;
								$margin_end = 0;
								if(in_array($value['modified_status'], ['HC', 'AS', 'UL', 'SS', 'LL']) || ($value['modified_status']=='' && in_array($value['actual_status'], ['HC', 'AS'])))
								{
									if($value['margin_start']<0)
									{
										$margin_start=abs($value['margin_start']);
									}
									// else
									// {
									// 	$margin_start=($value['margin_start']);
									// }
									if($value['margin_end']<0)
									{
										$margin_end=abs($value['margin_end']);
									}
									// else
									// {
									// 	$margin_end=($value['margin_end']);
									// }
								}

								$total_absence = $margin_end + $margin_start;

								list($hours, $minutes, $seconds) = explode(":", $value['schedule_end']);

								$schedule_end_second	= $hours*3600+$minutes*60+$seconds;

								list($hours, $minutes, $seconds) = explode(":", $value['schedule_start']);

								$schedule_start_second	= $hours*3600+$minutes*60+$seconds;

								$tlr = ($total_absence!=0 ? $total_absence : 1) / (abs($schedule_end_second - $schedule_start_second)!=0 ? abs($schedule_end_second - $schedule_start_second) : 1);?>
							{{round(abs($tlr) * 100, 2)}} %
						</td>
						<td>
							{{$value['actual_status']}}
						</td>
						<td>
							{{($value['modified_status']!='' ? $value['modified_status'] : '')}}
						</td>
						<td>
							{{($value['modified_status']!='' ? $value['modifiedby']['name'] : '')}}
						</td>
						<td>
							@if($value['modified_status']!='')
								{{ date('d-m-Y', strtotime($value['modified_at'])) }}
							@endif
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>
	@endif
</body>
</html>