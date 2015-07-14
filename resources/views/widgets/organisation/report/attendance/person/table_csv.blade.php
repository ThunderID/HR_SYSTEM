<html>
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th colspan="10"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th colspan="9" style="height:20%">Laporan Kehadiran {{$data['name']}} Per Tanggal {{$start}} s/d {{$end}} Unit Bisnis {{$org['name']}}</th>
				</tr>
				<tr>
					<th colspan="10"></th>
				</tr>
				<tr>
					<th rowspan="2" class="text-center" style="width:10%; height:35%">&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:4%; height:35%">No<br/>&nbsp;</th>
					<th rowspan="2" class="text-left" style="width:20%; height:35%">Tanggal<br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Jam Masuk<br/>(Jadwal);</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Jam Keluar <br/>(Jadwal)</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Time Loss Rate <br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Status Awal <br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Status Modifikasi <br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Dimodifikasi Oleh <br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Dimodifikasi Tanggal<br/>&nbsp;</th>
				</tr>
				<tr></tr>				
			</thead>
			<tbody>
				@foreach($data['processlogs'] as $key => $value)
					<tr>
						<td style="width:10%">&nbsp;</td>
						<td style="height:35%">{{$key+1}}</td>
						<td style="height:35%">
							{{ date('d-m-Y', strtotime($value['on'])) }}
						</td>
						<td style="text-align:center">
							{{ date('H:i:s', strtotime($value['start'])) }}
								({{ date('H:i:s', strtotime($value['schedule_start'])) }})
						</td>
						<td style="text-align:center">
							{{ date('H:i:s', strtotime($value['end'])) }}
								({{ date('H:i:s', strtotime($value['schedule_end'])) }})
						</td>
						<td style="text-align:center">
							<?php
								$margin_start = 0;
								$margin_end = 0;
								if(in_array($value['modified_status'], ['HC', 'AS', 'UL', 'SS', 'LL']) || ($value['modified_status']=='' && in_array($value['actual_status'], ['HC', 'AS'])))
								{
									if($value['margin_start']<0)
									{
										$margin_start=abs($value['margin_start']);
									}
									if($value['margin_end']<0)
									{
										$margin_end=abs($value['margin_end']);
									}
								}

								$total_absence = $margin_end + $margin_start;

								list($hours, $minutes, $seconds) = explode(":", $value['schedule_end']);

								$schedule_end_second	= $hours*3600+$minutes*60+$seconds;

								list($hours, $minutes, $seconds) = explode(":", $value['schedule_start']);

								$schedule_start_second	= $hours*3600+$minutes*60+$seconds;

								$tlr = ($total_absence!=0 ? $total_absence : 1) / (abs($schedule_end_second - $schedule_start_second)!=0 ? abs($schedule_end_second - $schedule_start_second) : 1);?>
							{{round(abs($tlr) * 100, 2)}} %
						</td>
						<td style="text-align:center">
							{{$value['actual_status']}}
						</td>
						<td style="text-align:center">
							{{($value['modified_status']!='' ? $value['modified_status'] : '')}}
						</td>
						<td style="text-align:center">
							{{($value['modified_status']!='' ? $value['modifiedby']['name'] : '')}}
						</td>
						<td style="text-align:center">
							@if($value['modified_status']!='')
								{{ date('d-m-Y', strtotime($value['modified_at'])) }}
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

	@endif
</body>
</html>