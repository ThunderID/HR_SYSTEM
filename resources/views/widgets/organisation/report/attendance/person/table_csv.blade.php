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
							@date_indo($value['on'])
						</td>
						<td>
							
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
								@date_indo($value['modified_at'])
							@endif
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>
	@endif
</body>
</html>