@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ $PersonComposer['widget_data']['personlist']['person-pagination']->total() }}</small>
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
				<div class="table-responsive">
					<table class="table report table-bordered table-condensed">
						<thead>
							<tr>
								<th rowspan="2" class="text-center" style="width:4%">No</th>
								<th rowspan="2" class="text-left">Nama</th>
								<th rowspan="2" class="hidden-xs text-left">Jabatan</th>
								<th rowspan="2" class="hidden-xs text-center">HB</th>
								<th colspan="4" class="hidden-xs text-center">HC</th>
								<th colspan="8" class="hidden-xs text-center">AS</th>
							</tr>
							<tr>
								<th class="text-center">HT</th>
								<th class="text-center">HP</th>
								<th class="text-center">HD</th>
								<th class="text-center">HC</th>
								<th class="text-center">DN</th>
								<th class="text-center">SS</th>
								<th class="text-center">SL</th>
								<th class="text-center">CN</th>
								<th class="text-center">CB</th>
								<th class="text-center">CI</th>
								<th class="text-center">UL</th>
								<th class="text-center">AS</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="table-responsive div-table-content">
					<table class="table table-hover report table-condensed">
						@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
							<tbody>
								<tr>
									<td class="text-center font-11" style="width:4%">{{$key+1}}</td>
									<td class="text-left font-11">
										{{$value['name']}}
									</td>
									<td class="text-left hidden-xs font-11">
										@if($value['position']!='')
											{{$value['position']}} {{$value['department']}} {{$value['branch']}}
										@elseif(isset($value['works'][0]))
											{{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}}
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
								</tr>
							</tbody>
						@endforeach
					</table>
				</div>
				</table>

				<div class="row">
					<div class="col-sm-12 text-center mt-10">
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