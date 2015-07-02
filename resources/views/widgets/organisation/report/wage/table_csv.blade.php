<html>
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th>Nama</th>
					<th>Jabatan</th>
					<!-- <th>Hak Cuti</th>
					<th>Penambah Cuti</th>
					<th>Pengurang Cuti</th>
					<th>Sisa Cuti</th>
					 -->
					 <th>Jumlah Absen (hari)</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$value['name']}}
						</td>
						<td>
							{{$value['position']}} {{$value['department']}}  {{$value['branch']}}
						</td>
						<!-- <td>
							{{$value['quotas']}}
						</td>
						<td>
							{{$value['plus_quotas']}}
						</td> -->
						<td>
							{{$value['minus_quotas']}}
						</td>
						<!-- <td>
							{{($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas'] >= 0 ? abs($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas']) : 0)}}
						</td>
						<td>
							{{($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas'] < 0 ? abs($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas']) : 0)}}
						</td> -->
						<td class="text-right">
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

	@endif
</body>
</html>