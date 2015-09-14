@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($PersonComposer['widget_data']['widgetlist']['person']))
			<div class="alert alert-callout alert-info no-margin">
				<strong class="pull-right text-info text-lg"><i class="fa fa-bed fa-2x"></i></strong>
				<h4>{{($PersonComposer['widget_data']['widgetlist']['person']['quotas'] + $PersonComposer['widget_data']['widgetlist']['person']['plus_quotas'] - $PersonComposer['widget_data']['widgetlist']['person']['minus_quotas'] >= 0 ? abs($PersonComposer['widget_data']['widgetlist']['person']['quotas'] + $PersonComposer['widget_data']['widgetlist']['person']['plus_quotas'] - $PersonComposer['widget_data']['widgetlist']['person']['minus_quotas']) : 0)}}
				</h4>
				<span class="opacity-50">{!! $widget_title  or 'Sisa Cuti "'.$data['name'].'" Bulan Ini' !!} </span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif