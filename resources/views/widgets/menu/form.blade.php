@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ is_null($id) ? 'Tambah Menu ' : 'Ubah Menu '}} "{{$data['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $MenuComposer['widget_data']['menu']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Nama</label>
						{!! Form::input('text', 'name', $MenuComposer['widget_data']['menu']['menu']['name'], ['class' => 'form-control', 'tabindex' => '1']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Tag</label>
						{!! Form::input('text', 'tag', $MenuComposer['widget_data']['menu']['menu']['tag'], ['class' => 'form-control', 'tabindex' => '2']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Deskripsi</label>
						{!! Form::input('text', 'description', $MenuComposer['widget_data']['menu']['menu']['description'], ['class' => 'form-control', 'tabindex' => '3']) !!}
					</div>
				</div>
			</div>
			<div class="form-group text-right">				
				<a href="{{ $MenuComposer['widget_data']['menu']['route_back'] }}" class="btn btn-default mr-5" tabindex="5">Batal</a>
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