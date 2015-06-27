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
		@if(isset($ChartComposer['widget_data']['chartlist']['chart']))
			<div class="row mt-20 mb-20">
				<div class="col-md-3">
					<div class="alert alert-callout alert-danger no-margin">
						<strong class="pull-right text-danger text-lg"><i class="fa fa-users fa-2x"></i></strong>
						<strong class="text-xl">{{ $ChartComposer['widget_data']['chartlist']['chart']['min_employee'] }}</strong><br>
						<span class="opacity-50">Min Pegawai</span>					
					</div>
				</div>
				<div class="col-md-3">
					<div class="alert alert-callout alert-success no-margin">
						<strong class="pull-right text-success text-lg"><i class="fa fa-users fa-2x"></i></strong>
						<strong class="text-xl">{{ $ChartComposer['widget_data']['chartlist']['chart']['ideal_employee'] }}</strong><br>
						<span class="opacity-50">Ideal Pegawai</span>					
					</div>
				</div>
				<div class="col-md-3">
					<div class="alert alert-callout alert-info no-margin">
						<strong class="pull-right text-info text-lg"><i class="fa fa-users fa-2x"></i></strong>
						<strong class="text-xl">{{ $ChartComposer['widget_data']['chartlist']['chart']['current_employee'] }}</strong><br>
						<span class="opacity-50">Total Pegawai</span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="alert alert-callout alert-warning no-margin">
						<strong class="pull-right text-warning text-lg"><i class="fa fa-users fa-2x"></i></strong>
						<strong class="text-xl">{{ $ChartComposer['widget_data']['chartlist']['chart']['max_employee'] }}</strong><br>
						<span class="opacity-50">Max Pegawai</span>					
					</div>
				</div>
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif