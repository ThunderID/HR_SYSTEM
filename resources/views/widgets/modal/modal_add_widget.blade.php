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
					<div class="row">
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
								<label for="">Nama</label>
								{!! Form::input('text', '', '', ['class' => 'form-control', 'placeholder' => 'Nama Widget']) !!}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Data</label>
								<select name="data_widget" id="select2-dashboard-widget" class="form-control select2-dashboard-widget">
									<option value="" data-type="list" data-widget="widgets.organisation.person.stat.total_employee" data-query="personlist">
										Daftar Karyawan dengan AS Terbanyak
									</option>
									<option value="" data-type="list" data-widget="list">Daftar Karyawan dengan HB Terbanyak</option>
									<option value="" data-type="list" data-widget="list">Daftar Karyawan dengan HC Terbanyak</option>
									<option value="" data-type="list" data-widget="list">Daftar Karyawan dengan Idle Terbanyak</option>
									<option value="" data-type="list" data-widget="list">Daftar Karyawan dengan SP Terbanyak</option>
									<option value="" data-type="list" data-widget="list" >Daftar Karyawan Terbaik</option>
									<option value="" data-type="list" data-widget="list">Daftar Karyawan yang cuti</option>
									<option value="" data-type="stat" data-widget="list">Jumlah Karyawan dengan AS Terbanyak</option>
									<option value="" data-type="stat" data-widget="list">Jumlah Karyawan dengan Idle Terbanyak</option>
									<option value="" data-type="stat" data-widget="list">Jumlah Karyawan dengan HB Terbanyak</option>
									<option value="" data-type="stat" data-widget="list">Jumlah Karyawan dengan HC Terbanyak</option>
									<option value="" data-type="stat" data-widget="list">Jumlah Karyawan dengan SP Terbanyak</option>
									<option value="" data-type="stat" data-widget="list">Jumlah Karyawan Terbaik</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Periode</label>
								<select name="" id="" class="form-control select2">
									<option value="">Hari ini</option>
									<option value="">1 Hari Sebelum</option>
									<option value="">2 Hari Sebelum</option>
									<option value="">3 Hari Sebelum</option>
									<option value="">4 Hari Sebelum</option>
									<option value="">5 Hari Sebelum</option>
									<option value="">6 Hari Sebelum</option>
									<option value="">Seminggu Sebelum</option>
									<option value="">2 Minggu Sebelum</option>
									<option value="">3 Minggu Sebelum </option>
									<option value="">1 Bulan Sebelum</option>
									<option value="">2 Bulan Sebelum</option>
									<option value="">3 Bulan Sebelum</option>
									<option value="">4 Bulan Sebelum</option>
									<option value="">5 Bulan Sebelum</option>
									<option value="">1 Hari Sesudah</option>
									<option value="">2 Hari Sesudah</option>
									<option value="">3 Hari Sesudah</option>
									<option value="">4 Hari Sesudah</option>
									<option value="">5 Hari Sesudah</option>
									<option value="">6 Hari Sesudah</option>
									<option value="">Seminggu Sesudah</option>
									<option value="">2 Minggu Sesudah</option>
									<option value="">3 Minggu Sesudah </option>
									<option value="">1 Bulan Sesudah</option>
									<option value="">2 Bulan Sesudah</option>
									<option value="">3 Bulan Sesudah</option>
									<option value="">4 Bulan Sesudah</option>
									<option value="">5 Bulan Sesudah</option>
								</select>
							</div>
						</div>
					</div>			
				</div>
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