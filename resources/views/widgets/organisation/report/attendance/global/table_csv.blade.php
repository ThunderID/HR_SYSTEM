<html>
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th colspan="7"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th colspan="6" style="height:20%">Laporan Kehadiran Per Tanggal {{$start}} - {{$end}} Unit Bisnis {{$org['name']}}</th>
				</tr>
				<tr>
					<th colspan="7"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th rowspan="2" style="width:4%; height:25%">No</th>
					<th rowspan="2" style="width:30%; height:25%">NIK </th>
					<th rowspan="2" style="width:30%; height:25%">Nama </th>
					<th rowspan="2" style="width:25%; height:25%">Tanggal</th>
					<th rowspan="2" style="width:30%; height:25%">Jam Masuk Jadwal</th>					
					<th rowspan="2" style="width:30%; height:25%">Jam Keluar Jadwal</th>
					<th rowspan="2" style="width:30%; height:25%">Jam Datang Karyawan</th>					
					<th rowspan="2" style="width:30%; height:25%">Jam Pulang Karyawan</th>
					<th rowspan="2" style="width:25%; height:25%">Status Terakhir</th>
				</tr>
				<tr></tr>
			</thead>
			<tbody>
				@foreach($data as $key => $value)
					<tr>
						<td style="width:10%">&nbsp;</td>
						<td style="text-align:center; vertical-align:top; width:4%; height:35%" @if(count($value['processlogs'])!=0) rowspan="{{count($value['processlogs'])}}" @endif>{{$key+1}}</td>
						<td style="text-align:left; vertical-align:top; width:30%; height:35%" @if(count($value['processlogs'])!=0) rowspan="{{count($value['processlogs'])}}" @endif>
							{{$value['name']}}
						</td>
						<td style="text-align:left; vertical-align:top; width:30%; height:35%" @if(count($value['processlogs'])!=0) rowspan="{{count($value['processlogs'])}}" @endif>
							{{$value['uniqid']}}
						</td>
						@if(isset($value['processlogs'][0]))
							<td style="text-align:center; width:25%; height:35%">
								{{ date('d-m-Y', strtotime($value['processlogs'][0]['on'])) }}
							</td>
							<td style="text-align:center; width:30%; height:35%">
								{{ date('H:i:s', strtotime($value['processlogs'][0]['schedule_start'])) }}
							</td>
							<td style="text-align:center; width:30%; height:35%">
								{{ date('H:i:s', strtotime($value['processlogs'][0]['schedule_end'])) }}
							</td>
							<td style="text-align:center; width:30%; height:35%">
								@if (strtotime($value['processlogs'][0]['fp_start']) > strtotime($value['processlogs'][0]['start']))
									{{ date('H:i:s', strtotime($value['processlogs'][0]['fp_start'])) }}
								@else
									{{ date('H:i:s', strtotime($value['processlogs'][0]['start'])) }}
								@endif
							</td>
							<td style="text-align:center; width:30%; height:35%">
								@if (strtotime($value['processlogs'][0]['fp_end']) > strtotime($value['processlogs'][0]['end'])))
									{{ date('H:i:s', strtotime($value['processlogs'][0]['fp_end'])) }}
								@else
									{{ date('H:i:s', strtotime($value['processlogs'][0]['end'])) }}
								@endif
							</td>
							<td style="text-align:center; width:25%; height:35%">
								@if(isset($value['processlogs'][0]['attendancelogs'][0]))
									{{($value['processlogs'][0]['attendancelogs'][0]['modified_status']!='' ? $value['processlogs'][0]['attendancelogs'][0]['modified_status'] : $value['processlogs'][0]['attendancelogs'][0]['actual_status'])}}
								@endif
							</td>
						@else
							<td style="text-align:center; width:25%; height:35%">-</td>
							<td style="text-align:center; width:30%; height:35%">-</td>
							<td style="text-align:center; width:30%; height:35%">-</td>
							<td style="text-align:center; width:25%; height:35%">-</td>
							<td style="text-align:center; width:25%; height:35%">-</td>
							<td style="text-align:center; width:25%; height:35%">-</td>
						@endif
					</tr>
					@foreach($value['processlogs'] as $key2 => $value2)
						@if($key2!=0)
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td style="text-align:center; width:25%; height:35%">
								{{ date('d-m-Y', strtotime($value2['on'])) }}
							</td>
							<td style="text-align:center; width:30%; height:35%">
								{{ date('H:i:s', strtotime($value2['schedule_start'])) }}
							</td>
							<td style="text-align:center; width:30%; height:35%">
								{{ date('H:i:s', strtotime($value2['schedule_end'])) }}
							</td>
							<td style="text-align:center; width:30%; height:35%">
								@if (strtotime($value2['fp_start']) > strtotime($value2['start']))
									{{ date('H:i:s', strtotime($value2['fp_start'])) }}
								@else
									{{ date('H:i:s', strtotime($value2['start'])) }}
								@endif
							</td>
							<td style="text-align:center; width:30%; height:35%">
								@if (strtotime($value2['fp_end']) > strtotime($value2['end']))
									{{ date('H:i:s', strtotime($value2['fp_end'])) }}
								@else
									{{ date('H:i:s', strtotime($value2['end'])) }}
								@endif
							</td>
							<td style="text-align:center; width:25%; height:35%">
								@if(isset($value2['attendancelogs'][0]))
									{{($value2['attendancelogs'][0]['modified_status']!='' ? $value2['attendancelogs'][0]['modified_status'] : $value2['attendancelogs'][0]['actual_status'])}}
								@endif
							</td>
						</tr>
						@endif
					@endforeach
				@endforeach
			</tbody>
		</table>

	@endif
</body>
</html>