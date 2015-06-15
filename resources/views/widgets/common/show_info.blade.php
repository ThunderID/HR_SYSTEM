@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_title')	
		{{ $widget_title }}	
	@overwrite

	@section('widget_info')
		{{ $widget_info or '' }} <span class="text-bold">&nbsp; {{ $widget_options['total'] or '' }}</span>
	@overwrite

	@section('widget_body')	
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_info')
	@overwrite

	@section('widget_body')
	@overwrite
@endif