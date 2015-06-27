@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	<?php
		$ChartComposer['widget_data']['chartlist']['chart-pagination']->setPath(route('hr.branch.charts.index'));
	?>

	@section('widget_title')
		<h1> {{ $widget_title or 'Struktur Organisasi' }} </h1>
		<small>Total data {{$ChartComposer['widget_data']['chartlist']['chart-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		tes
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif