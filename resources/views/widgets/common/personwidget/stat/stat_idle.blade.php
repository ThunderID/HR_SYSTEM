@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(!is_null($IdleLogComposer['widget_data']['widgetlist']['idlelog']))
			<div class="alert alert-callout alert-warning no-margin">
				<strong class="pull-right text-warning text-lg"><i class="fa fa-clock-o fa-2x"></i></strong>
				<strong class="text-xl">{{ $IdleLogComposer['widget_data']['widgetlist']['idlelog']['total_idle'] }}</strong><br>
				<span class="opacity-50">{{ $IdleLogComposer['widget_data']['widgetlist']['title'] }}</span>					
			</div>
		@else
			<div class="alert alert-callout alert-warning no-margin">
				<strong class="pull-right text-warning text-lg"><i class="fa fa-clock-o fa-2x"></i></strong>
				<strong class="text-xl">0</strong><br>
				<span class="opacity-50">{{ $IdleLogComposer['widget_data']['widgetlist']['title'] }}</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif