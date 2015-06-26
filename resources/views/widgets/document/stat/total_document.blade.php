@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($DocumentComposer['widget_data']['documentlist']['document']))
			<div class="alert alert-callout alert-info no-margin">
				<strong class="pull-right text-info text-lg"><i class="fa fa-users fa-2x"></i></strong>
				<strong class="text-xl">{{ $DocumentComposer['widget_data']['documentlist']['document-pagination']->total() }}</strong><br>
				<span class="opacity-50">Total Dokumen "{{$data['name']}}"</span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif