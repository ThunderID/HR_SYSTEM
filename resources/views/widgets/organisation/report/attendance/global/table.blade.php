@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ count($PersonComposer['widget_data']['personlist']['person']) }}</small>
	<?php
		$PersonComposer['widget_data']['personlist']['person-pagination']->setPath('hr.report.attendances.index');
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
			
				<table class="table-bordered table-affix overflow-y">
					<thead>
						<tr>
							<th class="text-center font-12">No<br/>&nbsp;</th>
							<th class="text-left font-12 mr-30 pr-30" style="width:20em;">Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br/>(Jabatan)</th>
							<th class="text-center font-12 pl-20 pr-20" style="width:20em">Tanggal</th>
							<th class="text-center font-12" style="width:20em">Jam Masuk <br> (Jadwal)</th>
							<th class="text-center font-12" style="width:20em">Jam Keluar <br> (Jadwal)</th>
							<th class="text-center font-12" style="width:20em">Status Terakhir</th>
						</tr>
					</thead>
					<tbody>
					
						@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
							<tr>
								<td @if(count($value['processlogs'])!=0) rowspan="{{count($value['processlogs'])}}" @endif class="text-center font-12">{{$key+1}}</td>
								<td @if(count($value['processlogs'])!=0) rowspan="{{count($value['processlogs'])}}" @endif class="text-left font-12" style="width:20em">
									{{$value['name']}}
								</td>
								@if(isset($value['processlogs'][0]))
									<td class="text-center font-12">
										{{ date('d-m-Y', strtotime($value['processlogs'][0]['on'])) }}
									</td>
									<td class="font-11 text-center">
										{{ date('H:i:s', strtotime($value['processlogs'][0]['start'])) }}
										({{ date('H:i:s', strtotime($value['processlogs'][0]['schedule_start'])) }})
									</td>
									<td class="font-11 text-center">
										{{ date('H:i:s', strtotime($value['processlogs'][0]['end'])) }}
										({{ date('H:i:s', strtotime($value['processlogs'][0]['schedule_end'])) }})
									</td>
									<td class="font-11 text-center">
										@if(isset($value['processlogs'][0]['attendancelogs'][0]))
											{{($value['processlogs'][0]['attendancelogs'][0]['modified_status']!='' ? $value['processlogs'][0]['attendancelogs'][0]['modified_status'] : $value['processlogs'][0]['attendancelogs'][0]['actual_status'])}}
										@endif
									</td>
								@else
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								@endif
							</tr>
							@foreach($value['processlogs'] as $key2 => $value2)
								@if($key2!=0)
								<tr>
									<td class="text-center font-12">
										{{ date('d-m-Y', strtotime($value2['on'])) }}
									</td>
									<td class="font-11 text-center">
										{{ date('H:i:s', strtotime($value2['start'])) }}
										({{ date('H:i:s', strtotime($value2['schedule_start'])) }})
									</td>
									<td class="font-11 text-center">
										{{ date('H:i:s', strtotime($value2['end'])) }}
										({{ date('H:i:s', strtotime($value2['schedule_end'])) }})
									</td>
									<td class="font-11 text-center">
										@if(isset($value2['attendancelogs'][0]))
											{{($value2['attendancelogs'][0]['modified_status']!='' ? $value2['attendancelogs'][0]['modified_status'] : $value2['attendancelogs'][0]['actual_status'])}}
										@endif
									</td>
								</tr>
								@endif
							@endforeach
						@endforeach
					</tbody>
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