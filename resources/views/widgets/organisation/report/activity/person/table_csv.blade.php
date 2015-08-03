<html>
<body>
	@if(isset($data['processlogs']))
		<table class="table">
			<thead>
				<tr>
					<th colspan="8"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th colspan="7" style="height:20%">Laporan Aktivitas {{$data['name']}} Per Tanggal {{$start}} s/d {{$end}} Unit Bisnis {{$org['name']}}</th>
				</tr>
				<tr>
					<th colspan="8"></th>
				</tr>
				<tr>
					<th rowspan="2" class="text-center" style="width:10%; height:35%">&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:4%; height:35%">No<br/>&nbsp;</th>
					<th rowspan="2" class="text-left" style="width:20%; height:35%">Tanggal<br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Total Aktif<br/>&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Total Idle I <br/>(Freq)</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Total Idle II <br/>(Freq)</th>
					<th rowspan="2" class="text-center" style="width:20%; height:35%">Total Idle III <br/>(Freq)</th>
					<!-- <th rowspan="2" class="text-center" style="width:20%; height:35%">Performance Rate<br/>&nbsp;</th> -->
				</tr>
				<tr></tr>
			</thead>
			<tbody>
				@foreach($data['processlogs'] as $key => $value)
					<tr>
						<td style="width:10%; height:35%">&nbsp;</td>
						<td class="font-11" style="height:35%">
							{{$key+1}}
						</td>
						<td class="font-11" style="height:35%">
							{{ date('d-m-Y', strtotime($value['on'])) }}
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{floor(($value['total_active']+$value['total_sleep']+$value['total_idle'])/3600)}} Jam&nbsp;
							{{floor((($value['total_active']+$value['total_sleep']+$value['total_idle'])%3600)/60)}} Menit&nbsp; 
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{floor($value['total_idle_1']/3600)}} Jam&nbsp;
							{{floor(($value['total_idle_1']%3600)/60)}} Menit&nbsp; 
							({{$value['frequency_idle_1']}})
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{floor($value['total_idle_2']/3600)}} Jam&nbsp;
							{{floor(($value['total_idle_2']%3600)/60)}} Menit&nbsp; 
							({{$value['frequency_idle_2']}})
						</td>
						<td class="hidden-xs font-11 text-center" style="height:35%">
							{{floor($value['total_idle_3']/3600)}} Jam&nbsp;
							{{floor(($value['total_idle_3']%3600)/60)}} Menit&nbsp; 
							({{$value['frequency_idle_3']}})
						</td>
						<!-- <td class="hidden-xs font-11 text-center" style="height:35%">
							<?php $pr = ($value['total_active']!=0 ? $value['total_active'] : 1) / (($value['total_active']+$value['total_sleep']+$value['total_idle'])!=0 ? ($value['total_active']+$value['total_sleep']+$value['total_idle']) : 1);?>
							{{round(abs($pr) * 100, 2)}} %
						</td> -->
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
</body>
</html>