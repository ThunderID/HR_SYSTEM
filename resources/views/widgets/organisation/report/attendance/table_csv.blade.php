<html>
<body>
	@if(isset($data))
		<table class="table">
			<thead>
				<tr>
					<th colspan="8"></th>
				</tr>
				<tr>
					<th style="width:10%">&nbsp;</th>
					<th colspan="7" style="height:20%">Laporan Kehadiran Per Tanggal {{$start}} s/d {{$end}} Unit Bisnis {{$org['name']}}</th>
				</tr>
				<tr>
					<th rowspan="2" style="width:10%; height:35%">&nbsp;</th>
					<th rowspan="2" style="width:10%; height:35%">No<br/>&nbsp;</th>
					<th rowspan="2" style="width:10%; height:35%">Nama <br>(Jabatan)</th>					
					<th rowspan="2" style="width:10%; height:35%; text-align:center">HB<br>&nbsp;</th>
					<th colspan="4" style="width:10%; height:35%;text-align:center">HC<br>&nbsp;</th>
					<th colspan="8" style="width:10%; height:35%;text-align:center">AS<br>&nbsp;</th>
					<th rowspan="2" style="width:10%; height:35%;text-align:center">Total<br>&nbsp;</th>
					<th rowspan="2" style="width:10%; height:35%;text-align:center">Time Loss Rate</th>
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
			<tbody>
				@foreach($data as $key => $value)
					<tr>
						<td style="width:10%">&nbsp;</td>
						<td style="height:35%">{{$key+1}}</td>
						<td style="height:35%">
							{{$value['name']}}
							<br/>
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
						<td style="text-align:center">
							{{$value['HB']+$value['HT']+$value['HP']+$value['HD']+$value['HC']+$value['DN']+$value['SS']+$value['SL']+$value['CN']+$value['CB']+$value['CI']+$value['UL']+$value['AS']}}
						</td>
						<td style="text-align:center">
							@if($value['position']!='')
								<?php $tlr = ($value['total_absence']!=0 ? $value['total_absence'] : 1) / ($value['possible_total_effective']!=0 ? $value['possible_total_effective'] : 1);?>
								{{round(abs($tlr) * 100, 2)}} %
							@else
								100 %
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

	@endif
</body>
</html>