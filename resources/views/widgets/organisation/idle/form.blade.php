@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')	
		<h1> {{ is_null($id) ? 'Tambah ' : 'Ubah '}} Pengaturan Idle </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $IdleComposer['widget_data']['idlelist']['form_url'], 'class' => 'form no-enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Start</label>				
				{!!Form::input('text', 'start', $IdleComposer['widget_data']['idlelist']['idle']['start'], ['class' => 'form-control date-mask', 'placeholder' => 'Start', 'tabindex' => '1'])!!}				
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Flag Idle Pertama (dalam detik)</label>
						{!!Form::input('numeric', 'idle_1', $IdleComposer['widget_data']['idlelist']['idle']['idle_1'], ['class' => 'form-control', 'placeholder' => 'Idle Pertama', 'tabindex' => '2'])!!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Flag Idle Kedua (dalam detik)</label>
						{!!Form::input('numeric', 'idle_2', $IdleComposer['widget_data']['idlelist']['idle']['idle_2'], ['class' => 'form-control', 'placeholder' => 'Idle Kedua', 'tabindex' => '3'])!!}
					</div>
				</div>
			</div>				
			<div class="form-group text-right">				
				<a href="{{ $IdleComposer['widget_data']['idlelist']['route_edit'] }}" class="btn btn-default mr-5" tabindex="5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="4">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif