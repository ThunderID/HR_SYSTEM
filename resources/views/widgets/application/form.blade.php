@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ is_null($id) ? 'Tambah Aplikasi' : 'Ubah Aplikasi "'. $ApplicationComposer['widget_data']['application']['application']['name'].'"'}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ApplicationComposer['widget_data']['application']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Nama Aplikasi</label>
						{!! Form::input('text', 'name', $ApplicationComposer['widget_data']['application']['application']['name'], ['class' => 'form-control', 'tabindex' => '1']) !!}
					</div>
				</div>
			</div>
			<div class="form-group text-right">				
				<a href="{{ $ApplicationComposer['widget_data']['application']['route_back'] }}" class="btn btn-default mr-5" tabindex="3">Batal</a>
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