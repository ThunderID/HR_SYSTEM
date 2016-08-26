@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Kehadiran' !!} </h1>
	<small>Total data {{ count($PersonComposer['widget_data']['personlist']['person']) }}</small>
	<?php
		$PersonComposer['widget_data']['personlist']['person-pagination']->setPath(route('hr.report.attendances.index'));
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
							<th class="text-left font-12 mr-30 pr-30" style="width:20em;">Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
							<th class="text-left font-12 mr-30 pr-30" style="width:20em;">NIK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
							<th class="text-center font-12 pl-20 pr-20" style="width:20em">Tanggal</th>
							<th class="text-center font-12" style="width:20em">Jam Masuk <br> Jadwal</th>
							<th class="text-center font-12" style="width:20em">Jam Keluar <br> Jadwal</th>
							<th class="text-center font-12" style="width:20em">Jam Datang <br> Karyawan</th>
							<th class="text-center font-12" style="width:20em">Jam Pulang <br> Karyawan</th>
							<th class="text-center font-12" style="width:20em">Status Terakhir</th>
						</tr>
					</thead>
					<tbody>
					
						<?php $i = $PersonComposer['widget_data']['personlist']['person-display']['from'];?>
						@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
							<tr>
								<td @if(count($value['processlogs'])!=0) rowspan="{{count($value['processlogs'])}}" @endif class="text-center font-12">{{$i}}</td>
								<td @if(count($value['processlogs'])!=0) rowspan="{{count($value['processlogs'])}}" @endif class="text-left font-12" style="width:20em">
									{{$value['name']}}
								</td>
								<td @if(count($value['processlogs'])!=0) rowspan="{{count($value['processlogs'])}}" @endif class="text-left font-12" style="width:20em">
									{{$value['uniqid']}}
								</td>
								@if(isset($value['processlogs'][0]))
									<td class="text-center font-12">
										{{ date('d-m-Y', strtotime($value['processlogs'][0]['on'])) }}
									</td>
									<td class="font-11 text-center">
										{{ date('H:i:s', strtotime($value['processlogs'][0]['schedule_start'])) }}
									</td>
									<td class="font-11 text-center">
										{{ date('H:i:s', strtotime($value['processlogs'][0]['schedule_end'])) }}
									</td>
									<td class="font-11 text-center">
										@if (strtotime($value['processlogs'][0]['fp_start']) > strtotime($value['processlogs'][0]['start']))
											{{ date('H:i:s', strtotime($value['processlogs'][0]['fp_start'])) }}
										@else
											{{ date('H:i:s', strtotime($value['processlogs'][0]['start'])) }}
										@endif
									</td>
									<td class="font-11 text-center">
										@if (strtotime($value['processlogs'][0]['fp_end']) > strtotime($value['processlogs'][0]['end']))
											{{ date('H:i:s', strtotime($value['processlogs'][0]['fp_end'])) }}
										@else
											{{ date('H:i:s', strtotime($value['processlogs'][0]['end'])) }}
										@endif
									</td>
									<td class="font-11 text-center">
										@if(isset($value['processlogs'][0]['attendancelogs'][0]))
											<a href="javascript:;" class="black cursor-text tipped-tooltip" title="
												@if ($value2['attendancelogs'][0]['modified_status']!='')
													@if ($value2['attendancelogs'][0]['modified_status']=='AS')
														Ketidakhadiran Tanpa Penjelasan
													@elseif ($value2['attendancelogs'][0]['modified_status']=='CB')
														Cuti Bersama
													@elseif ($value2['attendancelogs'][0]['modified_status']=='CI')
														Cuti Istimewa
													@elseif ($value2['attendancelogs'][0]['modified_status']=='CN')
														Cuti Untuk Keperluan Pribadi
													@elseif ($value2['attendancelogs'][0]['modified_status']=='DN')
														Keperluan Dinas
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HB')
														Hadir dan Pulang tepat waktu
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HC')
														Hadir Cacat Tanpa Penjelasan
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HD')
														Hadir Cacat Dengan Ijin Dinas
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HP')
														Hadir Cacat Dengan Ijin Pulang Cepat
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HT')
														Hadir Cacat Dengan Ijin Datang Terlambat
													@elseif ($value2['attendancelogs'][0]['modified_status']=='SS')
														Sakit Jangka Pendek
													@elseif ($value2['attendancelogs'][0]['modified_status']=='SL')
														Sakit Berkepanjangan
													@elseif ($value2['attendancelogs'][0]['modified_status']=='UL')
														Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia
													@endif
												@else
													@if ($value2['attendancelogs'][0]['actual_status']=='AS')
														Ketidakhadiran Tanpa Penjelasan
													@elseif ($value2['attendancelogs'][0]['actual_status']=='CB')
														Cuti Bersama
													@elseif ($value2['attendancelogs'][0]['actual_status']=='CI')
														Cuti Istimewa
													@elseif ($value2['attendancelogs'][0]['actual_status']=='CN')
														Cuti Untuk Keperluan Pribadi
													@elseif ($value2['attendancelogs'][0]['actual_status']=='DN')
														Keperluan Dinas
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HB')
														Hadir dan Pulang tepat waktu
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HC')
														Hadir Cacat Tanpa Penjelasan
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HD')
														Hadir Cacat Dengan Ijin Dinas
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HP')
														Hadir Cacat Dengan Ijin Pulang Cepat
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HT')
														Hadir Cacat Dengan Ijin Datang Terlambat
													@elseif ($value2['attendancelogs'][0]['actual_status']=='SS')
														Sakit Jangka Pendek
													@elseif ($value2['attendancelogs'][0]['actual_status']=='SL')
														Sakit Berkepanjangan
													@elseif ($value2['attendancelogs'][0]['actual_status']=='UL')
														Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia
													@endif
												@endif
											">
												{{($value['processlogs'][0]['attendancelogs'][0]['modified_status']!='' ? $value['processlogs'][0]['attendancelogs'][0]['modified_status'] : $value['processlogs'][0]['attendancelogs'][0]['actual_status'])}}
											</a>
										@endif
									</td>
								@else
									<td class="text-center">&#8211;</td>
									<td class="text-center">&#8211;</td>
									<td class="text-center">&#8211;</td>
									<td class="text-center">&#8211;</td>
									<td class="text-center">&#8211;</td>
									<td class="text-center">&#8211;</td>
								@endif
							</tr>
							@foreach($value['processlogs'] as $key2 => $value2)
								@if($key2!=0)
								<tr>
									<td class="text-center font-12">
										{{ date('d-m-Y', strtotime($value2['on'])) }}
									</td>
									<td class="font-11 text-center">	
										{{ date('H:i:s', strtotime($value2['schedule_start'])) }}
									</td>
									<td class="font-11 text-center">
										{{ date('H:i:s', strtotime($value2['schedule_end'])) }}
									</td>
									<td class="font-11 text-center">
										@if (strtotime($value2['fp_start']) > strtotime($value2['start']))
											{{ date('H:i:s', strtotime($value2['fp_start'])) }}
										@else
											{{ date('H:i:s', strtotime($value2['start'])) }}
										@endif
									</td>
									<td class="font-11 text-center">
										@if (strtotime($value2['fp_end']) > strtotime($value2['end']))
											{{ date('H:i:s', strtotime($value2['fp_end'])) }}
										@else
											{{ date('H:i:s', strtotime($value2['end'])) }}
										@endif
									</td>
									<td class="font-11 text-center">
										@if(isset($value2['attendancelogs'][0]))
											<a href="javascript:;" class="black cursor-text tipped-tooltip" title="
												@if ($value2['attendancelogs'][0]['modified_status']!='')
													@if ($value2['attendancelogs'][0]['modified_status']=='AS')
														Ketidakhadiran Tanpa Penjelasan
													@elseif ($value2['attendancelogs'][0]['modified_status']=='CB')
														Cuti Bersama
													@elseif ($value2['attendancelogs'][0]['modified_status']=='CI')
														Cuti Istimewa
													@elseif ($value2['attendancelogs'][0]['modified_status']=='CN')
														Cuti Untuk Keperluan Pribadi
													@elseif ($value2['attendancelogs'][0]['modified_status']=='DN')
														Keperluan Dinas
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HB')
														Hadir dan Pulang tepat waktu
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HC')
														Hadir Cacat Tanpa Penjelasan
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HD')
														Hadir Cacat Dengan Ijin Dinas
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HP')
														Hadir Cacat Dengan Ijin Pulang Cepat
													@elseif ($value2['attendancelogs'][0]['modified_status']=='HT')
														Hadir Cacat Dengan Ijin Datang Terlambat
													@elseif ($value2['attendancelogs'][0]['modified_status']=='SS')
														Sakit Jangka Pendek
													@elseif ($value2['attendancelogs'][0]['modified_status']=='SL')
														Sakit Berkepanjangan
													@elseif ($value2['attendancelogs'][0]['modified_status']=='UL')
														Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia
													@endif
												@else
													@if ($value2['attendancelogs'][0]['actual_status']=='AS')
														Ketidakhadiran Tanpa Penjelasan
													@elseif ($value2['attendancelogs'][0]['actual_status']=='CB')
														Cuti Bersama
													@elseif ($value2['attendancelogs'][0]['actual_status']=='CI')
														Cuti Istimewa
													@elseif ($value2['attendancelogs'][0]['actual_status']=='CN')
														Cuti Untuk Keperluan Pribadi
													@elseif ($value2['attendancelogs'][0]['actual_status']=='DN')
														Keperluan Dinas
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HB')
														Hadir dan Pulang tepat waktu
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HC')
														Hadir Cacat Tanpa Penjelasan
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HD')
														Hadir Cacat Dengan Ijin Dinas
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HP')
														Hadir Cacat Dengan Ijin Pulang Cepat
													@elseif ($value2['attendancelogs'][0]['actual_status']=='HT')
														Hadir Cacat Dengan Ijin Datang Terlambat
													@elseif ($value2['attendancelogs'][0]['actual_status']=='SS')
														Sakit Jangka Pendek
													@elseif ($value2['attendancelogs'][0]['actual_status']=='SL')
														Sakit Berkepanjangan
													@elseif ($value2['attendancelogs'][0]['actual_status']=='UL')
														Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia
													@endif
												@endif
											">
												{{($value2['attendancelogs'][0]['modified_status']!='' ? $value2['attendancelogs'][0]['modified_status'] : $value2['attendancelogs'][0]['actual_status'])}}
											</a>
										@endif
									</td>
								</tr>
								@endif
							@endforeach
							<?php $i++;?>
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