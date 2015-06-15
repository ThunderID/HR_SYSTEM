@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_body')
		{{-- @include('widgets.branch.sidemenu') --}}
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif