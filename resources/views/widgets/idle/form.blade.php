@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')	
		<h1> {{ is_null($id) ? 'Tambah Idle' : 'Ubah '}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $IdleComposer['widget_data']['idlelist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Start</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'start', $IdleComposer['widget_data']['idlelist']['idle']['start'], ['class' => 'form-control', 'placeholder' => 'Start'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Flag Idle Pertama</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'idle_1', $IdleComposer['widget_data']['idlelist']['idle']['idle_1'], ['class' => 'form-control', 'placeholder' => 'Idle Pertama'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Flag Idle Kedua</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'idle_2', $IdleComposer['widget_data']['idlelist']['idle']['idle_2'], ['class' => 'form-control', 'placeholder' => 'Idle Kedua'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $IdleComposer['widget_data']['idlelist']['route_edit'] }}" class="btn btn-default mr-5">Batal</a>
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