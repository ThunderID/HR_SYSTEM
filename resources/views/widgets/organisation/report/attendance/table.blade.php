@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ count($PersonComposer['widget_data']['personlist']['person']) }}</small>
	<?php
		$PersonComposer['widget_data']['personlist']['person-pagination']->setPath('hr.report.activities.index');
	 ?>

	 <div class="btn-group pull-right">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-file"></i> Export to <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="{{ route('hr.report.attendances.index', array_merge(Input::all(), ['print' => 'yes', 'mode' => 'csv'])) }}">CSV</a></li>
			<li><a href="{{ route('hr.report.attendances.index', array_merge(Input::all(), ['print' => 'yes', 'mode' => 'xls'])) }}">XLS</a></li>
		</ul>
	</div>
	
	@overwrite

	@section('widget_body')
			@if(isset($PersonComposer['widget_data']['personlist']['person']))
					<table class="table report table-bordered table-condensed">
						<thead>
							<tr>
								<th rowspan="2" class="text-center" style="width:4%">No<br/>&nbsp;</th>
								<th rowspan="2" class="text-left">Nama <br/>(Jabatan)</th>
								<th rowspan="2" class="hidden-xs text-center" style="width:4%">HB<br/>&nbsp;</th>
								<th colspan="4" class="hidden-xs text-center" style="width:16%">HC</th>
								<th colspan="8" class="hidden-xs text-center" style="width:32%">AS</th>
								<th rowspan="2" class="hidden-xs text-center" style="width:6%">Total<br/>&nbsp;</th>
								<th rowspan="2" class="hidden-xs text-center" style="width:8%">Time Loss Rate<br/>&nbsp;</th>
								<th rowspan="2" class="hidden-xs text-center" style="width:8%">&nbsp;</th>
							</tr>
							<tr>
								<th class="text-center" style="width:4%">HT</th>
								<th class="text-center" style="width:4%">HP</th>
								<th class="text-center" style="width:4%">HD</th>
								<th class="text-center" style="width:4%">HC</th>
								<th class="text-center" style="width:4%">DN</th>
								<th class="text-center" style="width:4%">SS</th>
								<th class="text-center" style="width:4%">SL</th>
								<th class="text-center" style="width:4%">CN</th>
								<th class="text-center" style="width:4%">CB</th>
								<th class="text-center" style="width:4%">CI</th>
								<th class="text-center" style="width:4%">UL</th>
								<th class="text-center" style="width:4%">AS</th>
							</tr>
						</thead>
						@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
							<tbody>
								<tr>
									<td class="text-center font-11" style="width:4%">{{$key+1}}</td>
									<td class="text-left font-11">
										{{$value['name']}}
										<br/>
										@if($value['position']!='')
											({{$value['position']}} {{$value['department']}} {{$value['branch']}})
										@elseif(isset($value['works'][0]))
											({{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}})
										@endif
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['HB']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['HT']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['HP']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['HD']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['HC']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['DN']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['SS']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['SL']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['CN']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['CB']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['CI']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['UL']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['AS']}}
									</td>
									<td class="text-center hidden-xs font-11">
										{{$value['HB']+$value['HT']+$value['HP']+$value['HD']+$value['HC']+$value['DN']+$value['SS']+$value['SL']+$value['CN']+$value['CB']+$value['CI']+$value['UL']+$value['AS']}}
									</td>
									<td class="text-center hidden-xs font-11">
										@if($value['position']!='')
											<?php $tlr = ($value['total_absence']!=0 ? $value['total_absence'] : 1) / ($value['possible_total_effective']!=0 ? $value['possible_total_effective'] : 1);?>
											{{round(abs($tlr) * 100, 2)}} %
										@else
											100 %
										@endif
									</td>
									<td class="text-right font-11">
										@if($value['position']!='')
											<a href="{{route('hr.report.attendances.show', ['person_id' => $value['id'], 'org_id' => $data['id'], 'start' => $start, 'end' => $end])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
										@endif
									</td>
								</tr>
							</tbody>
						@endforeach
					</table>

				<div class="row">
					<div class="col-sm-12 text-center mt-11">
						<p>Menampilkan {!! $PersonComposer['widget_data']['personlist']['person-display']['from']!!} - {!! $PersonComposer['widget_data']['personlist']['person-display']['to']!!}</p>
						{!! $PersonComposer['widget_data']['personlist']['person-pagination']->appends(Input::all())->render()!!}
					</div>
				</div>

				<div class="clearfix">&nbsp;</div>
			@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif