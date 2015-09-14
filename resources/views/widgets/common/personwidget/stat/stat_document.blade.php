@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($DocumentComposer['widget_data']['widgetlist']['document']))
			<div class="alert alert-callout alert-info no-margin">
				<strong class="pull-right text-info text-lg"><i class="fa fa-file-text fa-2x"></i></strong>
				<h4>
					{{ $DocumentComposer['widget_data']['widgetlist']['document-pagination']->total() }}
				</h4>
				<span class="opacity-50">{{ $DocumentComposer['widget_data']['widgetlist']['title'] }}</span>					
			</div>
		@else
			<div class="alert alert-callout alert-info no-margin">
				<strong class="pull-right text-info text-lg"><i class="fa fa-file-text fa-2x"></i></strong>
				<h4>0</h4>
				<span class="opacity-50">{{ $DocumentComposer['widget_data']['widgetlist']['title'] }}</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif