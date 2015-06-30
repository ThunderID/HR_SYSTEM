@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Status Log ' : 'Ubah Status Log '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ProcessLogComposer['widget_data']['processlogslist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Modifikasi Status</label>
				</div>	
				<div class="col-md-10">
					<select name="modified_status" class="form-control select2">
						<option value="SS">SS - Sakit Jangka Pendek</option>
						<option value="SL">SL - Sakit Berkepanjangan</option>
						<option value="HT">HT - Hadir Cacat Dengan Ijin Datang Terlambat</option>
						<option value="HP">HP - Hadir Cacat Dengan Ijin Pulang Cepat</option>
						<option value="HD">HD - Hadir Cacat Dengan Ijin Dinas</option>
						<option value="DN">DN - Keperluan Dinas</option>
						<option value="CN">CN - Cuti Untuk Keperluan Pribadi</option>
						<option value="CB">CB - Cuti Bersama</option>
						<option value="CI">CI - Cuti Istimewa</option>
						<option value="UL">UL - Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia</option>
						<option value="HC">HC - Hadir Cacat Tanpa Penjelasan</option>
						<option value="AS">AS - Ketidakhadiran Tanpa Penjelasan</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $ProcessLogComposer['widget_data']['processlogslist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
					<input type="submit" class="btn btn-primary" value="Simpan">
				</div>
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif