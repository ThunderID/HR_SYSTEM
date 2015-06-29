@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ $PersonComposer['widget_data']['personlist']['person-pagination']->total() }}</small>
	<?php
		$PersonComposer['widget_data']['personlist']['person-pagination']->setPath('hr.report.attendances.index');
	 ?>
	@overwrite

	@section('widget_body')
			@if(isset($PersonComposer['widget_data']['personlist']['person']))
				<table class="table">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama</th>
							<th>Jabatan</th>
							<th>Total Aktif</th>
							<th>Total Idle I</th>
							<th>Total Idle II</th>
							<th>Total Idle III</th>
							<!-- <th>Total Absence</th>
							<th>Possible Total Effective</th> -->
							<th>Time Loss Rate</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
						<tbody>
							<tr>
								<td>
									{{$key+1}}
								</td>
								<td>
									{{$value['name']}}
								</td>
								<td>
									{{$value['position']}} di departemen {{$value['department']}}
								</td>
								<td>
									{{gmdate('H:i:s', $value['total_active'])}}
								</td>
								<td>
									{{gmdate('H:i:s', $value['total_idle_1'])}}
								</td>
								<td>
									{{gmdate('H:i:s', $value['total_idle_2'])}}
								</td>
								<td>
									{{gmdate('H:i:s', $value['total_idle_3'])}}
								</td>
								<!-- <td>
									{{gmdate('H:i:s', $value['total_absence'])}}
								</td>
								<td>
									{{gmdate('H:i:s', $value['possible_total_effective'])}}
								</td> -->
								<td>
									<?php $tlr = ($value['total_absence']!=0 ? $value['total_absence'] : 1) / ($value['possible_total_effective']!=0 ? $value['possible_total_effective'] : 1);?>
									{{round(abs($tlr) * 100, 2)}} %
								</td>
								<td class="text-right">
									<a href="{{route('hr.attendance.persons.index', ['person_id' => $value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
								</td>
							</tr>
						</tbody>
					@endforeach
				</table>

				<div class="row">
					<div class="col-sm-12 text-center">
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