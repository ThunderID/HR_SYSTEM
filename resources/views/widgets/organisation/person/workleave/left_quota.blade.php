@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($PersonComposer['widget_data']['personlist']['person']))
			<div class="alert alert-callout alert-info no-margin">
				<strong class="pull-right text-info text-lg"><i class="fa fa-bed fa-2x"></i></strong>
				<h5>{{($PersonComposer['widget_data']['personlist']['person']['quotas'] + $PersonComposer['widget_data']['personlist']['person']['plus_quotas'] - $PersonComposer['widget_data']['personlist']['person']['minus_quotas'])}}
				</h5>
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