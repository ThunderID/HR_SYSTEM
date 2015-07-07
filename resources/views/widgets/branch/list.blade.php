@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		{{ $widget_title or 'List Cabang' }}
	@overwrite

	@section('widget_body')
		<ul class="list-unstyled">
			@foreach($BranchComposer['widget_data']['branchlist']['branch'] as $key => $value)
				<li>{{ $value['name'] }}</li>
			@endforeach
		</ul>
	@overwrite
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif
