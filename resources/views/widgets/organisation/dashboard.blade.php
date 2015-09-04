@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@foreach ($PersonWidgetComposer['widget_data']['widgetlist']['widget'] as $key => $value)
			@if (count($PersonWidgetComposer['widget_data']['widgetlist']['widget'])==4)
				@if (($key%2)==0)
					</div>
					<div class="row">
				@endif
				<?php $x = json_decode($value['query'], 500);?>
				<div class="col-sm-6">
					@include($value['widget'], $x)
				</div>
			@endif
		@endforeach
		
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif