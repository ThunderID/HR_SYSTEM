<html>
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">Jabatan</th>
					<th rowspan="2" style="text-align:center">HB</th>
					<th colspan="4" style="text-align:center">HC</th>
					<th colspan="8" style="text-align:center">AS</th>
				</tr>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th style="text-align:center">HT</th>
					<th style="text-align:center">HP</th>
					<th style="text-align:center">HD</th>
					<th style="text-align:center">HC</th>
					<th style="text-align:center">DN</th>
					<th style="text-align:center">SS</th>
					<th style="text-align:center">SL</th>
					<th style="text-align:center">CN</th>
					<th style="text-align:center">CB</th>
					<th style="text-align:center">CI</th>
					<th style="text-align:center">UL</th>
					<th style="text-align:center">AS</th>
				</tr>
			</thead>
			@foreach($data as $key => $value)
				<tbody>
					<tr>
						<td>{{$key+1}}</td>
						<td>
							{{$value['name']}}
						</td>
						<td>
							@if($value['position']!='')
								{{$value['position']}} {{$value['department']}} {{$value['branch']}}
							@elseif(isset($value['works'][0]))
								{{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}}
							@endif
						</td>
						<td style="text-align:center">
							{{$value['HB']}}
						</td>
						<td style="text-align:center">
							{{$value['HT']}}
						</td>
						<td style="text-align:center">
							{{$value['HP']}}
						</td>
						<td style="text-align:center">
							{{$value['HD']}}
						</td>
						<td style="text-align:center">
							{{$value['HC']}}
						</td>
						<td style="text-align:center">
							{{$value['DN']}}
						</td>
						<td style="text-align:center">
							{{$value['SS']}}
						</td>
						<td style="text-align:center">
							{{$value['SL']}}
						</td>
						<td style="text-align:center">
							{{$value['CN']}}
						</td>
						<td style="text-align:center">
							{{$value['CB']}}
						</td>
						<td style="text-align:center">
							{{$value['CI']}}
						</td>
						<td style="text-align:center">
							{{$value['UL']}}
						</td>
						<td style="text-align:center">
							{{$value['AS']}}
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

	@endif
</body>
</html>