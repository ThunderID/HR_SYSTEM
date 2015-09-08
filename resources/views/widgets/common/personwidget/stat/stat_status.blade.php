@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($AttendanceLogComposer['widget_data']['widgetlist']['attendancelog']))
			<div class="alert alert-callout alert-success no-margin">
				<strong class="pull-right text-success text-lg"><i class="fa fa-certificate fa-2x"></i></strong>
				<strong class="text-xl">{{ $AttendanceLogComposer['widget_data']['widgetlist']['attendancelog-pagination']->total() }}</strong><br>
				<span class="opacity-50">{{ $AttendanceLogComposer['widget_data']['widgetlist']['title'] }}</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif