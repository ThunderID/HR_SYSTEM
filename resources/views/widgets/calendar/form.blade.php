@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	
@overwrite

@section('widget_body')
	{!! Form::open(['url' => $widget_data['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Nama</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'name', $widget_data['data']['name'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Workdays</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'workdays', $widget_data['data']['workdays'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Start</label>
			</div>	
			<div class="col-md-4">
				{!!Form::input('text', 'start', $widget_data['data']['start'], ['class' => 'form-control'])!!}
			</div>
			<div class="col-md-2">
				<label class="control-label">End</label>
			</div>	
			<div class="col-md-4">
				{!!Form::input('text', 'end', $widget_data['data']['end'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	