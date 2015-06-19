@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Template Cuti' : 'Ubah Template Cuti '. $WorkleaveComposer['widget_data']['workleavelist']['workleave']['name']}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $WorkleaveComposer['widget_data']['workleavelist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Nama</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'name', $WorkleaveComposer['widget_data']['workleavelist']['workleave']['name'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Quota</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'quota', $WorkleaveComposer['widget_data']['workleavelist']['workleave']['quota'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $WorkleaveComposer['widget_data']['workleavelist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
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