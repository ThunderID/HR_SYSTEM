@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($PersonWorkleaveComposer['widget_data']['widgetlist']['workleave']))
			<div class="alert alert-callout alert-danger no-margin">
				<strong class="pull-right text-danger text-lg"><i class="fa fa-user fa-2x"></i></strong>
				<strong class="text-xl">{{ $PersonWorkleaveComposer['widget_data']['widgetlist']['workleave-pagination']->total() }}</strong><br>
				<span class="opacity-50">{{ $PersonWorkleaveComposer['widget_data']['widgetlist']['title'] }}</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif