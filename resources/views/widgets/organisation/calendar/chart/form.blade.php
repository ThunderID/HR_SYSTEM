@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Jabatan untuk Kalender ' : 'Ubah Jabatan untuk Kalender '). $calendar['name']}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $FollowComposer['widget_data']['followlist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Jabatan</label>
				</div>	
				<div class="col-md-10">
					@include('widgets.chart.select', [
						'widget_options'		=> 	[
														'chartlist'			=>
														[
															'organisation_id'	=> $data['id'],
															'search'			=> ['withattributes' => ['branch']],
															'sort'				=> ['name' => 'asc'],
															'page'				=> 1,
															'per_page'			=> 100,
															'chart_id'			=> $FollowComposer['widget_data']['followlist']['follow']['chart_id'],
														]
													]
					])
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $FollowComposer['widget_data']['followlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
					<input type="submit" class="btn btn-primary" value="Simpan">
				</div>
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif