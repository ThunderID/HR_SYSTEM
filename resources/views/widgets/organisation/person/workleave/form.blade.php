@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Jatah Cuti ' : 'Ubah Jatah Cuti '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $PersonWorkleaveComposer['widget_data']['personworkleavelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">
				<label class="control-label">Diambil Dari</label>
				@include('widgets.organisation.person.workleave.select', [
					'widget_options'		=> 	[
													'workleavelist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> ['personid' => $person['id'],'status' => (Input::has('confirmed_id') ? 'annual' : ($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['status']=='offer' ? 'confirmed' : '') ), 'ondate' => [date('Y-m-d', strtotime('first day of January this year')), date('Y-m-d', strtotime('last day of December this year'))]],
														'sort'				=> ['name' => 'asc'],
														'page'				=> (Input::has('page') ? Input::get('page') : 1),
														'per_page'			=> 100,
														'workleave_id'		=> $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['person_workleave_id'],
														'tabindex'			=> 1,
													]
												]
				])
			</div>
			@if(Input::has('confirmed_id'))
				{!!Form::hidden('confirmed_id', Input::get('confirmed_id'), ['class' => 'form-control'])!!}
			@endif
			<div class="form-group">
				<label class="control-label">Keterangan (Singkat)</label>
				{!!Form::input('text', 'name', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['name'], ['class' => 'form-control', 'tabindex' => 2])!!}
			</div>

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Jenis</label>
						<select name="status" class="form-control">
							@if(Input::has('confirmed_id'))
								<option value="confirmed" @if($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['status']=='confirmed') selected @endif>Konfirmasi Cuti</option>
							@else
								<option value="offer" @if($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['status']=='offer') selected @endif>Pengajuan Cuti</option>
								<option value="annual" @if($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['status']=='annual') selected @endif>Jatah Cuti Tahunan</option>
								<option value="special" @if($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['status']=='special') selected @endif>Cuti Istimewa</option>
							@endif
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Quota</label>
						{!!Form::input('number', 'quota', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['quota'], ['class' => 'form-control', 'tabindex' => 4])!!}
					</div>	
				</div>
			</div>

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