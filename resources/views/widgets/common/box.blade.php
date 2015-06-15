@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_body')
	<h4>{{ $widget_title }}</h4>

	<span class="pull-right">
		<a href="" class="btn btn-default"><i class="fa fa-pencil"></i></a>
		<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
	</span>
@overwrite	