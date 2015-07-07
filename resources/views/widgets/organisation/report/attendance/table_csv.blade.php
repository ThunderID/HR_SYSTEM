<!DOCTYPE html>
<html lang="en">
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Average Aktif</th>
					<th>Total Idle I</th>
					<th>Total Idle II</th>
					<th>Total Idle III</th>
					<!-- <th>Total Absence</th>
					<th>Possible Total Effective</th> -->
					<th>Time Loss Rate</th>
				</tr>
			</thead>
			@foreach($data as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$key+1}}
						</td>
						<td>
							{{$value['name']}}
						</td>
						<td>
							{{$value['position']}} di departemen {{$value['department']}} cabang {{$value['branch']}}
						</td>
						<td>
							{{floor($value['total_active']/3600)}} Jam<br/>
							{{floor(($value['total_active']%3600)/60)}} Menit</br/> 
							{{floor(($value['total_active']%3600)%60)}} Detik
						</td>
						<td>
							{{floor($value['total_idle_1']/3600)}} Jam<br/>
							{{floor(($value['total_idle_1']%3600)/60)}} Menit</br/> 
							{{floor(($value['total_idle_1']%3600)%60)}} Detik
						</td>
						<td>
							{{floor($value['total_idle_2']/3600)}} Jam<br/>
							{{floor(($value['total_idle_2']%3600)/60)}} Menit</br/> 
							{{floor(($value['total_idle_2']%3600)%60)}} Detik
						</td>
						<td>
							{{floor($value['total_idle_3']/3600)}} Jam<br/>
							{{floor(($value['total_idle_3']%3600)/60)}} Menit</br/> 
							{{floor(($value['total_idle_3']%3600)%60)}} Detik
						</td>
						<!-- <td>
							{{gmdate('H:i:s', $value['total_absence'])}}
						</td>
						<td>
							{{gmdate('H:i:s', $value['possible_total_effective'])}}
						</td> -->
						<td>
							<?php $tlr = ($value['total_absence']!=0 ? $value['total_absence'] : 1) / ($value['possible_total_effective']!=0 ? $value['possible_total_effective'] : 1);?>
							{{round(abs($tlr) * 100, 2)}} %
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>
	@endif
</body>
</html>