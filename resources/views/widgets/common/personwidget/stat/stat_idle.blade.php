@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($IdleLogComposer['widget_data']['idleloglist']['idlelog']))
			<div class="alert alert-callout alert-success no-margin">
				<strong class="pull-right text-success text-lg"><i class="fa fa-clock-o fa-2x"></i></strong>
				<strong class="text-xl">{{ $IdleLogComposer['widget_data']['idleloglist']['idlelog-pagination']->total() }}</strong><br>
				<span class="opacity-50">{{$data['name']}}</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif