<!DOCTYPE html>
<html lang="en">
<body>
	@if(isset($data))
		<table class="table">				
			<thead>
				<tr>
					<th colspan="8"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th colspan="7" style="height:20%">HRIS - DATA KARYAWAN UNIT BISNIS {{$org['name']}}</th>
				</tr>
				<tr>
					<th colspan="8"></th>
				</tr>
				<tr>
					<th rowspan="2" class="text-center" style="width:10%; height:35%">&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:4%; height:35%">No</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">NIK</th>
					<th rowspan="2" class="text-left" style="width:40%; height:35%">Nama</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Username HRIS</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Jabatan</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Status</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Tanggal Akhir Status</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Policy Absen</th>
					<!-- <th rowspan="2" class="text-center" style="width:20%; height:35%">Performance Rate</th> -->
				</tr>
				<tr></tr>
			</thead>
			<tbody>
				@foreach($data as $key => $value)
					<tr>
						<td style="width:10%">&nbsp;</td>
						<td class="font-11" style="height:35%">
							{{$key+1}}
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{$value['uniqid']}}
						</td>
						<td class="font-11" style="height:35%">
							{{$value['name']}}
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{$value['username']}}
						</td>
						<?php 
						$position = '';
						$start = date('Y-m-d H:i:s', strtotime('first day of January 2000'));
						$end = 'Sekarang';
						$is_absence = true;
						?>
						
						@foreach($value['works'] as $key2 => $value2)
							@if($start > $value2['start'])
							<?php
								$position = $value2['name'].' '.$value2['tag'].' '.$value2['branch']['name'];	
								$start = $value2['start'];	
								$end = $value2['end'];	
								$is_absence = $value2['is_absence'];	
							?>
							@endif
						@endforeach
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{$position}}
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{$start}}
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{$end}}
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							@if($is_absence) n @else y @endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
</body>
</html>