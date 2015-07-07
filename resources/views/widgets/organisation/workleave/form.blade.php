@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Template Cuti' : 'Ubah Template Cuti "'. $WorkleaveComposer['widget_data']['workleavelist']['workleave']['name'].'"' }} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $WorkleaveComposer['widget_data']['workleavelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Nama</label>				
				{!!Form::input('text', 'name', $WorkleaveComposer['widget_data']['workleavelist']['workleave']['name'], ['class' => 'form-control', 'tabindex' => 1])!!}				
			</div>
			<div class="form-group">				
				<label class="control-label">Quota</label>				
				{!!Form::input('number', 'quota', $WorkleaveComposer['widget_data']['workleavelist']['workleave']['quota'], ['class' => 'form-control', 'tabindex' => '2'])!!}				
			</div>
			<div class="form-group text-right">				
				<a href="{{ $WorkleaveComposer['widget_data']['workleavelist']['route_back'] }}" class="btn btn-default mr-5" tabindex="4">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="3">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif