@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
		<h1> {!! $widget_title  or 'Otentikasi' !!} </h1>
		
	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')<=2)
			<a href="{{ $AuthenticationComposer['widget_data']['authentication']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@endif
		<div class="clearfix">&nbsp;</div>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>No</th>
						<th>ID Organisation</th>
						<th>Pekerjaan</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr></tr>
				</tbody>
			</table>
		</div>
	@overwrite
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif