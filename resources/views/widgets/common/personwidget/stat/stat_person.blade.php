@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($PersonComposer['widget_data']['widgetlist']['person']))
			<div class="alert alert-callout alert-danger no-margin">
				<strong class="pull-right text-danger text-lg"><i class="fa fa-users fa-2x"></i></strong>
				<h4>
					{{ $PersonComposer['widget_data']['widgetlist']['person-pagination']->total() }}
				</h4>
				<span class="opacity-50">{{ $PersonComposer['widget_data']['widgetlist']['title'] }}</span>					
			</div>
		@else
			<div class="alert alert-callout alert-danger no-margin">
				<strong class="pull-right text-danger text-lg"><i class="fa fa-users fa-2x"></i></strong>
				<h4>0</h4>
				<span class="opacity-50">{{ $PersonComposer['widget_data']['widgetlist']['title'] }}</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif