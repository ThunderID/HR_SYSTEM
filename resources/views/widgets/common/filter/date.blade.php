@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_title')
		<h1> {!! $widget_title  or 'Data Karyawan' !!} </h1>

	@overwrite

	@section('widget_body')
		{!! Form::open(['url' => $widget_options['form_url'], 'class' => 'form pt-15 no_enter ', 'method' => 'get']) !!}
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label>Start</label>
					{!!Form::input('text', 'start', null , ['class' => 'form-control date-mask'])!!}							
				</div>
			</div>
			<div class="clearfix">&nbsp;</div>
			<div class="form-group">
				<label class="control-label">End</label>
				{!!Form::input('text', 'end', null , ['class' => 'form-control date-mask'])!!}		
			</div>
			<input type="hidden" class="form-control" id="text" name="org_id" value="{{$data['id']}}">
			<div class="clearfix">&nbsp;</div>
			<div class="form-group text-right">
				<input type="submit" class="btn btn-primary" value="Generate">
			</div>
		{!! Form::close() !!}
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif