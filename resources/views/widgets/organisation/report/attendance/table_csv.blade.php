<!DOCTYPE html>
<html lang="en">
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th class="col-sm-1">No</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Average Aktif</th>
					<th>Total Idle I / Freq</th>
					<th>Total Idle II / Freq</th>
					<th>Total Idle III / Freq</th>
					<!-- <th>Total Absence</th>
					<th>Possible Total Effective</th> -->
					<th>Time Loss Rate</th>
					<!-- <th>Total Absence</th>
					<th>Possible Total Effective</th> -->
					<th>Time Loss Rate</th>
				</tr>
			</thead>
			@foreach($data as $key => $value)
				<tbody>
					<tr class="font-11">
						<td class="col-sm-1">
							{{$key+1}}
						</td>
						<td>
							{{$value['name']}}
						</td>
						<td class="hidden-xs">
							@if($value['position']!='')
								{{$value['position']}} {{$value['department']}} {{$value['branch']}}
							@elseif(isset($value['works'][0]))
								{{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}}
							@endif
						</td>
						<td class="hidden-xs">
							@if($value['position']!='')
								{{floor($value['total_active']/3600)}} Jam<br/>
								{{floor(($value['total_active']%3600)/60)}} Menit</br/> 
								{{floor(($value['total_active']%3600)%60)}} Detik
							@else
								Tidak ada aktivitas
							@endif
						</td>
						<td class="hidden-xs">
							@if($value['position']!='')
								{{floor($value['total_idle_1']/3600)}} Jam<br/>
								{{floor(($value['total_idle_1']%3600)/60)}} Menit</br/> 
								{{floor(($value['total_idle_1']%3600)%60)}} Detik
								/ {{$value['frequency_idle_1']}}
							@else
								Tidak ada aktivitas
							@endif
						</td>
						<td class="hidden-xs">
							@if($value['position']!='')
								{{floor($value['total_idle_2']/3600)}} Jam<br/>
								{{floor(($value['total_idle_2']%3600)/60)}} Menit</br/> 
								{{floor(($value['total_idle_2']%3600)%60)}} Detik
								/ {{$value['frequency_idle_2']}}
							@else
								Tidak ada aktivitas
							@endif
						</td>
						<td class="hidden-xs">
							@if($value['position']!='')
								{{floor($value['total_idle_3']/3600)}} Jam<br/>
								{{floor(($value['total_idle_3']%3600)/60)}} Menit</br/> 
								{{floor(($value['total_idle_3']%3600)%60)}} Detik
								/ {{$value['frequency_idle_3']}}
							@else
								Tidak ada aktivitas
							@endif
						</td>
						<!-- <td>
							{{gmdate('H:i:s', $value['total_absence'])}}
						</td>
						<td>
							{{gmdate('H:i:s', $value['possible_total_effective'])}}
						</td> -->
						<td class="hidden-xs">
							@if($value['position']!='')
								<?php $tlr = ($value['total_absence']!=0 ? $value['total_absence'] : 1) / ($value['possible_total_effective']!=0 ? $value['possible_total_effective'] : 1);?>
								{{round(abs($tlr) * 100, 2)}} %
							@else
								100 %
							@endif
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>
	@endif
</body>
</html>