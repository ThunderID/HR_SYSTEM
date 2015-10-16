@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_title')
		<h1> {!! $widget_title  or 'Data Karyawan' !!} </h1>

	@overwrite

	@section('widget_body')
		{!! Form::open(['url' => $widget_options['form_url'], 'class' => 'form pt-15 no_enter ', 'method' => 'get']) !!}
			<div class="form-group">
				<label>Start</label>
				{!!Form::input('text', 'start', null , ['class' => 'form-control date-mask', 'tabindex' => '1'])!!}	
			</div>
			<div class="form-group">
				<label class="control-label">End</label>
				{!!Form::input('text', 'end', null , ['class' => 'form-control date-mask', 'tabindex' => '2'])!!}		
			</div>
			<input type="hidden" class="form-control" id="text" name="org_id" value="{{$data['id']}}">
			<div class="form-group text-right">
				<input type="submit" class="btn btn-primary" value="Generate" tabindex="3">
				@if(Route::currentRouteName()=='hr.report.attendances.index')
					<input type="submit" name="withschedule" class="btn btn-primary" value="Laporan Status" tabindex="4">
					@if (Input::get('org_id')==Session::get('user.organisationid'))
						<input type="submit" name="PersonalRep" class="btn btn-primary" value="Laporan Saya" tabindex="5">
					@endif
				@endif
			</div>
		{!! Form::close() !!}
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif