@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($PersonWorkleaveComposer['widget_data']['workleavelist']['workleave']))
			<div class="alert alert-callout alert-success no-margin">
				<strong class="pull-right text-success text-lg"><i class="fa fa-bullhorn fa-2x"></i></strong>
				<h5>{{ $PersonWorkleaveComposer['widget_data']['workleavelist']['workleave']['total_quota'] }}
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