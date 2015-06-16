@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Kontak </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $widget_data['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Item</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'item', $widget_data['contact-'.$widget_data['identifier']]['item'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Kontak</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'value', $widget_data['contact-'.$widget_data['identifier']]['value'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Aktif</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'is_default', $widget_data['contact-'.$widget_data['identifier']]['is_default'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<a href="{{ $widget_data['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	