<html>
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Hak Cuti</th>
					<th>Penambah Cuti</th>
					<th>Pengurang Cuti</th>
					<th>Sisa Cuti</th>
					<th>Pengurang Gaji</th>
				</tr>
			</thead>
			@foreach($data as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$value['name']}}
						</td>
						<td>
							{{$value['position']}} di departemen {{$value['department']}} cabang {{$value['branch']}}
						</td>
						<td>
							{{$value['quotas']}}
						</td>
						<td>
							{{$value['plus_quotas']}}
						</td>
						<td>
							{{$value['minus_quotas']}}
						</td>
						<td>
							{{($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas'] >= 0 ? abs($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas']) : 0)}}
						</td>
						<td>
							{{($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas'] < 0 ? abs($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas']) : 0)}}
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>
	@endif
</body>
</html>