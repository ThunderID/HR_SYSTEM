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
					<th colspan="9" style="height:20%">Laporan Kehadiran {{$data['name']}} Per Tanggal {{$start}} - {{$end}} Unit Bisnis {{$org['name']}}</th>
				</tr>
				<tr>
					<th colspan="10"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th class="text-center" style="width:5%; height:40%">No<br/>&nbsp;</th>
					<th style="width:12%; height:40%">Tanggal<br/>&nbsp;</th>
					<th class="text-center" style="width:12%; height:40%">Jam Masuk<br/>&nbsp;(Jadwal)</th>
					<th class="text-center" style="width:12%; height:40%">Jam Keluar<br/>&nbsp;(Jadwal)</th>
					<th class="text-center" style="width:10%; height:40%">Time <br/>&nbsp;Loss <br/>&nbsp;Rate</th>
					<th class="text-center" style="width:8%; height:40%">Status <br/>&nbsp;Awal</th>
					<th class="text-center" style="width:8%; height:40%">Status <br/>&nbsp; Modifikasi</th>
					<th class="text-center" style="width:20%; height:40%">Dimodifikasi <br/>&nbsp;Oleh</th>
					<th class="text-center" style="width:12%; height:40%">Dimodifikasi <br/>&nbsp;Tanggal</th>
				</tr>
			</thead>
			@foreach($data['processlogs'] as $key => $value)
				<tbody>
					<tr>
						<td style="width:10%" style="height:35%">&nbsp;</td>
						<td class="font-11" style="height:35%">
							{{$key+1}}
						</td>
						<td class="font-11" style="height:35%">
							{{ date('d-m-Y', strtotime($value['on'])) }}
						</td>
						<td class="font-11 text-center" style="height:35%">
							{{ date('H:i:s', strtotime($value['start'])) }}<br/>&nbsp;
							({{ date('H:i:s', strtotime($value['schedule_start'])) }})
						</td>
						<td class="font-11 text-center" style="height:35%">
							{{ date('H:i:s', strtotime($value['end'])) }}<br/>&nbsp;
							({{ date('H:i:s', strtotime($value['schedule_end'])) }})
						</td>
						<td class="font-11" style="height:35%">
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

						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{$value['actual_status']}}
							<?php /*@if ($value['actual_status']=='AS')
								Ketidakhadiran Tanpa Penjelasan
							@elseif ($value['actual_status']=='CB')
								Cuti Bersama
							@elseif ($value['actual_status']=='CI')
								Cuti Istimewa
							@elseif ($value['actual_status']=='CN')
								Cuti Untuk Keperluan Pribadi
							@elseif ($value['actual_status']=='DN')
								Keperluan Dinas
							@elseif ($value['actual_status']=='HC')
								Hadir Cacat Tanpa Penjelasan
							@elseif ($value['actual_status']=='HD')
								Hadir Cacat Dengan Ijin Dinas
							@elseif ($value['actual_status']=='HP')
								Hadir Cacat Dengan Ijin Pulang Cepat
							@elseif ($value['actual_status']=='HT')
								Hadir Cacat Dengan Ijin Datang Terlambat
							@elseif ($value['actual_status']=='SS')
								Sakit Jangka Pendek
							@elseif ($value['actual_status']=='SL')
								Sakit Berkepanjangan
							@elseif ($value['actual_status']=='UL')
								Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia
							@endif; */?>
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{($value['modified_status']!='' ? $value['modified_status'] : '')}}
							<?php /*@if ($value['modified_status']=='AS')
								Ketidakhadiran Tanpa Penjelasan
							@elseif ($value['modified_status']=='CB')
								Cuti Bersama
							@elseif ($value['modified_status']=='CI')
								Cuti Istimewa
							@elseif ($value['modified_status']=='CN')
								Cuti Untuk Keperluan Pribadi
							@elseif ($value['modified_status']=='DN')
								Keperluan Dinas
							@elseif ($value['modified_status']=='HC')
								Hadir Cacat Tanpa Penjelasan
							@elseif ($value['modified_status']=='HD')
								Hadir Cacat Dengan Ijin Dinas
							@elseif ($value['modified_status']=='HP')
								Hadir Cacat Dengan Ijin Pulang Cepat
							@elseif ($value['modified_status']=='HT')
								Hadir Cacat Dengan Ijin Datang Terlambat
							@elseif ($value['modified_status']=='SS')
								Sakit Jangka Pendek
							@elseif ($value['modified_status']=='SL')
								Sakit Berkepanjangan
							@elseif ($value['modified_status']=='UL')
								Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia
							@endif
							*/?>
						</td>
						<td class="hidden-xs font-11" style="height:35%">
							{{($value['modified_status']!='' ? $value['modifiedby']['name'] : '')}}
						</td>
						<td class="hidden-xs font-11" style="height:35%">
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