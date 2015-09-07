@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_body')
	<div class="modal fade {{ isset($class_id) ? $class_id : '' }}" id="{{ isset($class_id) ? $class_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="Import CSV" aria-hidden="true">
		<div class="modal-dialog form">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-xl " id="formModalLabel">Tambah Widget</h4>
				</div>
				<div class="modal-body" style="background-color:#f5f5f5">
					<div class="row mb-20">
						<div class="col-lg-12">
							<h4 class="text-primary">Petunjuk</h4>
							<article class="margin-bottom-xxl">
								<p class="opacity-75">
									Silahkan menambah widget sesuai pilihan anda.
								</p>
							</article>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Title</label>
								{!! Form::input('text', 'title', '', ['class' => 'form-control', 'placeholder' => 'Title Widget']) !!}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Data</label>
								<select name="data_widget" id="select2-dashboard-widget" class="form-control select2-dashboard-widget">
									<option value="ondate" data-composer="ProcessLogComposer" data-type="table" data-widget-option="personlist">
										SP Print list
									</option>
									<option value="processlogondate" data-composer="IdleLogComposer" data-type="table" data-widget="list" data-widget-option="personlist">
										Idle Terbanyak
									</option>
									<option value="totalprocesslogondate" data-composer="IdleLogComposer" data-type="stat" >
										Total Idle
									</option>
									<option value="processlogondate" data-composer="AttendanceLogComposer" data-type="stat">
										Total Karyawan Status AS Terbanyak
									</option>
									<option value="processlogondate" data-composer="AttendanceLogComposer" data-type="stat">
										Total Karyawan Status HB Terbanyak
									</option>
									<option value="processlogondate" data-composer="AttendanceLogComposer" data-type="stat">
										Total Karyawan Status HC Terbanyak
									</option>
									<option data-composer="PersonWorkleaveComposer" data-type="table">
										Karyawan Yang Sedang Cuti
									</option>
									<option data-composer="PersonWorkleaveComposer" data-type="stat">
										Total Karyawan Yang Sedang Cuti
									</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Periode</label>
								<select name="periode" class="form-control select2">
									<option value="today">Hari Ini</option>
									<option value="this week">Minggu Ini</option>
									<option value="this month">Bulan Ini</option>
									<option value="this year">Tahun Ini</option>
									<option value="tomorrow">1 Hari Sebelum</option>
									<option value="- 2 days">2 Hari Sebelum</option>
									<option value="- 3 days">3 Hari Sebelum</option>
									<option value="- 4 days">4 Hari Sebelum</option>
									<option value="- 5 days">5 Hari Sebelum</option>
									<option value="- 6 days">6 Hari Sebelum</option>
									<option value="- a week">Seminggu Sebelum</option>
									<option value="- 2 weeks">2 Minggu Sebelum</option>
									<option value="- 3 weeks">3 Minggu Sebelum </option>
									<option value="- a month">1 Bulan Sebelum</option>
									<option value="- 2 months">2 Bulan Sebelum</option>
									<option value="- 3 months">3 Bulan Sebelum</option>
									<option value="- 4 months">4 Bulan Sebelum</option>
									<option value="- 5 months">5 Bulan Sebelum</option>
									<option value="yesterday">1 Hari Sesudah</option>
									<option value="+ 2 days">2 Hari Sesudah</option>
									<option value="+ 3 days">3 Hari Sesudah</option>
									<option value="+ 4 days">4 Hari Sesudah</option>
									<option value="+ 5 days">5 Hari Sesudah</option>
									<option value="+ 6 days">6 Hari Sesudah</option>
									<option value="+ a week">Seminggu Sesudah</option>
									<option value="+ 2 weeks">2 Minggu Sesudah</option>
									<option value="+ 3 weeks">3 Minggu Sesudah </option>
									<option value="+ a month">1 Bulan Sesudah</option>
									<option value="+ 2 months">2 Bulan Sesudah</option>
									<option value="+ 3 months">3 Bulan Sesudah</option>
									<option value="+ 4 months">4 Bulan Sesudah</option>
									<option value="+ 5 months">5 Bulan Sesudah</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Urutkan</label>
								<select name="sort" id="">
									<option value="asc">ASC</option>
									<option value="desc">DESC</option>
								</select>
							</div>
						</div>
					</div>			
				</div>
				{!! Form::hidden('org_id', '', ['class' => 'hid_org_id']) !!}
				{!! Form::hidden('dashboard', 'orgasisation') !!}
				{!! Form::hidden('type', '', ['class' => 'type_widget']) !!}
				<div class="modal-footer bg-grey">
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary">Tambah</button>
				</div>
			</div>
		</div>
	</div>
@overwrite