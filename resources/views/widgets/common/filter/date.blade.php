@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_title')
		<h1> {!! $widget_title  or 'Data Karyawan' !!} </h1>

	@overwrite

	@section('widget_body')
		{!! Form::open(['url' => $widget_options['form_url'], 'class' => 'form-horizontal pt-15  ', 'method' => 'get']) !!}
			
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label class="control-label">Start</label>
					{!!Form::input('date', 'start', null , ['class' => 'form-control date-mask'])!!}							
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label class="control-label">End</label>
					{!!Form::input('date', 'start', null , ['class' => 'form-control date-mask'])!!}							
				</div>
			</div>
			<input type="hidden" class="form-control" id="text" name="org_id" value="{{$data['id']}}">
			<div class="form-group">
				<div class="col-md-12 text-right">
					<input type="submit" class="btn btn-primary" value="Simpan">
				</div>
			</div>
		{!! Form::close() !!}
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif