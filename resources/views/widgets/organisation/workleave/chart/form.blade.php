@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Jatah Cuti '.$workleave['name'].' Untuk Jabatan ' : 'Ubah Jatah Cuti '.$workleave['name'].' Untuk Jabatan ')}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $PersonWorkleaveComposer['widget_data']['personworkleavelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Jabatan</label>								
				@include('widgets.chart.select', [
					'widget_options'		=> 	[
													'chartlist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> ['withattributes' => ['branch']],
														'sort'				=> ['name' => 'asc'],
														'page'				=> 1,
														'per_page'			=> 100,
														'chart_id'			=> $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['chart_id'],
														'tabindex'			=> '1'
													]
												]
				])				
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">				
						<label class="control-label">Start</label>				
						{!!Form::input('text', 'start', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['start'], ['class' => 'form-control date-mask', 'tabindex' => '2'])!!}
					</div>
				</div>
				<div class="col-sm-6">				
					<label class="control-label">End</label>				
					{!!Form::input('text', 'end', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['end'], ['class' => 'form-control date-mask', 'tabindex' => '3'])!!}
				</div>
			</div>
			<div class="form-group">				
				<div class="checkbox">
					<label for="">
					{!!Form::checkbox('is_default', '1', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['is_default'], ['class' => '', 'tabindex' => '4'])!!} Hak Cuti
					</label> 
				</div>				
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $PersonWorkleaveComposer['widget_data']['personworkleavelist']['route_back'] }}" class="btn btn-default mr-5" tabindex="6">Batal</a>
					<input type="submit" class="btn btn-primary" value="Simpan" tabindex="5">
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