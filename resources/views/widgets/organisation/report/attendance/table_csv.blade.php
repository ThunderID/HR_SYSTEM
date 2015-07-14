<html>
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th colspan="18"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th colspan="17" style="height:20%">Laporan Kehadiran Per Tanggal {{$start}} - {{$end}} Unit Bisnis {{$org['name']}}</th>
				</tr>
				<tr>
					<th colspan="18"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th rowspan="2" class="text-center" style="width:4%; height:25%">No<br/>&nbsp;</th>
					<th rowspan="2" class="text-left" style="width:30%; height:25%">Nama <br/>(Jabatan)</th>
					<th rowspan="2" class="hidden-xs text-center" style="text-align:center; width:4%; height:25%">HB<br/>&nbsp;</th>
					<th colspan="4" class="hidden-xs text-center" style="text-align:center; width:16%; height:25%">HC</th>
					<th colspan="8" class="hidden-xs text-center" style="text-align:center; width:32%; height:25%">AS</th>
					<th rowspan="2" class="hidden-xs text-center" style="width:6%; height:25%">Total<br/>&nbsp;</th>
					<th rowspan="2" class="hidden-xs text-center" style="width:8%; height:25%">Time<br/>&nbsp;Loss<br/>&nbsp;Rate</th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th></th>
					<th></th>
					<th></th>
					<th style="text-align:center; width:4%; height:25%">HT</th>
					<th style="text-align:center; width:4%; height:25%">HP</th>
					<th style="text-align:center; width:4%; height:25%">HD</th>
					<th style="text-align:center; width:4%; height:25%">HC</th>
					<th style="text-align:center; width:4%; height:25%">DN</th>
					<th style="text-align:center; width:4%; height:25%">SS</th>
					<th style="text-align:center; width:4%; height:25%">SL</th>
					<th style="text-align:center; width:4%; height:25%">CN</th>
					<th style="text-align:center; width:4%; height:25%">CB</th>
					<th style="text-align:center; width:4%; height:25%">CI</th>
					<th style="text-align:center; width:4%; height:25%">UL</th>
					<th style="text-align:center; width:4%; height:25%">AS</th>
				</tr>
			</thead>
			@foreach($data as $key => $value)
				<tbody>
					<tr>
						<td style="width:10%">&nbsp;</td>
						<td style="text-align:center; width:4%; height:35%">{{$key+1}}</td>
						<td style="text-align:center; width:30%; height:35%">
							{{$value['name']}}<br/>&nbsp;
							@if($value['position']!='')
								({{$value['position']}} {{$value['department']}} {{$value['branch']}})
							@elseif(isset($value['works'][0]))
								({{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}})
							@endif
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['HB']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['HT']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['HP']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['HD']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['HC']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['DN']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['SS']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['SL']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['CN']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['CB']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['CI']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['UL']}}
						</td>
						<td style="text-align:center; width:4%; height:35%">
							{{$value['AS']}}
						</td>
						<td style="text-align:center; width:6%; height:35%">
							{{$value['HB']+$value['HT']+$value['HP']+$value['HD']+$value['HC']+$value['DN']+$value['SS']+$value['SL']+$value['CN']+$value['CB']+$value['CI']+$value['UL']+$value['AS']}}
						</td>
						<td style="text-align:center; width:8%; height:35%">
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