@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

@section('widget_title')	
	{{ $widget_title }}	
@overwrite

@section('widget_info')
	{{ $widget_info or '' }} <span class="text-bold">&nbsp; {{ $widget_options['total'] }}</span>
@overwrite

@section('widget_body')	
@overwrite	