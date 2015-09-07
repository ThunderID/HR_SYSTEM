@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($AttendanceLogComposer['widget_data']['attendanceloglist']['attendancelog']))
			<div class="alert alert-callout alert-success no-margin">
				<strong class="pull-right text-success text-lg"><i class="fa fa-certificate fa-2x"></i></strong>
				<strong class="text-xl">{{ $AttendanceLogComposer['widget_data']['attendanceloglist']['attendancelog-pagination']->total() }}</strong><br>
				<span class="opacity-50">{{$data['name']}}</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif