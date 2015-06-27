@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($PersonComposer['widget_data']['personlist']['person']))
			<div class="alert alert-callout alert-warning no-margin">
				<strong class="pull-right text-warning text-lg"><i class="fa fa-users fa-2x"></i></strong>
				<strong class="text-xl">{{ $PersonComposer['widget_data']['personlist']['person-pagination']->total() }}</strong><br>
				<span class="opacity-50">{{$PersonComposer['widget_data']['personlist']['title']}}</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif