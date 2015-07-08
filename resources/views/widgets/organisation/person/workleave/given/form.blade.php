@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Jatah Cuti ' : 'Ubah Jatah Cuti '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $PersonWorkleaveComposer['widget_data']['personworkleavelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">
				<label class="control-label">Tambahkan</label>
				@include('widgets.organisation.workleave.select', [
					'widget_options'		=> 	[
													'workleavelist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> [],
														'sort'				=> ['name' => 'asc'],
														'page'				=> (Input::has('page') ? Input::get('page') : 1),
														'per_page'			=> 100,
														'workleave_id'		=> $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['person_workleave_id'],
														'tabindex'			=> 1,
													]
												]
				])
			</div>
			{!!Form::hidden('type', 'given')!!}
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Start</label>
						{!!Form::input('text', 'start', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['start'], ['class' => 'form-control date-mask', 'tabindex' => 5])!!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">End</label>
						{!!Form::input('text', 'end', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['end'], ['class' => 'form-control date-mask', 'tabindex' => 6])!!}
					</div>	
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">Notes</label>
				{!!Form::textarea('notes', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['notes'], ['class' => 'form-control', 'tabindex' => 7])!!}
			</div>
			<div class="form-group text-right">
				<a href="{{ $PersonWorkleaveComposer['widget_data']['personworkleavelist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif