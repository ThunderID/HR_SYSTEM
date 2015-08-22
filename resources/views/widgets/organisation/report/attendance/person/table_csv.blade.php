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
					<th class="text-center" style="width:5%; height:40%">No</th>
					<th style="width:12%; height:40%">Tanggal</th>
					<th class="text-center" style="width:12%; height:40%">Jam Masuk<br/>&nbsp;(Jadwal)</th>
					<th class="text-center" style="width:12%; height:40%">Jam Keluar<br/>&nbsp;(Jadwal)</th>
					<th class="text-center" style="width:8%; height:40%">Status Awal</th>
					<th class="text-center" style="width:8%; height:40%">Status Modifikasi</th>
					<th class="text-center" style="width:20%; height:40%">Dimodifikasi Oleh</th>
					<th class="text-center" style="width:12%; height:40%">Dimodifikasi Tanggal</th>
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
						@if(isset($value['attendancelogs'][0]))
							<td style="height:35%">
								{{$value['attendancelogs'][0]['actual_status']}}
							</td>
							<td style="height:35%">
								{{($value['attendancelogs'][0]['modified_status']!='' ? $value['attendancelogs'][0]['modified_status'] : '')}}
							</td>
							<td style="height:35%">
								{{($value['attendancelogs'][0]['modified_status']!='' ? $value['attendancelogs'][0]['modifiedby']['name'] : '')}}
							</td>
							<td style="height:35%">
								@if($value['attendancelogs'][0]['modified_status']!='')
									{{ date('d-m-Y', strtotime($value['attendancelogs'][0]['modified_at'])) }}
								@endif
							</td>
						@else
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						@endif
					</tr>
				</tbody>
			@endforeach
		</table>
	@endif
</body>
</html>