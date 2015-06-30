<html>
<body>
	<table class="table">
		<thead>
			<tr>
				<th>No</th>
				<th>Waktu</th>
				<th>Aktivitas</th>
				<th>PC</th>
			</tr>
		</thead>
		@foreach($data['logs'] as $key => $value)
			<tbody>
				<tr>
					<td>
						{{$key+1}}
					</td>
					<td>
						@date_indo($value['on'])
						@time_indo($value['on'])
					</td>
					<td>
						{{$value['name']}}
					</td>

					<td>
						{{$value['pc']}}
					</td>
				</tr>
			</tbody>
		@endforeach
	</table>
</body>
</html>