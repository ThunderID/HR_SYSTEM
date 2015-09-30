@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Struktur Organisasi Cabang ' : 'Ubah Struktur Organisasi Cabang '). '"'.$branch['name'].'"'}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ChartComposer['widget_data']['chartlist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Nama</label>				
				{!!Form::input('text', 'name', $ChartComposer['widget_data']['chartlist']['chart']['name'], ['class' => 'form-control', 'tabindex' => '1'])!!}				
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">				
						<label class="control-label">Bawahan Dari</label>				
						<select name="chart_id" class="form-control select2" tabindex="2">
							<option value="0"></option>
							@foreach($ChartComposer['widget_data']['chartpath']['chart'] as $key => $value)
								<option value="{{$value['id']}}" @if($value['id']==$ChartComposer['widget_data']['chartlist']['chart']['chart_id']) selected @endif>{{$value['name']}} Departemen {{$value['tag']}}</option>
							@endforeach
						</select>								
					</div>
				</div>				
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Departemen</label>				
						{!!Form::input('text', 'tag', $ChartComposer['widget_data']['chartlist']['chart']['tag'], ['class' => 'form-control', 'tabindex' => '3'])!!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label class="text-left">Jumlah Pegawai Minimum</label>
						{!!Form::input('number', 'min_employee', $ChartComposer['widget_data']['chartlist']['chart']['min_employee'], ['class' => 'form-control', 'min' => '0', 'tabindex' => '5'])!!}
					</div>	
				</div>
				<div class="col-sm-4">					
					<div class="form-group">
						<label class="text-left">Jumlah Pegawai Ideal</label>
						{!!Form::input('number', 'ideal_employee', $ChartComposer['widget_data']['chartlist']['chart']['ideal_employee'], ['class' => 'form-control', 'min' => '0', 'tabindex' => '6'])!!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label class="text-left">Jumlah Pegawai Maximum</label>
						{!!Form::input('number', 'max_employee', $ChartComposer['widget_data']['chartlist']['chart']['max_employee'], ['class' => 'form-control', 'min' => '0', 'tabindex' => '7'])!!}
					</div>
				</div>
			</div>

			@if(is_null($id))
				<div class="form-group">
					<div class="checkbox">
						<label>
							{!!Form::checkbox('affect', '1', '', ['class' => '', 'tabindex' => '9'])!!} Set Kalender Default : 
						</label>
					</div>
				</div>
				
				@include('widgets.organisation.calendar.simplelist', [
					'widget_template'		=> 'panel',
					'widget_title'			=> 'Kalender '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
					'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
					'widget_body_class'		=> '',
					'widget_options'		=> 	[
													'calendarlist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> array_merge([], (isset($filtered['search']) ? $filtered['search'] : [])),
														'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['name' => 'asc']),
														'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
														'page'				=> (Input::has('page') ? Input::get('page') : 1),
														'per_page'			=> 100,
														'route_create'		=> route('hr.calendars.create', ['org_id' => $data['id']])
													]
												]
				])
				@endif

			<div class="form-group text-right">				
				<a href="{{ $ChartComposer['widget_data']['chartlist']['route_back'] }}" class="btn btn-default mr-5" tabindex="9">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="8">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif