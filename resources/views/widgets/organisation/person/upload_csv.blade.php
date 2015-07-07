@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ is_null($id) ? 'Tambah Data Karyawan' : 'Ubah Data Karyawan "'. $PersonComposer['widget_data']['personlist']['person']['name'].'"'}} </h1> 
	@overwrite

	@section('widget_body')
		{!! Form::open(['url' => $PersonComposer['widget_data']['personlist']['form_url'], 'class' => 'form no_enter', 'files' => true]) !!}	
			<div class="clearfix">&nbsp;</div>
			<div class="form-group">
				<label>Browse CSV</label>
				<input type="file" name="file_csv">
				<input type="hidden" name="import" value="yes">
				<span id="helpBlock" class="help-block font-12">* Masukkan dalam bentuk .csv</span>
			</div>

			<div class="form-group text-right">
				<a href="{{ $PersonComposer['widget_data']['personlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input class="btn btn-primary" type="submit" value="Import">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif