@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_body')
		<div class="row">
			<div class="col-sm-12 text-center">
				<a href="{{ $widget_options['url_old'] }}" class="btn btn-primary">{{$widget_options['caption_old']}}</a>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				<a href="{{ $widget_options['url_new'] }}" class="btn btn-primary">{{$widget_options['caption_new']}}</a>
			</div>
		</div>
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif