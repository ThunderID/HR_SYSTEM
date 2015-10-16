@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Otentikasi Grup ' : 'Ubah Otentikasi Grup ') }} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $AuthGroupComposer['widget_data']['authgroup']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Nama Otentikasi Group</label>
						{!! Form::input('text', 'name', $AuthGroupComposer['widget_data']['authgroup']['authgroup']['name'], ['class' => 'form-control', 'tabindex' => '1']) !!}
					</div>
				</div>
			</div>
			<div class="form-group text-right">				
				<a href="{{ $AuthGroupComposer['widget_data']['authgroup']['route_back'] }}" class="btn btn-default mr-5" tabindex="3">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="2">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif