@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_errors->count())
	@section('widget_body')
		{{-- @include('widgets.branch.sidemenu') --}}
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif