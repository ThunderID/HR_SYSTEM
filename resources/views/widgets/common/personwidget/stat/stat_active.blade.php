@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($IdleLogComposer['widget_data']['widgetlist']['idlelog']))
			<div class="alert alert-callout alert-primary no-margin">
				<strong class="pull-right text-primary text-lg"><i class="fa fa-history fa-2x"></i></strong>
				<strong class="text-xl">{{ $IdleLogComposer['widget_data']['widgetlist']['idlelog']['total_active'] }}</strong><br>
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