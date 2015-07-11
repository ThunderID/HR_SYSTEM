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
					<table class="table report">
						<thead>
							<tr>
								<th rowspan="2">No</th>
								<th rowspan="2">Nama</th>
								<th rowspan="2" class="hidden-xs">Jabatan</th>
								<th class="hidden-xs">HB</th>
								<th colspan="4" class="hidden-xs">HC</th>
								<th colspan="8" class="hidden-xs">AS</th>
							</tr>
							<tr>
								<th>HT</th>
								<th>HP</th>
								<th>HD</th>
								<th>HC</th>
								<th>DN</th>
								<th>SS</th>
								<th>SL</th>
								<th>CN</th>
								<th>CB</th>
								<th>CI</th>
								<th>UL</th>
								<th>AS</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="table-responsive div-table-content">
					<table class="table table-hover report">
						@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
							<tbody>
								<tr>
									<td>{{$key+1}}</td>
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
										{{$value['HT']}}
									</td>
									<td class="hidden-xs">
										{{$value['HP']}}
									</td>
									<td class="hidden-xs">
										{{$value['HD']}}
									</td>
									<td class="hidden-xs">
										{{$value['HC']}}
									</td>
									<td class="hidden-xs">
										{{$value['DN']}}
									</td>
									<td class="hidden-xs">
										{{$value['SS']}}
									</td>
									<td class="hidden-xs">
										{{$value['SL']}}
									</td>
									<td class="hidden-xs">
										{{$value['CN']}}
									</td>
									<td class="hidden-xs">
										{{$value['CB']}}
									</td>
									<td class="hidden-xs">
										{{$value['CI']}}
									</td>
									<td class="hidden-xs">
										{{$value['UL']}}
									</td>
									<td class="hidden-xs">
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