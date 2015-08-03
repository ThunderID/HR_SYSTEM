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
					<th colspan="7" style="height:20%">Laporan Aktivitas Per Tanggal {{$start}} s/d {{$end}} Unit Bisnis {{$org['name']}}</th>
				</tr>
				<tr>
					<th colspan="8"></th>
				</tr>
				<tr>
					<th rowspan="2" class="text-center" style="width:10%; height:35%">&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:4%; height:35%">No<br/>&nbsp;</th>
					<th rowspan="2" class="text-left" style="width:40%; height:35%">Nama <br/>(Jabatan)</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Total Aktif <br/>(Hari)</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Total Idle I <br/>(Freq)</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Total Idle II <br/>(Freq)</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Total Idle III <br/>(Freq)</th>
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
						<td class="font-11" style="height:35%">
							{{$value['name']}}<br/>
							@if($value['position']!='')
								({{$value['position']}} {{$value['department']}} {{$value['branch']}})
							@elseif(isset($value['works'][0]))
								({{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}})
							@endif
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							@if($value['position']!='')
								{{floor($value['total_presence']/3600)}} Jam&nbsp;
								{{floor(($value['total_presence']%3600)/60)}} Menit&nbsp; 
								({{$value['total_days']}})
							@else
								Tidak ada aktivitas
							@endif
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							@if($value['position']!='')
								{{floor($value['total_idle_1']/3600)}} Jam&nbsp;
								{{floor(($value['total_idle_1']%3600)/60)}} Menit&nbsp; 
								({{$value['frequency_idle_1']}})
							@else
								Tidak ada aktivitas
							@endif
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							@if($value['position']!='')
								{{floor($value['total_idle_2']/3600)}} Jam&nbsp;
								{{floor(($value['total_idle_2']%3600)/60)}} Menit&nbsp; 
								({{$value['frequency_idle_2']}})
							@else
								Tidak ada aktivitas
							@endif
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							@if($value['position']!='')
								{{floor($value['total_idle_3']/3600)}} Jam&nbsp;
								{{floor(($value['total_idle_3']%3600)/60)}} Menit&nbsp; 
								({{$value['frequency_idle_3']}})
							@else
								Tidak ada aktivitas
							@endif
						</td>
						<!-- <td class="hidden-xs font-11 text-center" style="height:35%">
							@if($value['position']!='')
								<?php $pr = ($value['total_active']!=0 ? $value['total_active'] : 1) / ($value['total_presence']!=0 ? $value['total_presence'] : 1);?>
								{{round(abs($pr) * 100, 2)}} %
							@endif
						</td> -->
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
</body>
</html>