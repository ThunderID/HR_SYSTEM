@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> API </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $widget_data['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">API KEY</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'client', $widget_data['api-'.$widget_data['identifier']]['client'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">API SECRET</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('password', 'secret', $widget_data['api-'.$widget_data['identifier']]['secret'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	