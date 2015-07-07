@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ $PersonComposer['widget_data']['personlist']['person-pagination']->total() }}</small>
	<?php
		$PersonComposer['widget_data']['personlist']['person-pagination']->setPath('hr.report.attendances.index');
	 ?>

	 <div class="btn-group pull-right">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-file"></i> Export to <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="#">CSV</a></li>
			<li><a href="#">XLS</a></li>
		</ul>
	</div>
	
	@overwrite

	@section('widget_body')
			@if(isset($PersonComposer['widget_data']['personlist']['person']))
				<div class="table-responsive">
					<table class="table report">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th class="hidden-xs">Jabatan</th>
								<!-- <th>Hak Cuti</th>
								<th>Penambah Cuti</th>
								<th>Pengurang Cuti</th>
								<th>Sisa Cuti</th>
								 -->
								 <th class="hidden-xs">Jumlah Absen (hari)</th>
								<!-- <th>&nbsp;</th> -->
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
										{{$value['position']}} {{$value['department']}} {{$value['branch']}}
									</td>
									<!-- <td>
										{{$value['quotas']}}
									</td>
									<td>
										{{$value['plus_quotas']}}
									</td> -->
									<td class="hidden-xs">
										{{$value['minus_quotas']}}
									</td>
									<!-- <td>
										{{($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas'] >= 0 ? abs($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas']) : 0)}}
									</td>
									<td>
										{{($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas'] < 0 ? abs($value['quotas'] + $value['plus_quotas'] - $value['minus_quotas']) : 0)}}
									</td> -->
									<!-- <td class="text-right">
									</td> -->
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