<html>
<body>
	<table class="table">
		<thead>
			<tr>
				<th colspan="5"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th colspan="4" style="height:20%">Laporan Aktivitas {{$data['name']}} Per Tanggal {{$ondate}} Unit Bisnis {{$org['name']}}</th>
				</tr>
				<tr>
					<th colspan="5"></th>
				</tr>
				<tr>
					<th rowspan="2" class="text-center" style="width:10%; height:35%">&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:4%; height:35%">No<br/>&nbsp;</th>
					<th rowspan="2" class="text-left" style="width:20%; height:35%">Tanggal<br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Aktivitas<br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">PC<br/>&nbsp;</th>
				</tr>
				<tr></tr>
		</thead>
		@foreach($data['logs'] as $key => $value)
			<tbody>
				<tr>
					<td style="width:10%;height:35%">&nbsp;</td>
					<td style="height:35%">
						{{$key+1}}
					</td>
					<td style="height:35%">
						{{ date('d-m-Y H:i', strtotime($value['on'])) }}
					</td>
					<td style="height:35%">
						{{$value['name']}}
					</td>

					<td style="height:35%">
						{{$value['pc']}}
					</td>
				</tr>
			</tbody>
		@endforeach
	</table>
</body>
</html>